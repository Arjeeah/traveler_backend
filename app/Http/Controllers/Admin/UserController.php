<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        }

        // Filter by user type
        if ($request->has('user_type')) {
            $query->where('user_type', $request->user_type);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Simple pagination
        $users = $query->paginate(15);

        return UserResource::collection($users);
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        // Load relationships for more detailed view
        return new UserResource($user->load('trips', 'trips.city.country', 'trips.tasks', 'trips.budgetLogs'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'user_type' => 'required|in:admin,user',
            'sex' => 'nullable|in:male,female',
            'birth_date' => 'nullable|date|before:today',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        return new UserResource($user);
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => ['sometimes', 'required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'sometimes|nullable|string|min:8',
            'user_type' => 'sometimes|required|in:admin,user',
            'status' => 'sometimes|required|in:active,inactive,banned',
            'sex' => 'sometimes|nullable|in:male,female',
            'birth_date' => 'sometimes|nullable|date|before:today',
        ]);

        // Only hash password if it's being updated
        if (isset($validated['password']) && !empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return new UserResource($user);
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        // Prevent deletion of admin users or users with trips
        if ($user->user_type === 'admin' && User::where('user_type', 'admin')->count() <= 1) {
            return response()->json([
                'message' => 'Cannot delete the last admin user'
            ], 422);
        }

        // Check if user has trips
        if ($user->trips()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete user with existing trips'
            ], 422);
        }

        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }

    /**
     * Get user statistics 
     */
    public function stats()
    {
        $stats = [
            'total_users' => User::count(),
            'admin_users' => User::where('user_type', 'admin')->count(),
            'regular_users' => User::where('user_type', 'user')->count(),
            'active_users' => User::where('status', 'active')->count(),
            'inactive_users' => User::where('status', 'inactive')->count(),
            'banned_users' => User::where('status', 'banned')->count(),
            'users_with_trips' => User::has('trips')->count(),
            'recent_users' => User::where('created_at', '>=', now()->subDays(30))->count(),
        ];

        return response()->json($stats);
    }
}