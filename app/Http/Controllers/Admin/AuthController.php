<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Login admin user
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Check if user exists, password is correct, and user is admin
        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Check if user is admin
        if ($user->user_type !== 'admin') {
            throw ValidationException::withMessages([
                'email' => ['Access denied. Admin privileges required.'],
            ]);
        }

        // Check user status
        if ($user->status !== 'active') {
            $statusMessages = [
                'inactive' => 'Your admin account is currently inactive. Please contact support.',
                'banned' => 'Your admin account has been banned. Please contact support.',
            ];

            throw ValidationException::withMessages([
                'email' => [$statusMessages[$user->status] ?? 'Your admin account access has been restricted.'],
            ]);
        }

        // Create token for the admin user
        $token = $user->createToken('admin_auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Admin login successful',
            'user' => new UserResource($user),
            'token' => $token,
        ]);
    }

    /**
     * Logout admin user
     */
    public function logout(Request $request)
    {
        // Delete current access token
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Admin logout successful'
        ]);
    }

    /**
     * Get authenticated admin user info
     */
    public function me(Request $request)
    {
        return new UserResource($request->user());
    }

}