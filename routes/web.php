<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItineraryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SavingsWalletController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\TripInvitationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WelcomeController;
use App\Http\Middleware\HandleTripSessionData;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| routes/web.php
| Web Routes - Complete Trip Planning Application
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';

// Include admin routes if they exist
if (file_exists(__DIR__ . '/admin.php')) {
    require __DIR__ . '/admin.php';
}

// Include CSRF debug routes in local environment
if (app()->environment('local')) {
    if (file_exists(__DIR__ . '/csrf-debug.php')) {
        require __DIR__ . '/csrf-debug.php';
    }
}

// ==================== LANGUAGE & LOCALIZATION ====================

Route::get('language/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'es', 'fr', 'de', 'it', 'pt', 'ja', 'zh'])) {
        app()->make(App\Services\LanguageService::class)->setLanguage($locale);
    }
    return back();
})->name('language.switch');

// ==================== PUBLIC PAGES ====================

// Landing page
Route::get('/', [WelcomeController::class, 'index'])->name('home');

// Trip planning form submission from welcome page
Route::post('/plan-trip', [WelcomeController::class, 'planTrip'])->name('plan.trip');

// Public destination search API
Route::get('/api/destinations/search', [WelcomeController::class, 'searchDestinations'])->name('destinations.search');

// Public pages
Route::prefix('pages')->name('pages.')->group(function () {
    Route::get('/features', fn() => view('pages.features'))->name('features');
    Route::get('/pricing', fn() => view('pages.pricing'))->name('pricing');
    Route::get('/mobile-app', fn() => view('pages.mobile-app'))->name('mobile-app');
    Route::get('/destinations', fn() => view('pages.destinations'))->name('destinations');
    
    // Resources
    Route::get('/travel-guides', fn() => view('pages.travel-guides'))->name('travel-guides');
    Route::get('/trip-ideas', fn() => view('pages.trip-ideas'))->name('trip-ideas');
    Route::get('/blog', fn() => view('pages.blog'))->name('blog');
    Route::get('/support', fn() => view('pages.support'))->name('support');
    
    // Company
    Route::get('/about', fn() => view('pages.about'))->name('about');
    Route::get('/careers', fn() => view('pages.careers'))->name('careers');
    Route::get('/press', fn() => view('pages.press'))->name('press');
    Route::get('/contact', fn() => view('pages.contact'))->name('contact');
    
    // Legal
    Route::get('/privacy', fn() => view('pages.privacy'))->name('privacy');
    Route::get('/terms', fn() => view('pages.terms'))->name('terms');
    Route::get('/cookies', fn() => view('pages.cookies'))->name('cookies');
});

// ==================== TRIP INVITATIONS (Public Access) ====================

Route::prefix('invitations')->name('trip-invitations.')->group(function () {
    // Public invitation viewing and handling
    Route::get('/{token}', [TripInvitationController::class, 'show'])->name('show');
    Route::post('/{token}/accept', [TripInvitationController::class, 'accept'])->name('accept');
    Route::post('/{token}/decline', [TripInvitationController::class, 'decline'])->name('decline');
});

// ==================== TRIP PLANNING FLOW (Public Access) ====================

Route::prefix('plan')->name('trips.')->group(function () {
    // Main trip planning entry point
    Route::get('/', function () {
        // Only clear session if starting fresh (not coming from welcome form)
        if (!Session::has('selected_trip_type')) {
            Session::forget([
                'selected_trip_type',
                'selected_trip_template', 
                'selected_destination',
                'trip_details',
                'trip_activities',
                'trip_invites',
                'trip_base_price',
                'trip_total_price',
                'selected_optional_activities'
            ]);
        }
        return view('livewire.pages.trips.create');
    })->name('plan');

    // Alternative create route
    Route::get('/create', function () {
        if (!Session::has('selected_trip_type')) {
            Session::forget([
                'selected_trip_type',
                'selected_trip_template',
                'selected_destination', 
                'trip_details',
                'trip_activities',
                'trip_invites',
                'trip_base_price',
                'trip_total_price',
                'selected_optional_activities'
            ]);
        }
        return view('livewire.pages.trips.create');
    })->name('create');

    // Individual planning steps (for direct access)
    Route::get('/type-selection', fn() => view('livewire.trips.trip-type-selection'))->name('type-selection');
    Route::get('/destination', fn() => view('livewire.trips.destination-selection'))->name('destination');
    Route::get('/details', fn() => view('livewire.trips.trip-details'))->name('details');
    Route::get('/itinerary', fn() => view('livewire.trips.itinerary-planning'))->name('itinerary-planning');
    Route::get('/invite', fn() => view('livewire.trips.invite-friends'))->name('invite-friends');
    Route::get('/review', fn() => view('livewire.trips.review'))->name('review');
    
    // Pre-planned trip selection (standalone)
    Route::get('/pre-planned', function () {
        return view('livewire.pages.trips.pre-planned', ['standalone' => true]);
    })->name('pre-planned');
});

