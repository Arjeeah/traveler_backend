<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AreaResource;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AreaController extends Controller
{
    /**
     * Display a listing of areas
     */
    public function index(Request $request)
    {
        $query = Area::with('city.country');

        // Search functionality
        if ($request->has('city_id')) {
            $query->where('city_id', $request->city_id);
        }

        // Then apply search within the city filter
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // Filter by recommendation status
        if ($request->has('is_recommended')) {
            $query->where('is_recommended', $request->boolean('is_recommended'));
        }

        // Load trips count
        $query->withCount('trips');

        $areas = $query->paginate(15);

        return AreaResource::collection($areas);
    }

    /**
     * Display the specified area
     */
    public function show(Area $area)
    {
        return new AreaResource($area->load('city.country'));
    }

    /**
     * Store a newly created area
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id',
            'description' => 'nullable|string|max:1000',
            'is_recommended' => 'boolean',
        ]);

        // Check uniqueness within city
        $exists = Area::where('name', $validated['name'])
            ->where('city_id', $validated['city_id'])
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Area already exists in this city'
            ], 422);
        }

        $area = Area::create($validated);

        return new AreaResource($area->load('city.country'));
    }

    /**
     * Update the specified area
     */
    public function update(Request $request, Area $area)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'city_id' => 'sometimes|required|exists:cities,id',
            'description' => 'sometimes|nullable|string|max:1000',
            'is_recommended' => 'sometimes|boolean',
        ]);

        // Check uniqueness within city if name or city is changing
        if (isset($validated['name']) || isset($validated['city_id'])) {
            $name = $validated['name'] ?? $area->name;
            $city_id = $validated['city_id'] ?? $area->city_id;

            $exists = Area::where('name', $name)
                ->where('city_id', $city_id)
                ->where('id', '!=', $area->id)
                ->exists();

            if ($exists) {
                return response()->json([
                    'message' => 'Area already exists in this city'
                ], 422);
            }
        }

        $area->update($validated);

        return new AreaResource($area->load('city.country'));
    }

    /**
     * Remove the specified area
     */
    public function destroy(Area $area)
    {
        // Check if area has trips
        if ($area->trips()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete area with existing trips'
            ], 422);
        }

        $area->delete();

        return response()->json([
            'message' => 'Area deleted successfully'
        ]);
    }
}