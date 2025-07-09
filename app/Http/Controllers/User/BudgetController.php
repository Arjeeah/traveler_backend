<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\BudgetLogResource;
use App\Models\Trip;
use App\Models\BudgetLog;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    /**
     * Get budget logs for a trip
     */
    public function index(Request $request, Trip $trip)
    {
        // Check if trip belongs to user
        if ($trip->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $budgetLogs = $trip->budgetLogs()->orderBy('created_at', 'desc')->get();

        return response()->json([
            'current_budget' => $trip->budget,
            'budget_logs' => BudgetLogResource::collection($budgetLogs)
        ]);
    }

    /**
     * Add new budget item and update trip budget
     */
    public function store(Request $request, Trip $trip)
    {
        // Check if trip belongs to user
        if ($trip->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'after_subtraction' => 'required|numeric|min:0',
        ]);

        // Create budget log
        $budgetLog = BudgetLog::create([
            'trip_id' => $trip->id,
            'title' => $validated['title'],
            'amount' => $validated['amount'],
        ]);

        // Update trip budget to "after_subtraction" amount
        $trip->update([
            'budget' => $validated['after_subtraction']
        ]);

        return response()->json([
            'message' => 'Budget item added successfully',
            'budget_log' => new BudgetLogResource($budgetLog),
            'new_budget' => $trip->budget
        ]);
    }

    /**
     * Delete budget log and recalculate trip budget
     */
    public function destroy(Request $request, Trip $trip, BudgetLog $budgetLog)
    {
        // Check if trip belongs to user
        if ($trip->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Check if budget log belongs to trip
        if ($budgetLog->trip_id !== $trip->id) {
            return response()->json(['message' => 'Budget log not found'], 404);
        }

        $budgetLog->delete();

        return response()->json([
            'message' => 'Budget log deleted successfully'
        ]);
    }
}
