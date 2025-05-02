<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    // Regular auth routes
    Route::view('register', 'auth.register')->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    
    Route::view('login', 'auth.login')->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    
    Route::view('forgot-password', 'auth.forgot-password')->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    
    Route::view('reset-password/{token}', 'auth.reset-password')->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.update');
    
    // Social Authentication Routes
    Route::get('auth/{provider}', [SocialAuthController::class, 'redirectToProvider'])
        ->name('auth.provider')
        ->where('provider', 'google|facebook');
    
    Route::get('auth/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback'])
        ->name('auth.callback')
        ->where('provider', 'google|facebook');
    
    // Named routes for convenience
    Route::get('auth/google', [SocialAuthController::class, 'redirectToProvider'])
        ->name('auth.google')
        ->defaults('provider', 'google');
        
    Route::get('auth/facebook', [SocialAuthController::class, 'redirectToProvider'])
        ->name('auth.facebook')
        ->defaults('provider', 'facebook');
});

Route::middleware('auth')->group(function () {
    Route::view('verify-email', 'auth.verify-email')->name('verification.notice');
    
    Route::get('verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware(['throttle:6,1'])
        ->name('verification.send');
    
    Route::view('confirm-password', 'auth.confirm-password')->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
    
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});