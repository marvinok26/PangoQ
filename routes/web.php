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
use App\Http\Controllers\WelcomeController;
use App\Services\LanguageService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| routes/web.php
| Web Routes
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';

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
Route::get('/', [WelcomeController::class, 'index'])->name('home');

// Destination search API route
Route::get('/api/destinations/search', [WelcomeController::class, 'searchDestinations'])->name('destinations.search');

// Trip planning form submission route
Route::post('/plan-trip', [WelcomeController::class, 'planTrip'])->name('plan.trip');

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
    // Only clear session if starting fresh (not coming from welcome form)
    if (!Session::has('selected_trip_type')) {
        Session::forget([
            'selected_trip_type',
            'selected_trip_template',
            'selected_destination',
            'trip_details',
            'trip_activities',
            'trip_invites'
        ]);
    }

    return view('livewire.pages.trips.create');
})->name('trips.plan');

// Make sure create route also handles session properly
Route::get('/plan/create', function () {
    // Only clear session if starting fresh (not coming from welcome form)
    if (!Session::has('selected_trip_type')) {
        Session::forget([
            'selected_trip_type',
            'selected_trip_template',
            'selected_destination',
            'trip_details',
            'trip_activities',
            'trip_invites'
        ]);
    }

    return view('livewire.pages.trips.create');
})->name('trips.create');

// Pre-planned trip templates - NEW ROUTES FOR DUAL PATH
Route::get('/trip-templates', [TripController::class, 'browseTemplates'])->name('trips.templates');
Route::get('/trip-templates/{template}', [TripController::class, 'showTemplate'])->name('trips.templates.show');

// Trip Planning Steps - Direct access routes
Route::get('/plan/type-selection', function () {
    return view('livewire.trips.trip-type-selection');
})->name('trips.type-selection');

Route::get('/plan/destination', function () {
    return view('livewire.trips.destination-selection');
})->name('trips.destination');

