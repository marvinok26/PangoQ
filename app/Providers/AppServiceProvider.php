<?php

namespace App\Providers;

use App\Services\NotificationService;
use App\Services\SavingsService;
use App\Services\TripService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register the services used by your application
        $this->app->singleton(NotificationService::class, function ($app) {
            return new NotificationService();
        });
        
        $this->app->singleton(TripService::class, function ($app) {
            return new TripService(
                $app->make(NotificationService::class)
            );
        });
        
        $this->app->singleton(SavingsService::class, function ($app) {
            return new SavingsService(
                $app->make(NotificationService::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}