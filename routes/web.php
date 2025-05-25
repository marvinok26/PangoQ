<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItineraryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SavingsWalletController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\ProfileController;
use App\Services\LanguageService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| routes/web.php
| Web Routes
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';

// Include CSRF debug routes in local environment
if (app()->environment('local')) {
    require __DIR__ . '/csrf-debug.php';
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

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// Profile routes
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

// Trip routes
Route::get('/trips', [TripController::class, 'index'])->name('trips.index');
Route::post('/trips', [TripController::class, 'store'])->name('trips.store');

// Trip detail routes
Route::get('/trips/{trip}', [TripController::class, 'show'])->name('trips.show');
Route::get('/trips/{trip}/edit', [TripController::class, 'edit'])->name('trips.edit');
Route::put('/trips/{trip}', [TripController::class, 'update'])->name('trips.update');
Route::delete('/trips/{trip}', [TripController::class, 'destroy'])->name('trips.destroy');
Route::post('/trips/{trip}/invite', [TripController::class, 'invite'])->name('trips.invite');

// Trip Planning Routes (Main entry point)
Route::get('/plan', function () {
    // Force clear any previous trip planning session data to start fresh
    Session::forget([
        'selected_trip_type',
        'selected_trip_template',
        'selected_destination',
        'trip_details',
        'trip_activities',
        'trip_invites'
    ]);

    // Use the view that exists in your project
    return view('livewire.pages.trips.create');
})->name('trips.plan');

// Make sure create route also clears session
Route::get('/plan/create', function () {
    // Force clear any previous trip planning session data to start fresh
    Session::forget([
        'selected_trip_type',
        'selected_trip_template',
        'selected_destination',
        'trip_details',
        'trip_activities',
        'trip_invites'
    ]);

    // Use the view that exists in your project
    return view('livewire.pages.trips.create');
})->name('trips.create');

// Pre-planned trip templates - NEW ROUTES FOR DUAL PATH
Route::get('/trip-templates', [TripController::class, 'browseTemplates'])->name('trips.templates');
Route::get('/trip-templates/{template}', [TripController::class, 'showTemplate'])->name('trips.templates.show');

// Trip Planning Steps - Direct access routes for debugging if needed
Route::get('/plan/type-selection', function () {
    return view('livewire.trips.trip-type-selection');
})->name('trips.type-selection');

Route::get('/plan/destination', function () {
    return view('livewire.trips.destination-selection');
})->name('trips.destination');

Route::get('/plan/pre-planned', function () {
    return view('livewire.trips.pre-planned-trip-selection');
})->name('trips.pre-planned');

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







// Dashboard routes - All protected with auth middleware
Route::middleware(['auth'])->group(function () {
    // Dashboard home
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Trips routes
    Route::get('/trips', [TripController::class, 'index'])->name('trips.index');
    Route::post('/trips', [TripController::class, 'store'])->name('trips.store');
    Route::get('/trips/{trip}', [TripController::class, 'show'])->name('trips.show');
    Route::get('/trips/{trip}/edit', [TripController::class, 'edit'])->name('trips.edit');
    Route::put('/trips/{trip}', [TripController::class, 'update'])->name('trips.update');
    Route::delete('/trips/{trip}', [TripController::class, 'destroy'])->name('trips.destroy');
    Route::post('/trips/{trip}/invite', [TripController::class, 'invite'])->name('trips.invite');

    // Trip Planning Routes
    // Route::get('/plan', function() {
    //     Session::forget([
    //         'selected_trip_type',
    //         'selected_trip_template',
    //         'selected_destination',
    //         'trip_details',
    //         'trip_activities',
    //         'trip_invites'
    //     ]);

    //     return view('livewire.pages.trips.create');
    // })->name('trips.plan');

    // Wallet routes
    Route::get('/wallet', [SavingsWalletController::class, 'index'])->name('wallet.index');
    Route::get('/wallet/contribute', [SavingsWalletController::class, 'showContributeForm'])->name('wallet.contribute.form');
    Route::post('/wallet/contribute', [SavingsWalletController::class, 'contribute'])->name('wallet.contribute');
    Route::get('/wallet/withdraw', [SavingsWalletController::class, 'showWithdrawForm'])->name('wallet.withdraw.form');
    Route::post('/wallet/withdraw', [SavingsWalletController::class, 'withdraw'])->name('wallet.withdraw');
    Route::get('/wallet/transactions', [SavingsWalletController::class, 'transactions'])->name('wallet.transactions');

    // Trip specific wallet routes
    Route::get('/trips/{trip}/savings', [SavingsWalletController::class, 'show'])->name('trips.savings');
    Route::get('/trips/{trip}/savings/start', [SavingsWalletController::class, 'start'])->name('trips.savings.start');
    Route::post('/trips/{trip}/savings/contribute', [SavingsWalletController::class, 'contribute'])->name('trips.savings.contribute');
    Route::post('/trips/{trip}/savings/withdraw', [SavingsWalletController::class, 'withdraw'])->name('trips.savings.withdraw');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/security', [ProfileController::class, 'security'])->name('profile.security');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::get('/profile/notifications', [ProfileController::class, 'notifications'])->name('profile.notifications');
    Route::patch('/profile/notifications', [ProfileController::class, 'updateNotifications'])->name('profile.notifications.update');
    Route::get('/profile/account', [ProfileController::class, 'account'])->name('profile.account');
    Route::patch('/profile/account', [ProfileController::class, 'updateAccount'])->name('profile.account.update');
    // Settings routes
    Route::get('/settings', [SettingsController::class, 'general'])->name('settings.general');
    Route::patch('/settings', [SettingsController::class, 'updateGeneral'])->name('settings.general.update');
    Route::get('/settings/privacy', [SettingsController::class, 'privacy'])->name('settings.privacy');
    Route::patch('/settings/privacy', [SettingsController::class, 'updatePrivacy'])->name('settings.privacy.update');
    Route::get('/settings/payments', [SettingsController::class, 'payments'])->name('settings.payments');
    Route::patch('/settings/payments', [SettingsController::class, 'updatePayments'])->name('settings.payments.update');

    // Itinerary routes
    Route::get('/trips/{trip}/itinerary', [ItineraryController::class, 'index'])->name('trips.itinerary');
    Route::get('/trips/{trip}/itinerary/edit', [ItineraryController::class, 'edit'])->name('trips.itinerary.edit');
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');

    Route::get('/trips/create-from-session', [TripController::class, 'createFromSession'])
    ->name('trips.create-from-session')
    ->middleware('auth');
});
