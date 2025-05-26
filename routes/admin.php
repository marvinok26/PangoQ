<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TripController;
use App\Http\Controllers\Admin\WalletController;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\TransactionController;

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