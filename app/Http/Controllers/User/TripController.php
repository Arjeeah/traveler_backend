<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\TripResource;
use App\Models\Trip;
use Illuminate\Http\Request;

class TripController extends Controller
{
    /**
     * Display user's trips
     */
    public function index(Request $request)
    {
        $query = Trip::where('user_id', $request->user()->id)
            ->with(['city.country']);

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%");
        }

        // Filter by status
        if ($request->has('status')) {
            $today = now()->toDateString();

            switch ($request->status) {
                case 'upcoming':
                    $query->where('start_date', '>', $today);
                    break;
                case 'current':
                    $query->where('start_date', '<=', $today)
                        ->where('end_date', '>=', $today);
                    break;
                case 'past':
                    $query->where('end_date', '<', $today);
                    break;
            }
        }

        // Load counts
        $query->withCount(['tasks', 'areas', 'budgetLogs']);

        $trips = $query->orderBy('start_date', 'desc')->paginate(10);

        return TripResource::collection($trips);
    }

    /**
     * Display specific trip
     */
    public function show(Request $request, Trip $trip)
    {
        // Check if trip belongs to user
        if ($trip->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Load all relationships
        $trip->load([
            'city.country',
            'city.areas',
            'areas' => function ($query) {
                $query->orderBy('trip_areas.order');
            },
            'tasks' => function ($query) {
                $query->orderBy('created_at', 'desc');
            },
            'budgetLogs' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }
        ]);

        return new TripResource($trip);
    }

    /**
     * Create new trip
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'city_id' => 'required|exists:cities,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'budget' => 'nullable|numeric|min:0',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'number_of_people' => 'required|integer|min:1|max:50',
        ]);

        $validated['user_id'] = $request->user()->id;

        $trip = Trip::create($validated);

        return new TripResource($trip->load('city.country'));
    }

    /**
     * Update trip
     */
    public function update(Request $request, Trip $trip)
    {
        // Check if trip belongs to user
        if ($trip->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'city_id' => 'sometimes|required|exists:cities,id',
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string|max:1000',
            'budget' => 'sometimes|nullable|numeric|min:0',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date|after:start_date',
            'number_of_people' => 'sometimes|required|integer|min:1|max:50',
        ]);

        $trip->update($validated);

        return new TripResource($trip->load('city.country'));
    }

    /**
     * Delete trip
     */
    public function destroy(Request $request, Trip $trip)
    {
        // Check if trip belongs to user
        if ($trip->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $trip->delete();

        return response()->json([
            'message' => 'Trip deleted successfully'
        ]);
    }
}
