<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\AreaController;
use App\Http\Controllers\Admin\TripController;


// Admin Routes
Route::prefix('admin')->middleware(['auth:sanctum', 'admin'])->group(function () {

    // User Management
    Route::apiResource('users', UserController::class);
    Route::get('users/stats', [UserController::class, 'stats']);

    // Country Management
    Route::apiResource('countries', CountryController::class);

    // City Management
    Route::apiResource('cities', CityController::class);

    // Area Management
    Route::apiResource('areas', AreaController::class);

    // Trip Management
    Route::get('trips', [TripController::class, 'index']);
    Route::get('trips/{trip}', [TripController::class, 'show']);
    Route::get('trips/stats', [TripController::class, 'stats']);
    Route::get('trips/recent-activity', [TripController::class, 'recentActivity']);
});
