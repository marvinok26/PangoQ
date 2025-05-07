<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| CSRF Debug Routes
|--------------------------------------------------------------------------
|
| These routes help diagnose CSRF token issues.
|
*/

// Only enable in local environment
if (app()->environment('local')) {
    Route::get('/debug-csrf', function (Request $request) {
        return response()->json([
            'csrf_token' => csrf_token(),
            'session_id' => session()->getId(),
            'session_data' => [
                'has_token' => session()->has('_token'),
                'token_matches' => session()->token() === csrf_token(),
            ],
            'cookies' => $request->cookies->all(),
            'session_config' => [
                'driver' => config('session.driver'),
                'lifetime' => config('session.lifetime'),
                'cookie' => config('session.cookie'),
                'path' => config('session.path'),
                'domain' => config('session.domain'),
                'secure' => config('session.secure'),
                'same_site' => config('session.same_site'),
            ],
            'middleware_applied' => array_map(function ($middleware) {
                return get_class($middleware);
            }, app(\Illuminate\Contracts\Http\Kernel::class)->getMiddleware()),
        ]);
    });

    // Test form submission
    Route::view('/csrf-test-form', 'csrf-test-form');
    Route::post('/csrf-test-submit', function () {
        return ['success' => true, 'message' => 'Form submitted successfully!'];
    })->name('csrf.test.submit');
}