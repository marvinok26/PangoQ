<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TripController;
use App\Http\Controllers\Admin\WalletController;
use App\Http\Controllers\Admin\ActivityController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    
    // Admin Authentication Routes (no auth required)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login']);
    });
    
    // Admin Logout (auth required)
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
    
    // Redirect /admin to /admin/dashboard
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });
    
    // Protected Admin Routes
    Route::middleware(['auth', 'admin'])->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Users Management
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/{user}', [UserController::class, 'show'])->name('show');
            Route::patch('/{user}/status', [UserController::class, 'updateStatus'])->name('update-status');
        });

        // Trips Management
        Route::prefix('trips')->name('trips.')->group(function () {
            Route::get('/', [TripController::class, 'index'])->name('index');
            Route::get('/{trip}', [TripController::class, 'show'])->name('show');
            Route::patch('/{trip}/admin-status', [TripController::class, 'updateAdminStatus'])->name('update-admin-status');
            Route::patch('/{trip}/toggle-featured', [TripController::class, 'toggleFeatured'])->name('toggle-featured');
        });

        // Wallets Management
        Route::prefix('wallets')->name('wallets.')->group(function () {
            Route::get('/', [WalletController::class, 'index'])->name('index');
            Route::get('/{wallet}', [WalletController::class, 'show'])->name('show');
            Route::patch('/{wallet}/toggle-flag', [WalletController::class, 'toggleFlag'])->name('toggle-flag');
        });

        // Transactions
        Route::prefix('transactions')->name('transactions.')->group(function () {
            Route::get('/', [WalletController::class, 'transactions'])->name('index');
        });

        // Activity Logs
        Route::prefix('activities')->name('activities.')->group(function () {
            Route::get('/', [ActivityController::class, 'index'])->name('index');
            Route::get('/{activity}', [ActivityController::class, 'show'])->name('show');
        });
    });
});