// ==================== TRIP TEMPLATES (Public Browsing) ====================

Route::prefix('trip-templates')->name('trips.templates.')->group(function () {
    Route::get('/', [TripController::class, 'browseTemplates'])->name('index');
    Route::get('/{template}', [TripController::class, 'showTemplate'])->name('show');
});

// ==================== PUBLIC TRIP ROUTES ====================

Route::prefix('trips')->name('trips.')->group(function () {
    // Public trip listing and viewing
    Route::get('/', [TripController::class, 'index'])->name('index');
    Route::get('/{trip}', [TripController::class, 'show'])->name('show')->where('trip', '[0-9]+');
    
    // Trip search (public API)
    Route::get('/search', [TripController::class, 'search'])->name('search');
    
    // Join public trips (requires auth)
    Route::post('/{trip}/join', [TripController::class, 'join'])->name('join')->middleware('auth');
});

// ==================== API ROUTES (Public Access) ====================

Route::prefix('api')->name('api.')->group(function () {
    // Mock suggestion API for demo
    Route::get('suggestions/get/{id}', function ($id) {
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

    // Public trip data API
    Route::get('trips/{trip}', [TripController::class, 'apiShow'])->name('trips.show');
    
    // Destination search API
    Route::get('destinations/search', [WelcomeController::class, 'searchDestinations'])->name('destinations.search');
});

// ==================== AUTHENTICATED USER ROUTES ====================

Route::middleware(['auth', HandleTripSessionData::class])->group(function () {
    
    // ==================== DASHBOARD ====================
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Dashboard welcome (handles post-login trip creation)
    Route::get('/dashboard/welcome', function () {
        $newTripId = Session::get('newly_created_trip_id');
        
        if ($newTripId) {
            Session::forget('newly_created_trip_id');
            return redirect()->route('trips.show', $newTripId)
                           ->with('success', 'Welcome! Your trip has been created successfully.');
        }
        
        return redirect()->route('dashboard');
    })->name('dashboard.welcome');

    // ==================== TRIP MANAGEMENT ====================
    
    Route::prefix('trips')->name('trips.')->group(function () {
        // Trip CRUD operations
        Route::post('/', [TripController::class, 'store'])->name('store');
        Route::get('/{trip}/edit', [TripController::class, 'edit'])->name('edit');
        Route::put('/{trip}', [TripController::class, 'update'])->name('update');
        Route::delete('/{trip}', [TripController::class, 'destroy'])->name('destroy');
        
        // Trip creation from session
        Route::get('/create-from-session', [TripController::class, 'createFromSession'])->name('create-from-session');
        
        // Trip actions
        Route::post('/{trip}/duplicate', [TripController::class, 'duplicate'])->name('duplicate');
        Route::post('/{trip}/leave', [TripController::class, 'leave'])->name('leave');
        Route::post('/{trip}/favorite', [TripController::class, 'toggleFavorite'])->name('favorite');
        
        // Trip exports
        Route::get('/{trip}/export/{format?}', [TripController::class, 'export'])->name('export')
             ->where('format', 'pdf|ical|json');
        
        // Bulk operations
        Route::post('/bulk-action', [TripController::class, 'bulkAction'])->name('bulk-action');
        
        // Trip statistics
        Route::get('/statistics', [TripController::class, 'statistics'])->name('statistics');
        
        // ==================== TRIP INVITATIONS MANAGEMENT ====================
        
        Route::prefix('{trip}')->group(function () {
            Route::get('/invitations', [TripController::class, 'invitations'])->name('invitations');
            Route::post('/invitations', [TripController::class, 'sendInvitations'])->name('send-invitations');
        });
        
        // ==================== TRIP ITINERARIES ====================
        
        Route::prefix('{trip}')->name('itinerary.')->group(function () {
            Route::get('/itinerary', [ItineraryController::class, 'index'])->name('index');
            Route::get('/itinerary/edit', [ItineraryController::class, 'edit'])->name('edit');
            Route::post('/itinerary/{itinerary}/activities', [ActivityController::class, 'store'])->name('activities.store');
            Route::put('/itinerary/activities/{activity}', [ActivityController::class, 'update'])->name('activities.update');
            Route::delete('/itinerary/activities/{activity}', [ActivityController::class, 'destroy'])->name('activities.destroy');
        });
        
        // ==================== TRIP SAVINGS/WALLET ====================
        
        Route::prefix('{trip}/savings')->name('savings.')->group(function () {
            Route::get('/', [SavingsWalletController::class, 'show'])->name('show');
            Route::get('/start', [SavingsWalletController::class, 'start'])->name('start');
            Route::post('/contribute', [SavingsWalletController::class, 'contribute'])->name('contribute');
            Route::post('/withdraw', [SavingsWalletController::class, 'withdraw'])->name('withdraw');
            Route::get('/transactions', [SavingsWalletController::class, 'transactions'])->name('transactions');
        });
    });

    // ==================== INVITATION MANAGEMENT (Authenticated) ====================
    
    Route::prefix('invitations')->name('trip-invitations.')->group(function () {
        Route::post('/{invitation}/resend', [TripInvitationController::class, 'resend'])->name('resend');
        Route::delete('/{invitation}', [TripInvitationController::class, 'cancel'])->name('cancel');
    });

    // ==================== WALLET MANAGEMENT ====================
    
    Route::prefix('wallet')->name('wallet.')->group(function () {
        Route::get('/', [SavingsWalletController::class, 'index'])->name('index');
        Route::get('/contribute', [SavingsWalletController::class, 'showContributeForm'])->name('contribute.form');
        Route::post('/contribute', [SavingsWalletController::class, 'contribute'])->name('contribute');
        Route::get('/withdraw', [SavingsWalletController::class, 'showWithdrawForm'])->name('withdraw.form');
        Route::post('/withdraw', [SavingsWalletController::class, 'withdraw'])->name('withdraw');
        Route::get('/transactions', [SavingsWalletController::class, 'transactions'])->name('transactions');
    });

    // ==================== PROFILE MANAGEMENT ====================
    
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
        
        // Profile sections
        Route::get('/security', [ProfileController::class, 'security'])->name('security');
        Route::patch('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
        Route::get('/notifications', [ProfileController::class, 'notifications'])->name('notifications');
        Route::patch('/notifications', [ProfileController::class, 'updateNotifications'])->name('notifications.update');
        Route::get('/account', [ProfileController::class, 'account'])->name('account');
        Route::patch('/account', [ProfileController::class, 'updateAccount'])->name('account.update');
    });

    // Trip routes
Route::get('/trips', [TripController::class, 'index'])->name('trips.index');
Route::post('/trips', [TripController::class, 'store'])->name('trips.store');

// Trip detail routes
Route::get('/trips/{trip}', [TripController::class, 'show'])->name('trips.show');
Route::get('/trips/{trip}/edit', [TripController::class, 'edit'])->name('trips.edit');
Route::put('/trips/{trip}', [TripController::class, 'update'])->name('trips.update');
Route::delete('/trips/{trip}', [TripController::class, 'destroy'])->name('trips.destroy');
Route::post('/trips/{trip}/invite', [TripController::class, 'invite'])->name('trips.invite');

    // ==================== SETTINGS ====================
    
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'general'])->name('general');
        Route::patch('/', [SettingsController::class, 'updateGeneral'])->name('general.update');
        Route::get('/privacy', [SettingsController::class, 'privacy'])->name('privacy');
        Route::patch('/privacy', [SettingsController::class, 'updatePrivacy'])->name('privacy.update');
        Route::get('/payments', [SettingsController::class, 'payments'])->name('payments');
        Route::patch('/payments', [SettingsController::class, 'updatePayments'])->name('payments.update');
    });

    // ==================== NOTIFICATIONS ====================
    
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/{notification}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read-all');
    });

    // ==================== API ROUTES (Authenticated) ====================
    
    Route::prefix('api')->name('api.')->group(function () {
        // Trip management API
        Route::put('trips/{trip}', [TripController::class, 'apiUpdate'])->name('trips.update');
        
        // User statistics API
        Route::get('user/statistics', [TripController::class, 'statistics'])->name('user.statistics');
        
        // Invitation management API
        Route::post('invitations/{invitation}/resend', [TripInvitationController::class, 'resend'])->name('invitations.resend');
        
        // Activity management API
        Route::resource('activities', ActivityController::class)->except(['index', 'show']);
        
        // Wallet API
        Route::get('wallet/balance', [SavingsWalletController::class, 'getBalance'])->name('wallet.balance');
        Route::post('wallet/quick-contribute', [SavingsWalletController::class, 'quickContribute'])->name('wallet.quick-contribute');
    });
});

