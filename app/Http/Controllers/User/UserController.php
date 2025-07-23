<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    /**
     * Get authenticated user info
     */
    public function me(Request $request)
    {
        return new UserResource($request->user());
    }

    /**
     * Update authenticated user profile
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'sex' => 'sometimes|nullable|in:male,female',
            'birth_date' => 'sometimes|nullable|date|before:today',
        ]);

        $request->user()->update($validated);

        return new UserResource($request->user());
    }
}
