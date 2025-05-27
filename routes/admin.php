<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TripController;
use App\Http\Controllers\Admin\WalletController;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\DestinationController;
use App\Http\Controllers\Admin\TripTemplateController;
use App\Http\Controllers\Admin\TripManagement\TemplateActivityController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    
    // Admin Authentication Routes (no auth required)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    });
    
    // Admin Logout (auth required)
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
    
    // Redirect /admin to /admin/dashboard
    Route::get('/', function () {
        if (auth()->check() && auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('admin.login');
    });
    
    // Protected Admin Routes
    Route::middleware(['auth', 'admin'])->group(function () {
        
        // Dashboard - points to admin/dashboard/index.blade.php
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Users Management - points to admin/user-management/
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/{user}', [UserController::class, 'show'])->name('show');
            Route::patch('/{user}/status', [UserController::class, 'updateStatus'])->name('update-status');
            Route::patch('/{user}/admin-status', [UserController::class, 'updateAdminStatus'])->name('update-admin-status');
            Route::patch('/{user}/reset-password', [UserController::class, 'resetPassword'])->name('reset-password');
            Route::patch('/{user}/verify-email', [UserController::class, 'verifyEmail'])->name('verify-email');
            Route::patch('/bulk-action', [UserController::class, 'bulkAction'])->name('bulk-action');
        });

        // Trips Management - points to admin/monitoring/trips/
        Route::prefix('trips')->name('trips.')->group(function () {
            Route::get('/', [TripController::class, 'index'])->name('index');
            Route::get('/{trip}', [TripController::class, 'show'])->name('show');
            Route::patch('/{trip}/admin-status', [TripController::class, 'updateAdminStatus'])->name('update-admin-status');
            Route::patch('/{trip}/toggle-featured', [TripController::class, 'toggleFeatured'])->name('toggle-featured');
        });

        // Destinations Management
        Route::prefix('destinations')->name('destinations.')->group(function () {
            Route::get('/', [DestinationController::class, 'index'])->name('index');
            Route::get('/create', [DestinationController::class, 'create'])->name('create');
            Route::post('/', [DestinationController::class, 'store'])->name('store');
            Route::get('/{destination}', [DestinationController::class, 'show'])->name('show');
            Route::get('/{destination}/edit', [DestinationController::class, 'edit'])->name('edit');
            Route::put('/{destination}', [DestinationController::class, 'update'])->name('update');
            Route::delete('/{destination}', [DestinationController::class, 'destroy'])->name('destroy');
        });

        // Trip Templates Management
        Route::prefix('trip-templates')->name('trip-templates.')->group(function () {
            Route::get('/', [TripTemplateController::class, 'index'])->name('index');
            Route::get('/create', [TripTemplateController::class, 'create'])->name('create');
            Route::post('/', [TripTemplateController::class, 'store'])->name('store');
            Route::get('/{tripTemplate}', [TripTemplateController::class, 'show'])->name('show');
            Route::get('/{tripTemplate}/edit', [TripTemplateController::class, 'edit'])->name('edit');
            Route::put('/{tripTemplate}', [TripTemplateController::class, 'update'])->name('update');
            Route::delete('/{tripTemplate}', [TripTemplateController::class, 'destroy'])->name('destroy');
            Route::post('/{tripTemplate}/duplicate', [TripTemplateController::class, 'duplicate'])->name('duplicate');
            Route::patch('/{tripTemplate}/toggle-featured', [TripTemplateController::class, 'toggleFeatured'])->name('toggle-featured');
            Route::post('/bulk-action', [TripTemplateController::class, 'bulkAction'])->name('bulk-action');
            
            // Template Activities Management - nested under templates
            Route::prefix('{tripTemplate}/activities')->name('activities.')->group(function () {
                Route::get('/create', [TemplateActivityController::class, 'create'])->name('create');
                Route::post('/', [TemplateActivityController::class, 'store'])->name('store');
                Route::get('/{activity}/edit', [TemplateActivityController::class, 'edit'])->name('edit');
                Route::put('/{activity}', [TemplateActivityController::class, 'update'])->name('update');
                Route::delete('/{activity}', [TemplateActivityController::class, 'destroy'])->name('destroy');
                Route::post('/{activity}/duplicate', [TemplateActivityController::class, 'duplicate'])->name('duplicate');
                Route::post('/bulk-action', [TemplateActivityController::class, 'bulkAction'])->name('bulk-action');
            });
        });

        // Wallets Management - points to admin/financial/wallets/
        Route::prefix('wallets')->name('wallets.')->group(function () {
            Route::get('/', [WalletController::class, 'index'])->name('index');
            Route::get('/{wallet}', [WalletController::class, 'show'])->name('show');
            Route::patch('/{wallet}/toggle-flag', [WalletController::class, 'toggleFlag'])->name('toggle-flag');
        });

        // Transactions - points to admin/financial/transactions/
        Route::prefix('transactions')->name('transactions.')->group(function () {
            Route::get('/', [TransactionController::class, 'index'])->name('index');
            Route::get('/analytics', [TransactionController::class, 'analytics'])->name('analytics');
            Route::get('/suspicious', [TransactionController::class, 'flagSuspicious'])->name('suspicious');
            Route::get('/summary', [TransactionController::class, 'summary'])->name('summary');
            Route::get('/{transaction}', [TransactionController::class, 'show'])->name('show');
            Route::patch('/{transaction}/status', [TransactionController::class, 'updateStatus'])->name('update-status');
            Route::patch('/bulk-status', [TransactionController::class, 'bulkUpdateStatus'])->name('bulk-status');
            Route::post('/export', [TransactionController::class, 'export'])->name('export');
        });

        // Activity Logs - points to admin/platform/activities/
        Route::prefix('activities')->name('activities.')->group(function () {
            Route::get('/', [ActivityController::class, 'index'])->name('index');
            Route::get('/security', [ActivityController::class, 'security'])->name('security');
            Route::get('/{activity}', [ActivityController::class, 'show'])->name('show');
            Route::post('/export', [ActivityController::class, 'export'])->name('export');
        });
    });
});