// ==================== SOCIAL AUTH ROUTES ====================

if (class_exists('App\Http\Controllers\Auth\SocialAuthController')) {
    Route::prefix('auth')->name('auth.')->group(function () {
        Route::get('/{provider}', [SocialAuthController::class, 'redirect'])->name('social.redirect');
        Route::get('/{provider}/callback', [SocialAuthController::class, 'callback'])->name('social.callback');
    });
}

// ==================== WEBHOOKS & EXTERNAL INTEGRATIONS ====================

Route::prefix('webhooks')->name('webhooks.')->group(function () {
    // Payment webhooks (if implemented)
    Route::post('/stripe', function () {
        // Handle Stripe webhooks
        return response()->json(['status' => 'success']);
    })->name('stripe');
    
    // Email service webhooks
    Route::post('/mailgun', function () {
        // Handle Mailgun webhooks
        return response()->json(['status' => 'success']);
    })->name('mailgun');
});

// ==================== FALLBACK ROUTES ====================

// Handle old route patterns
Route::get('/trip/{id}', function ($id) {
    return redirect()->route('trips.show', $id);
})->where('id', '[0-9]+');

Route::get('/plan-trip', function () {
    return redirect()->route('trips.plan');
});

// 404 handling for trip-related routes
Route::fallback(function () {
    if (request()->is('trips/*') || request()->is('plan/*')) {
        return response()->view('errors.404-trip', [], 404);
    }
    
    return response()->view('errors.404', [], 404);
});

