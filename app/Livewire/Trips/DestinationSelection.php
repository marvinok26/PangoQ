<?php

namespace App\Livewire\Trips;

use Livewire\Component;
use App\Models\Destination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class DestinationSelection extends Component
{
    public $destinationQuery = '';
    public $showDestinationDropdown = false;
    public $destinationResults = [];
    public $recentSearches = [];
    public $popularDestinations = [];
   
    public function mount()
    {
        // Load popular destinations from database
        $this->loadPopularDestinations();
        
        // Load recent searches if available
        $this->loadRecentSearches();
    }

    public function render()
    {
        return view('livewire.trips.destination-selection');
    }

    public function searchDestinations()
    {
        if (strlen($this->destinationQuery) >= 2) {
            $this->destinationResults = Destination::where('name', 'like', '%' . $this->destinationQuery . '%')
                ->orWhere('country', 'like', '%' . $this->destinationQuery . '%')
                ->orWhere('city', 'like', '%' . $this->destinationQuery . '%')
                ->take(5)
                ->get()
                ->toArray();

            $this->showDestinationDropdown = true;
        } else {
            $this->resetDestinationResults();
        }
    }

    public function resetDestinationResults()
    {
        $this->destinationResults = [];
        $this->showDestinationDropdown = false;
    }
    
    public function selectDestination($name)
    {
        $destination = Destination::where('name', $name)->first();
        
        if ($destination) {
            // Store destination in session for persistence
            Session::put('selected_destination', $destination->toArray());
            
            // Add to recent searches if authenticated
            $this->saveRecentSearch($destination);
            
            // Dispatch event to parent component
            $this->dispatch('destinationSelected', destination: $destination->toArray());
        }
        
        $this->resetDestinationResults();
    }

    private function loadPopularDestinations()
    {
        // In a real implementation, you might load trending or featured destinations
        // For now, we'll just get a random selection
        $this->popularDestinations = Destination::inRandomOrder()->take(6)->get();
    }

    private function loadRecentSearches()
    {
        // Get recent searches from session if authenticated
        if (Auth::check()) {
            $this->recentSearches = Session::get('recent_searches', []);
        } else {
            $this->recentSearches = []; // Ensure it's always an array
        }
    }

    private function saveRecentSearch($destination)
    {
        if (Auth::check()) {
            $recentSearches = Session::get('recent_searches', []);
            
            // Check if already in recent searches
            $exists = false;
            foreach ($recentSearches as $search) {
                if (isset($search['id']) && $search['id'] == $destination->id) {
                    $exists = true;
                    break;
                }
            }
            
            // Add to recent searches if not already present
            if (!$exists) {
                array_unshift($recentSearches, $destination->toArray());
                // Keep only last 4 searches
                $recentSearches = array_slice($recentSearches, 0, 4);
                Session::put('recent_searches', $recentSearches);
                $this->recentSearches = $recentSearches;
            }
        }
    }
}