// Pre-planned trip selection route - This handles the direct access from welcome form
Route::get('/plan/pre-planned', function () {
    // Create a wrapper view that only shows the pre-planned selection component
    return view('livewire.pages.trips.pre-planned', [
        'standalone' => true
    ]);
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


// ADMIN ROUTES - Add this to the END of routes/web.php
Route::prefix('admin')->name('admin.')->group(function () {

    // Admin Login
    Route::get('/login', function () {
        return view('admin.auth.login');
    })->name('login')->middleware('guest');

    Route::post('/login', function (Illuminate\Http\Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            if (!auth()->user()->isAdmin()) {
                Auth::logout();
                return back()->withErrors(['email' => 'Access denied. Admin privileges required.']);
            }
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    })->name('login.post');

    // Admin Logout
    Route::post('/logout', function (Illuminate\Http\Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    })->name('logout')->middleware('auth');

    // Redirect /admin to dashboard
    Route::get('/', function () {
        if (auth()->check() && auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('admin.login');
    });

    // Protected Admin Routes
    Route::middleware(['auth'])->group(function () {

        // Dashboard
        Route::get('/dashboard', function () {
            if (!auth()->user()->isAdmin()) abort(403);

            $stats = [
                'total_users' => \App\Models\User::count(),
                'active_users' => \App\Models\User::where('account_status', 'active')->count(),
                'admin_users' => \App\Models\User::where('is_admin', true)->count(),
                'total_trips' => \App\Models\Trip::count(),
                'active_trips' => \App\Models\Trip::where('status', 'active')->count(),
                'flagged_trips' => \App\Models\Trip::where('admin_status', 'flagged')->count(),
                'featured_trips' => \App\Models\Trip::where('is_featured', true)->count(),
                'total_wallets' => \App\Models\SavingsWallet::count(),
                'flagged_wallets' => \App\Models\SavingsWallet::where('admin_flagged', true)->count(),
                'total_transactions' => \App\Models\WalletTransaction::count(),
                'recent_activities' => \App\Models\ActivityLog::with('user')->adminActions()->latest()->take(10)->get() ?? collect([])
            ];

            return view('admin.dashboard.index', compact('stats'));
        })->name('dashboard');

        // Users Management
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', function (Illuminate\Http\Request $request) {
                if (!auth()->user()->isAdmin()) abort(403);

                $query = \App\Models\User::query();

                if ($request->filled('search')) {
                    $search = $request->search;
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('account_number', 'like', "%{$search}%");
                    });
                }

                if ($request->filled('status')) {
                    $query->where('account_status', $request->status);
                }

                if ($request->filled('is_admin')) {
                    $query->where('is_admin', $request->is_admin);
                }

                $users = $query->latest()->paginate(15);
                return view('admin.user-management.index', compact('users'));
            })->name('index');

            Route::get('/{user}', function (\App\Models\User $user) {
                if (!auth()->user()->isAdmin()) abort(403);

                $user->load(['createdTrips', 'savingsWallets', 'walletTransactions']);
                $activities = \App\Models\ActivityLog::forModel(\App\Models\User::class, $user->id)
                    ->with('user')->latest()->take(20)->get();

                return view('admin.user-management.show', compact('user', 'activities'));
            })->name('show');

            Route::patch('/{user}/status', function (Illuminate\Http\Request $request, \App\Models\User $user) {
                if (!auth()->user()->isAdmin()) abort(403);

                $request->validate([
                    'account_status' => 'required|in:active,inactive,suspended'
                ]);

                $oldStatus = $user->account_status;
                $user->update(['account_status' => $request->account_status]);

                \App\Models\ActivityLog::log('user_status_updated', $user, [
                    'old_status' => $oldStatus,
                    'new_status' => $request->account_status
                ]);

                return back()->with('success', 'User status updated successfully.');
            })->name('update-status');
        });

        // Trips Management
        Route::prefix('trips')->name('trips.')->group(function () {
            Route::get('/', function (Illuminate\Http\Request $request) {
                if (!auth()->user()->isAdmin()) abort(403);

                $query = \App\Models\Trip::with(['creator', 'tripTemplate']);

                if ($request->filled('search')) {
                    $search = $request->search;
                    $query->where(function ($q) use ($search) {
                        $q->where('title', 'like', "%{$search}%")
                            ->orWhere('destination', 'like', "%{$search}%")
                            ->orWhereHas('creator', function ($userQuery) use ($search) {
                                $userQuery->where('name', 'like', "%{$search}%")
                                    ->orWhere('email', 'like', "%{$search}%");
                            });
                    });
                }

                if ($request->filled('status')) {
                    $query->where('status', $request->status);
                }

                if ($request->filled('admin_status')) {
                    $query->where('admin_status', $request->admin_status);
                }

                if ($request->filled('is_featured')) {
                    $query->where('is_featured', $request->is_featured);
                }

                $trips = $query->latest()->paginate(15);
                return view('admin.monitoring.trips.index', compact('trips'));
            })->name('index');

            Route::get('/{trip}', function (\App\Models\Trip $trip) {
                if (!auth()->user()->isAdmin()) abort(403);

                $trip->load(['creator', 'members.user', 'itineraries.activities', 'savingsWallet']);
                $activities = \App\Models\ActivityLog::forModel(\App\Models\Trip::class, $trip->id)
                    ->with('user')->latest()->take(20)->get();

                return view('admin.monitoring.trips.show', compact('trip', 'activities'));
            })->name('show');

            Route::patch('/{trip}/admin-status', function (Illuminate\Http\Request $request, \App\Models\Trip $trip) {
                if (!auth()->user()->isAdmin()) abort(403);

                $request->validate([
                    'admin_status' => 'required|in:approved,under_review,flagged,restricted',
                    'admin_notes' => 'nullable|string|max:1000'
                ]);

                $oldStatus = $trip->admin_status;

                $trip->update([
                    'admin_status' => $request->admin_status,
                    'reviewed_by' => auth()->id(),
                    'reviewed_at' => now(),
                    'admin_notes' => $request->admin_notes
                ]);

                \App\Models\ActivityLog::log('trip_admin_status_updated', $trip, [
                    'old_status' => $oldStatus,
                    'new_status' => $request->admin_status,
                    'notes' => $request->admin_notes
                ]);

                return back()->with('success', 'Trip status updated successfully.');
            })->name('update-admin-status');

            Route::patch('/{trip}/toggle-featured', function (\App\Models\Trip $trip) {
                if (!auth()->user()->isAdmin()) abort(403);

                $newStatus = !$trip->is_featured;
                $trip->update(['is_featured' => $newStatus]);

                \App\Models\ActivityLog::log('trip_featured_toggled', $trip, [
                    'is_featured' => $newStatus
                ]);

                $message = $newStatus ? 'Trip marked as featured.' : 'Trip removed from featured.';
                return back()->with('success', $message);
            })->name('toggle-featured');
        });

        // Wallets Management
        Route::prefix('wallets')->name('wallets.')->group(function () {
            Route::get('/', function (Illuminate\Http\Request $request) {
                if (!auth()->user()->isAdmin()) abort(403);

                $query = \App\Models\SavingsWallet::with(['user', 'trip']); // Make sure to load relationships

                if ($request->filled('search')) {
                    $search = $request->search;
                    $query->where(function ($q) use ($search) {
                        $q->whereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        })
                            ->orWhereHas('trip', function ($tripQuery) use ($search) {
                                $tripQuery->where('title', 'like', "%{$search}%");
                            });
                    });
                }

                if ($request->filled('flagged')) {
                    $query->where('admin_flagged', $request->flagged);
                }

                if ($request->filled('currency')) {
                    $query->where('currency', $request->currency);
                }

                $wallets = $query->latest()->paginate(15);

                // Make sure each wallet has a user - filter out orphaned wallets
                $wallets->getCollection()->each(function ($wallet) {
                    if (!$wallet->user) {
                        \Log::warning("Wallet ID {$wallet->id} has no associated user");
                    }
                });

                return view('admin.financial.wallets.index', compact('wallets'));
            })->name('index');

            Route::get('/{wallet}', function (\App\Models\SavingsWallet $wallet) {
                if (!auth()->user()->isAdmin()) abort(403);

                $wallet->load(['user', 'trip', 'transactions.user']);

                // Check if wallet has a user
                if (!$wallet->user) {
                    return redirect()->route('admin.wallets.index')->with('error', 'This wallet has no associated user.');
                }

                $activities = \App\Models\ActivityLog::forModel(\App\Models\SavingsWallet::class, $wallet->id)
                    ->with('user')->latest()->take(20)->get();

                return view('admin.financial.wallets.show', compact('wallet', 'activities'));
            })->name('show');

            Route::patch('/{wallet}/toggle-flag', function (Illuminate\Http\Request $request, \App\Models\SavingsWallet $wallet) {
                if (!auth()->user()->isAdmin()) abort(403);

                if ($wallet->admin_flagged) {
                    $wallet->clearFlag();
                    $message = 'Wallet flag cleared.';
                } else {
                    $reason = $request->input('reason', 'Flagged for review');
                    $wallet->flagForReview($reason);
                    $message = 'Wallet flagged for review.';
                }

                return back()->with('success', $message);
            })->name('toggle-flag');
        });

        // Transactions
        Route::prefix('transactions')->name('transactions.')->group(function () {
            Route::get('/', function (Illuminate\Http\Request $request) {
                if (!auth()->user()->isAdmin()) abort(403);

                $query = \App\Models\WalletTransaction::with(['user', 'wallet.trip']);

                if ($request->filled('search')) {
                    $search = $request->search;
                    $query->where(function ($q) use ($search) {
                        $q->where('transaction_reference', 'like', "%{$search}%")
                            ->orWhereHas('user', function ($userQuery) use ($search) {
                                $userQuery->where('name', 'like', "%{$search}%")
                                    ->orWhere('email', 'like', "%{$search}%");
                            });
                    });
                }

                if ($request->filled('type')) {
                    $query->where('type', $request->type);
                }

                if ($request->filled('status')) {
                    $query->where('status', $request->status);
                }

                $transactions = $query->latest()->paginate(15);
                return view('admin.financial.transactions.index', compact('transactions'));
            })->name('index');
        });

        // Activity Logs
        Route::prefix('activities')->name('activities.')->group(function () {
            Route::get('/', function (Illuminate\Http\Request $request) {
                if (!auth()->user()->isAdmin()) abort(403);

                $query = \App\Models\ActivityLog::with('user');

                if ($request->filled('search')) {
                    $search = $request->search;
                    $query->where(function ($q) use ($search) {
                        $q->where('action', 'like', "%{$search}%")
                            ->orWhere('model_type', 'like', "%{$search}%")
                            ->orWhereHas('user', function ($userQuery) use ($search) {
                                $userQuery->where('name', 'like', "%{$search}%")
                                    ->orWhere('email', 'like', "%{$search}%");
                            });
                    });
                }

                if ($request->filled('action')) {
                    $query->where('action', $request->action);
                }

                if ($request->filled('model_type')) {
                    $query->where('model_type', $request->model_type);
                }

                if ($request->filled('admin_only')) {
                    $query->adminActions();
                }

                $activities = $query->latest()->paginate(20);
                $actions = \App\Models\ActivityLog::distinct()->pluck('action')->filter();
                $modelTypes = \App\Models\ActivityLog::distinct()->pluck('model_type')->filter();

                return view('admin.platform.activities.index', compact('activities', 'actions', 'modelTypes'));
            })->name('index');

            Route::get('/{activity}', function (\App\Models\ActivityLog $activity) {
                if (!auth()->user()->isAdmin()) abort(403);

                $activity->load('user');
                return view('admin.platform.activities.show', compact('activity'));
            })->name('show');
        });
    });
});
