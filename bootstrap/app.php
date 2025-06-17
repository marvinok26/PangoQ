<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register all middleware aliases
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'save-trip' => \App\Http\Middleware\SaveTripAfterLogin::class,
            'handle-trip-session' => \App\Http\Middleware\HandleTripSessionData::class,
            'tripmember' => \App\Http\Middleware\CheckTripMembership::class,
            'set.language' => \App\Http\Middleware\SetLanguage::class,
            'trip.step' => \App\Http\Middleware\TripStepMiddleware::class,
        ]);
        
        // Register web middleware group customizations if needed
        $middleware->web(append: [
            \App\Http\Middleware\CheckRedisConnection::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();