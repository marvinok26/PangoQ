<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;
use App\Livewire\Trips\CreateTrip;
use App\Livewire\Trips\DestinationSelection;
use App\Livewire\Trips\TripDetails;
use App\Livewire\Trips\InviteFriends;
use App\Livewire\Trips\ItineraryPlanning;
use App\Livewire\Trips\Review;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Add a blade directive to include CSRF meta tag
        Blade::directive('csrfMeta', function () {
            return '<?php echo \'<meta name="csrf-token" content="\' . csrf_token() . \'">\'; ?>';
        });
        
        // Register Livewire components
        if (class_exists(Livewire::class)) {
            Livewire::component('trips.create-trip', CreateTrip::class);
            Livewire::component('trips.destination-selection', DestinationSelection::class);
            Livewire::component('trips.trip-details', TripDetails::class);
            Livewire::component('trips.invite-friends', InviteFriends::class);
            Livewire::component('trips.itinerary-planning', ItineraryPlanning::class);
            Livewire::component('trips.review', Review::class);
        }
    }
}