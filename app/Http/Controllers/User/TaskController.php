<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Models\Trip;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Get tasks for a trip
     */
    public function index(Request $request, Trip $trip)
    {
        // Check if trip belongs to user
        if ($trip->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $tasks = $trip->tasks()->orderBy('created_at', 'desc')->get();

        return TaskResource::collection($tasks);
    }

    /**
     * Create new task for trip
     */
    public function store(Request $request, Trip $trip)
    {
        // Check if trip belongs to user
        if ($trip->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'priority' => 'nullable|in:low,medium,high',
        ]);

        $validated['trip_id'] = $trip->id;
        $validated['priority'] = $validated['priority'] ?? 'medium';

        $task = Task::create($validated);

        return new TaskResource($task);
    }

    /**
     * Update task
     */
    public function update(Request $request, Task $task)
    {
        // Check if task belongs to user's trip
        if ($task->trip->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'is_done' => 'sometimes|boolean',
            'priority' => 'sometimes|in:low,medium,high',
        ]);

        $task->update($validated);

        return new TaskResource($task);
    }

    /**
     * Delete task
     */
    public function destroy(Request $request, Task $task)
    {
        // Check if task belongs to user's trip
        if ($task->trip->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $task->delete();

        return response()->json([
            'message' => 'Task deleted successfully'
        ]);
    }
}

