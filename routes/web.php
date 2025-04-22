<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItineraryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SavingsWalletController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Landing page route
Route::get('/', function () {
    return view('welcome'); 
})->name('home');

// Language switcher route
Route::get('language/{locale}', function ($locale) {
    app()->make(App\Services\LanguageService::class)->setLanguage($locale);
    return back();
})->name('language.switch');

// Trip Planning Route - Accessible without login
Route::get('/plan', [TripController::class, 'create'])->name('trips.plan');

// Routes that require authentication
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Trip routes
    Route::get('/trips', [TripController::class, 'index'])->name('trips.index');
    Route::post('/trips', [TripController::class, 'store'])->name('trips.store');
    
    // Routes that require trip membership
    Route::middleware(['tripmember'])->group(function () {
        Route::get('/trips/{trip}', [TripController::class, 'show'])->name('trips.show');
        Route::get('/trips/{trip}/edit', [TripController::class, 'edit'])->name('trips.edit');
        Route::put('/trips/{trip}', [TripController::class, 'update'])->name('trips.update');
        Route::delete('/trips/{trip}', [TripController::class, 'destroy'])->name('trips.destroy');
        Route::post('/trips/{trip}/invite', [TripController::class, 'invite'])->name('trips.invite');
        
        // Itineraries
        Route::get('/trips/{trip}/itineraries', [ItineraryController::class, 'index'])->name('trips.itineraries.index');
        Route::get('/trips/{trip}/itineraries/{itinerary}', [ItineraryController::class, 'show'])->name('trips.itineraries.show');
        Route::put('/trips/{trip}/itineraries/{itinerary}', [ItineraryController::class, 'update'])->name('trips.itineraries.update');
        
        // Activities
        Route::post('/trips/{trip}/itineraries/{itinerary}/activities', [ActivityController::class, 'store'])->name('trips.itineraries.activities.store');
        Route::put('/trips/{trip}/itineraries/{itinerary}/activities/{activity}', [ActivityController::class, 'update'])->name('trips.itineraries.activities.update');
        Route::delete('/trips/{trip}/itineraries/{itinerary}/activities/{activity}', [ActivityController::class, 'destroy'])->name('trips.itineraries.activities.destroy');
        
        // Savings Wallet
        Route::get('/trips/{trip}/savings', [SavingsWalletController::class, 'show'])->name('trips.savings.show');
        Route::get('/trips/{trip}/savings/edit', [SavingsWalletController::class, 'edit'])->name('trips.savings.edit');
        Route::put('/trips/{trip}/savings', [SavingsWalletController::class, 'update'])->name('trips.savings.update');
        Route::post('/trips/{trip}/savings/contribute', [SavingsWalletController::class, 'contribute'])->name('trips.savings.contribute');
        Route::post('/trips/{trip}/savings/withdraw', [SavingsWalletController::class, 'withdraw'])->name('trips.savings.withdraw');
        Route::get('/trips/{trip}/savings/transactions', [SavingsWalletController::class, 'transactions'])->name('trips.savings.transactions');
    });
    
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
});

// Include authentication routes from auth.php
require __DIR__.'/auth.php';