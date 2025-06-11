<?php

use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API routes for MVP (protected by auth:sanctum middleware)
Route::middleware('auth:sanctum')->group(function () {
    // Trips
    Route::get('/trips', function (Request $request) {
        return $request->user()->trips()->latest()->get();
    });
    
    Route::get('/trips/{trip}', function (Request $request, Trip $trip) {
        // Check if user is member of the trip
        if (!$trip->members()->where('users.id', $request->user()->id)->exists() && $trip->creator_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        return $trip->load(['creator', 'members', 'itineraries', 'savingsWallet']);
    });
    
    // Trip members
    Route::get('/trips/{trip}/members', function (Request $request, Trip $trip) {
        // Check if user is member of the trip
        if (!$trip->members()->where('users.id', $request->user()->id)->exists() && $trip->creator_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        return $trip->members;
    });
    
    // Savings wallet
    Route::get('/trips/{trip}/savings', function (Request $request, Trip $trip) {
        // Check if user is member of the trip
        if (!$trip->members()->where('users.id', $request->user()->id)->exists() && $trip->creator_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        return $trip->savingsWallet->load('transactions');
    });
    
    // Notifications
    Route::get('/notifications', function (Request $request) {
        return $request->user()->notifications()->latest()->take(20)->get();
    });
});