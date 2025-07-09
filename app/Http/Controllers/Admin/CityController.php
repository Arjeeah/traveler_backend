<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CityController extends Controller
{
    /**
     * Display a listing of cities
     */
    public function index(Request $request)
    {
        $query = City::with('country');
        
        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }
        
        // Filter by country
        if ($request->has('country_id')) {
            $query->where('country_id', $request->country_id);
        }
        
        // Load areas count
        $query->withCount('areas');
        
        $cities = $query->paginate(15);
        
        return CityResource::collection($cities);
    }

    /**
     * Display the specified city
     */
    public function show(City $city)
    {   
        return new CityResource($city->load('country', 'areas'));
    }

    /**
     * Store a newly created city
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'country_id' => 'required|exists:countries,id',
        ]);
        
        // Check uniqueness within country
        $exists = City::where('name', $validated['name'])
                     ->where('country_id', $validated['country_id'])
                     ->exists();
        
        if ($exists) {
            return response()->json([
                'message' => 'City already exists in this country'
            ], 422);
        }
        
        $city = City::create($validated);
        
        return new CityResource($city->load('country'));
    }

    /**
     * Update the specified city
     */
    public function update(Request $request, City $city)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'country_id' => 'sometimes|required|exists:countries,id',
        ]);
        
        // Check uniqueness within country if name or country is changing
        if (isset($validated['name']) || isset($validated['country_id'])) {
            $name = $validated['name'] ?? $city->name;
            $country_id = $validated['country_id'] ?? $city->country_id;
            
            $exists = City::where('name', $name)
                         ->where('country_id', $country_id)
                         ->where('id', '!=', $city->id)
                         ->exists();
            
            if ($exists) {
                return response()->json([
                    'message' => 'City already exists in this country'
                ], 422);
            }
        }
        
        $city->update($validated);
        
        return new CityResource($city->load('country'));
    }

    /**
     * Remove the specified city
     */
    public function destroy(City $city)
    {
        // Check if city has areas or trips
        if ($city->areas()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete city with existing areas'
            ], 422);
        }
        
        if ($city->trips()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete city with existing trips'
            ], 422);
        }
        
        $city->delete();
        
        return response()->json([
            'message' => 'City deleted successfully'
        ]);
    }
}