// ==================== DEVELOPMENT & DEBUG ROUTES ====================

if (app()->environment('local', 'staging')) {
    Route::prefix('dev')->name('dev.')->group(function () {
        // Test email templates
        Route::get('/email/invitation/{invitation}', function ($invitationId) {
            $invitation = App\Models\TripInvitation::findOrFail($invitationId);
            return new App\Mail\TripInvitationMail($invitation);
        })->name('email.invitation');
        
        // Test session data
        Route::get('/session', function () {
            return response()->json([
                'session_data' => session()->all(),
                'auth_user' => auth()->user()
            ]);
        })->name('session');
        
        // Clear session data
        Route::post('/session/clear', function () {
            session()->flush();
            return redirect()->back()->with('success', 'Session cleared');
        })->name('session.clear');
        
        // Test trip creation
        Route::get('/test-trip-creation', function () {
            if (!auth()->check()) {
                return 'Please login first';
            }
            
            // Set up test session data
            session([
                'selected_trip_type' => 'self_planned',
                'selected_destination' => ['name' => 'Test Destination', 'country' => 'Test Country'],
                'trip_details' => [
                    'title' => 'Test Trip',
                    'start_date' => now()->addDays(30)->format('Y-m-d'),
                    'end_date' => now()->addDays(37)->format('Y-m-d'),
                    'travelers' => 2,
                    'budget' => 1000
                ],
                'trip_invites' => [
                    ['name' => 'Test Friend', 'email' => 'test@example.com', 'message' => 'Join my trip!']
                ],
                'trip_data_not_saved' => true
            ]);
            
            return redirect()->route('trips.create-from-session');
        })->name('test.trip.creation');
    });
}

// ==================== ROUTE MODEL BINDING ====================

Route::bind('trip', function ($value) {
    return App\Models\Trip::findOrFail($value);
});

Route::bind('invitation', function ($value) {
    return App\Models\TripInvitation::findOrFail($value);
});

Route::bind('template', function ($value) {
    return App\Models\TripTemplate::findOrFail($value);
});

Route::bind('activity', function ($value) {
    return App\Models\Activity::findOrFail($value);
});

Route::bind('itinerary', function ($value) {
    return App\Models\Itinerary::findOrFail($value);
});

Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'timestamp' => now(),
        'app' => config('app.name')
    ]);
});