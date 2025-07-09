<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CountryResource;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CountryController extends Controller
{
    /**
     * Display a listing of countries
     */
    public function index(Request $request)
    {
        $query = Country::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        // Load cities count
        $query->withCount('cities');

        $countries = $query->paginate(15);

        return CountryResource::collection($countries);
    }

    /**
     * Display the specified country
     */
    public function show(Country $country)
    {
        // Load cities with their areas
        return new CountryResource($country->load('cities', 'cities.areas'));
    }

    /**
     * Store a newly created country
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:countries,name',
        ]);

        $country = Country::create($validated);

        return new CountryResource($country);
    }

    /**
     * Update the specified country
     */
    public function update(Request $request, Country $country)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('countries')->ignore($country->id)],
        ]);

        $country->update($validated);

        return new CountryResource($country);
    }

    /**
     * Remove the specified country
     */
    public function destroy(Country $country)
    {
        // Check if country has cities
        if ($country->cities()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete country with existing cities'
            ], 422);
        }

        $country->delete();

        return response()->json([
            'message' => 'Country deleted successfully'
        ]);
    }
}
