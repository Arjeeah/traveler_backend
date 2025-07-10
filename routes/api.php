<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\AreaController;
use App\Http\Controllers\Admin\TripController;
use App\Http\Controllers\User\CountryController as UserCountryController;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\TripController as UserTripController;
use App\Http\Controllers\User\TripAreaController;
use App\Http\Controllers\User\TaskController;
use App\Http\Controllers\User\BudgetController;



// Admin Routes
Route::prefix('admin')->group(function () {

    Route::post('register', [App\Http\Controllers\Admin\AuthController::class, 'register']);
    Route::post('login', [App\Http\Controllers\Admin\AuthController::class, 'login']);

    Route::middleware(['auth:sanctum', 'admin'])->group(function () {

        Route::post('logout', [App\Http\Controllers\Admin\AuthController::class, 'logout']);
        Route::get('me', [App\Http\Controllers\Admin\AuthController::class, 'me']);

        Route::post('logout', [App\Http\Controllers\Admin\AuthController::class, 'logout']);
        Route::get('me', [App\Http\Controllers\Admin\AuthController::class, 'me']);

        // User Management
        Route::get('users/stats', [UserController::class, 'stats']);
        Route::apiResource('users', UserController::class);
       

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

});


// User Routes
Route::prefix('user')->group(function () {

    Route::post('register', [App\Http\Controllers\User\AuthController::class, 'register']);
    Route::post('login', [App\Http\Controllers\User\AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);

        Route::get('countries', [UserCountryController::class, 'index']);

        Route::apiResource('trips', UserTripController::class);

        // Trip Areas
        Route::get('trips/{trip}/areas', [TripAreaController::class, 'index']);
        Route::post('trips/{trip}/areas', [TripAreaController::class, 'store']);
        Route::delete('trips/{trip}/areas/{area}', [TripAreaController::class, 'destroy']);
        Route::put('trips/{trip}/areas/reorder', [TripAreaController::class, 'reorder']);

        // Tasks
        Route::get('trips/{trip}/tasks', [TaskController::class, 'index']);
        Route::post('trips/{trip}/tasks', [TaskController::class, 'store']);
        Route::put('tasks/{task}', [TaskController::class, 'update']);
        Route::delete('tasks/{task}', [TaskController::class, 'destroy']);

        // Budget
        Route::get('trips/{trip}/budget', [BudgetController::class, 'index']);
        Route::post('trips/{trip}/budget', [BudgetController::class, 'store']);
        Route::delete('trips/{trip}/budget/{budgetLog}', [BudgetController::class, 'destroy']);
    });
});
