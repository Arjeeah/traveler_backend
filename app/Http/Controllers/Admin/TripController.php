<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\TripResource;
use App\Models\Trip;
use Illuminate\Http\Request;

class TripController extends Controller
{
    /**
     * Display a listing of trips
     */
    public function index(Request $request)
    {
        $query = Trip::with(['user', 'city.country']);

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%");
        }

        // Filter by user
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by city
        if ($request->has('city_id')) {
            $query->where('city_id', $request->city_id);
        }

        // Filter by date range
        if ($request->has('start_date')) {
            $query->where('start_date', '>=', $request->start_date);
        }
        if ($request->has('end_date')) {
            $query->where('end_date', '<=', $request->end_date);
        }

        // Filter by current/upcoming/past trips
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

        // Filter by number of people
        if ($request->has('min_people')) {
            $query->where('number_of_people', '>=', $request->min_people);
        }
        if ($request->has('max_people')) {
            $query->where('number_of_people', '<=', $request->max_people);
        }

        // Load counts for additional info
        $query->withCount(['tasks', 'budgetLogs']);

        $trips = $query->paginate(15);

        return TripResource::collection($trips);
    }

    /**
     * Display the specified trip
     */
    public function show(Trip $trip)
    {
        // Load all relationships for comprehensive view
        $trip->load([
            'user',
            'city.country',
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
     * Get trip statistics 
     */
    public function stats(Request $request)
    {
        $today = now()->toDateString();

        $stats = [
            'total_trips' => Trip::count(),
            'upcoming_trips' => Trip::where('start_date', '>', $today)->count(),
            'current_trips' => Trip::where('start_date', '<=', $today)
                ->where('end_date', '>=', $today)
                ->count(),
            'past_trips' => Trip::where('end_date', '<', $today)->count(),
            'total_budget' => Trip::sum('budget'),
            'average_budget' => Trip::avg('budget'),
            'total_travelers' => Trip::sum('number_of_people'),
            'trips_this_month' => Trip::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Get recent activity 
     */
    public function recentActivity()
    {
        $recentTrips = Trip::with(['user', 'city.country'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return TripResource::collection($recentTrips);
    }
}