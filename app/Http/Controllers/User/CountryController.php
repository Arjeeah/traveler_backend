<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\CountryResource;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    /**
     * Display all countries with their cities and areas
     */
    public function index(Request $request)
    {
        // Load all countries with their cities and areas
        $countries = Country::with([
            'cities' => function ($query) {
                $query->orderBy('name');
            },
            'cities.areas' => function ($query) {
                $query->orderBy('name');
            }
        ])->orderBy('name')->get();

        return CountryResource::collection($countries);
    }
}