<?php

namespace App\Providers;

use App\Services\NotificationService;
use App\Services\TripService;
use Illuminate\Support\ServiceProvider;

class TripServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(TripService::class, function ($app) {
            return new TripService($app->make(NotificationService::class));
        });
        
        $this->app->singleton(NotificationService::class, function ($app) {
            return new NotificationService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}