<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\SocialAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Fixed Authentication Routes
|--------------------------------------------------------------------------
|
| These routes are specifically designed to fix CSRF issues with auth.
| They include middleware that ensures session availability for tokens.
|
*/

// Define basic middleware for all routes
$middleware = ['web', 'throttle:60,1'];

// Guest middleware group with additional session handling
Route::middleware($middleware)->group(function () {
    Route::middleware('guest')->group(function () {
        // Registration routes with explicit CSRF handling
        Route::get('/register', function () {
            return view('auth.register');
        })->name('register');
        
        Route::post('/register', [RegisteredUserController::class, 'store'])
            ->middleware(['csrf']);
        
        // Login routes with explicit CSRF handling
        Route::get('/login', function () {
            return view('auth.login');
        })->name('login');
        
        Route::post('/login', [AuthenticatedSessionController::class, 'store'])
            ->middleware(['csrf']);
            
        // Social auth routes - excluded from CSRF verification
        Route::get('/auth/{provider}', [SocialAuthController::class, 'redirectToProvider'])
            ->name('auth.provider')
            ->where('provider', 'google|facebook');
        
        Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback'])
            ->name('auth.callback')
            ->where('provider', 'google|facebook')
            ->withoutMiddleware(['csrf']);
            
        // Direct named routes for social auth
        Route::get('auth/google', [SocialAuthController::class, 'redirectToProvider'])
            ->name('auth.google')
            ->defaults('provider', 'google');
            
        Route::get('auth/facebook', [SocialAuthController::class, 'redirectToProvider'])
            ->name('auth.facebook')
            ->defaults('provider', 'facebook');
    });

    // Auth required routes
    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
            ->name('logout')
            ->middleware(['csrf']);
    });
});