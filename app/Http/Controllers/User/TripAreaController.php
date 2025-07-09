<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\AreaResource;
use App\Models\Trip;
use App\Models\Area;
use Illuminate\Http\Request;

class TripAreaController extends Controller
{
    /**
     * Get areas for a trip
     */
    public function index(Request $request, Trip $trip)
    {
        // Check if trip belongs to user
        if ($trip->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $areas = $trip->areas()->with('city.country')->orderBy('trip_areas.order')->get();

        return AreaResource::collection($areas);
    }

    /**
     * Add area to trip
     */
    public function store(Request $request, Trip $trip)
    {
        // Check if trip belongs to user
        if ($trip->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'area_id' => 'required|exists:areas,id',
            'order' => 'nullable|integer|min:0',
        ]);

        // Check if area already exists in trip
        if ($trip->areas()->where('area_id', $validated['area_id'])->exists()) {
            return response()->json([
                'message' => 'Area already added to this trip'
            ], 422);
        }

        // Set order to last position if not provided
        if (!isset($validated['order'])) {
            $validated['order'] = $trip->areas()->count();
        }

        $trip->areas()->attach($validated['area_id'], [
            'order' => $validated['order']
        ]);

        $area = Area::with('city.country')->find($validated['area_id']);

        return new AreaResource($area);
    }

    /**
     * Remove area from trip
     */
    public function destroy(Request $request, Trip $trip, Area $area)
    {
        // Check if trip belongs to user
        if ($trip->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Check if area exists in trip
        if (!$trip->areas()->where('area_id', $area->id)->exists()) {
            return response()->json([
                'message' => 'Area not found in this trip'
            ], 404);
        }

        $trip->areas()->detach($area->id);

        return response()->json([
            'message' => 'Area removed from trip successfully'
        ]);
    }

    /**
     * Reorder areas in trip
     */
    public function reorder(Request $request, Trip $trip)
    {
        // Check if trip belongs to user
        if ($trip->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'areas' => 'required|array',
            'areas.*.area_id' => 'required|exists:areas,id',
            'areas.*.order' => 'required|integer|min:0',
        ]);

        // Update order for each area
        foreach ($validated['areas'] as $areaData) {
            $trip->areas()->updateExistingPivot($areaData['area_id'], [
                'order' => $areaData['order']
            ]);
        }

        return response()->json([
            'message' => 'Areas reordered successfully'
        ]);
    }
}