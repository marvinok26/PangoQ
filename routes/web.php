<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItineraryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SavingsWalletController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\ProfileController;
use App\Services\LanguageService;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';

// Include CSRF debug routes in local environment
if (app()->environment('local')) {
    require __DIR__.'/csrf-debug.php';
}

// Language switcher route
Route::get('language/{locale}', function ($locale) {
    app()->make(App\Services\LanguageService::class)->setLanguage($locale);
    return back();
})->name('language.switch');

// Landing page route
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Public pages
// Product
Route::get('/features', function () {
    return view('pages.features');
})->name('features');

Route::get('/pricing', function () {
    return view('pages.pricing');
})->name('pricing');

Route::get('/mobile-app', function () {
    return view('pages.mobile-app');
})->name('mobile-app');

Route::get('/destinations', function () {
    return view('pages.destinations');
})->name('destinations');

// Resources
Route::get('/travel-guides', function () {
    return view('pages.travel-guides');
})->name('travel-guides');

Route::get('/trip-ideas', function () {
    return view('pages.trip-ideas');
})->name('trip-ideas');

Route::get('/blog', function () {
    return view('pages.blog');
})->name('blog');

Route::get('/support', function () {
    return view('pages.support');
})->name('support');

// Company
Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/careers', function () {
    return view('pages.careers');
})->name('careers');

Route::get('/press', function () {
    return view('pages.press');
})->name('press');

Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact');

// Legal
Route::get('/privacy', function () {
    return view('pages.privacy');
})->name('privacy');

Route::get('/terms', function () {
    return view('pages.terms');
})->name('terms');

Route::get('/cookies', function () {
    return view('pages.cookies');
})->name('cookies');

// API Routes (for AJAX requests in trip creation)
Route::prefix('api')->name('api.')->group(function () {
    Route::get('suggestions/get/{id}', function ($id) {
        // Mock API for the demo
        return response()->json([
            'success' => true,
            'id' => $id,
            'name' => $id == 1 ? 'Kecak Fire Dance at Uluwatu' : 'Seafood Dinner at Jimbaran Bay',
            'location' => $id == 1 ? 'Uluwatu Temple' : 'Jimbaran Beach',
            'cost' => $id == 1 ? 15 : 30,
            'category' => $id == 1 ? 'cultural' : 'food',
            'description' => $id == 1 
                ? 'Experience the mesmerizing Kecak Fire Dance at sunset with dramatic clifftop ocean views.' 
                : 'Enjoy fresh seafood grilled to perfection while watching the sunset over Jimbaran Bay.',
        ]);
    })->name('suggestions.get');
});

// Dashboard - Now accessible without authentication
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Profile routes - Now accessible without authentication 
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

// Trip routes - Now accessible without authentication
Route::get('/trips', [TripController::class, 'index'])->name('trips.index');
Route::post('/trips', [TripController::class, 'store'])->name('trips.store');

// Trip detail routes - Now accessible without authentication
Route::get('/trips/{trip}', [TripController::class, 'show'])->name('trips.show');
Route::get('/trips/{trip}/edit', [TripController::class, 'edit'])->name('trips.edit');
Route::put('/trips/{trip}', [TripController::class, 'update'])->name('trips.update');
Route::delete('/trips/{trip}', [TripController::class, 'destroy'])->name('trips.destroy');
Route::post('/trips/{trip}/invite', [TripController::class, 'invite'])->name('trips.invite');

// Trip Planning Routes
Route::get('/plan', function() {
    return view('livewire.pages.trips.create');
})->name('trips.plan');

// Make sure this route properly references the CreateTrip Livewire component
Route::get('/plan/create', function() {
    return view('livewire.pages.trips.create');
})->name('trips.create');

// Trip Planning Steps - Direct access routes for debugging if needed
Route::get('/plan/destination', function () {
    return view('livewire.trips.destination-selection');
})->name('trips.destination');

Route::get('/plan/details', function () {
    return view('livewire.trips.trip-details');
})->name('trips.details');

Route::get('/plan/invite', function () {
    return view('livewire.trips.invite-friends');
})->name('trips.invite-friends');

Route::get('/plan/itinerary', function () {
    return view('livewire.trips.itinerary-planning');
})->name('trips.itinerary-planning');

Route::get('/plan/review', function () {
    return view('livewire.trips.review');
})->name('trips.review');

// Map View
Route::get('/trips/{trip}/map', [TripController::class, 'map'])->name('trips.map');

// Finalize Trip
Route::get('/trips/{trip}/finalize', [TripController::class, 'finalize'])->name('trips.finalize');

// Trip Downloads and Exports
Route::get('/trips/{trip}/download', [TripController::class, 'download'])->name('trips.download');
Route::get('/trips/{trip}/print', [TripController::class, 'print'])->name('trips.print');

// Trip Review
Route::get('/trips/{trip}/review', [TripController::class, 'review'])->name('trips.review');

// Itineraries
Route::get('/trips/{trip}/itinerary', [ItineraryController::class, 'index'])->name('trips.itinerary');
Route::get('/trips/{trip}/itinerary/day/{day}', [ItineraryController::class, 'day'])->name('trips.itinerary.day');
Route::get('/trips/{trip}/itinerary/edit', [ItineraryController::class, 'edit'])->name('trips.itinerary.edit');

// Activities
Route::post('/trips/{trip}/day/{day}', [ActivityController::class, 'store'])->name('trips.activities.store');
Route::put('/activities/{activity}', [ActivityController::class, 'update'])->name('trips.activities.update');
Route::delete('/activities/{activity}', [ActivityController::class, 'destroy'])->name('trips.activities.destroy');

// Savings Wallet
Route::get('/trips/{trip}/savings', [SavingsWalletController::class, 'show'])->name('trips.savings');
Route::get('/trips/{trip}/savings/start', [SavingsWalletController::class, 'start'])->name('trips.savings.start');
Route::post('/trips/{trip}/savings/contribute', [SavingsWalletController::class, 'contribute'])->name('trips.savings.contribute');
Route::post('/trips/{trip}/savings/withdraw', [SavingsWalletController::class, 'withdraw'])->name('trips.savings.withdraw');
Route::get('/trips/{trip}/savings/transactions', [SavingsWalletController::class, 'transactions'])->name('trips.savings.transactions');

// Notifications
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');

// Trip helpers
Route::get('/api/suggestions/{id}', [ActivityController::class, 'getSuggestion'])->name('api.suggestions.get');