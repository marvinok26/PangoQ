<?php

namespace App\Livewire\Trips;

use Livewire\Component;
use App\Models\Destination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class DestinationSelection extends Component
{
    public $destinationQuery = '';
    public $showDestinationDropdown = false;
    public $destinationResults = [];
    public $recentSearches = [];
    public $popularDestinations = [];
    public $isLoading = false;
   
    public function mount()
    {
        $this->loadPopularDestinations();
        $this->loadRecentSearches();
        
        // Check if there's a pre-selected destination from the welcome page form
        $sessionDestination = Session::get('selected_destination');
        if ($sessionDestination && is_string($sessionDestination)) {
            // If it's a string (from welcome form), find the destination by name
            $destination = Destination::where('name', 'LIKE', "%{$sessionDestination}%")->first();
            if ($destination) {
                $this->selectDestination($destination->name);
            }
        }
    }

    public function render()
    {
        return view('livewire.trips.destination-selection');
    }

    public function updatedDestinationQuery()
    {
        $this->searchDestinations();
    }

    public function searchDestinations()
    {
        if (strlen($this->destinationQuery) >= 2) {
            $this->isLoading = true;
            
            try {
                $this->destinationResults = Destination::search($this->destinationQuery)
                    ->take(5)
                    ->get()
                    ->map(function($destination) {
                        return $destination->toSearchResult();
                    })
                    ->toArray();

                $this->showDestinationDropdown = true;
            } catch (\Exception $e) {
                Log::error('Error searching destinations: ' . $e->getMessage());
                $this->destinationResults = [];
            } finally {
                $this->isLoading = false;
            }
        } else {
            $this->resetDestinationResults();
        }
    }

    public function resetDestinationResults()
    {
        $this->destinationResults = [];
        $this->showDestinationDropdown = false;
        $this->isLoading = false;
    }
    
    public function selectDestination($name)
    {
        $destination = Destination::where('name', $name)->first();
        
        if ($destination) {
            // Store destination in session for persistence
            Session::put('selected_destination', $destination->toArray());
            
            // Add to recent searches if authenticated
            $this->saveRecentSearch($destination);
            
            // Clear search
            $this->destinationQuery = '';
            $this->resetDestinationResults();
            
            // Dispatch event to parent component
            $this->dispatch('destinationSelected', destination: $destination->toArray());
            
            Log::info('Destination selected', [
                'destination_id' => $destination->id,
                'destination_name' => $destination->name
            ]);
        } else {
            Log::warning('Destination not found', ['name' => $name]);
        }
    }

    private function loadPopularDestinations()
    {
        try {
            // Load destinations that have trip templates, prioritizing featured ones
            $this->popularDestinations = Destination::withTripTemplates()
                ->with(['tripTemplates' => function($query) {
                    $query->select('id', 'destination_id', 'title', 'base_price', 'is_featured')
                          ->orderBy('is_featured', 'desc')
                          ->take(3);
                }])
                ->inRandomOrder()
                ->take(6)
                ->get();
        } catch (\Exception $e) {
            Log::error('Error loading popular destinations: ' . $e->getMessage());
            $this->popularDestinations = collect([]);
        }
    }

    private function loadRecentSearches()
    {
        try {
            if (Auth::check()) {
                $recentSearches = Session::get('recent_searches', []);
                // Ensure we have valid destination data
                $this->recentSearches = array_filter($recentSearches, function($search) {
                    return is_array($search) && isset($search['name']) && isset($search['id']);
                });
            } else {
                $this->recentSearches = [];
            }
        } catch (\Exception $e) {
            Log::error('Error loading recent searches: ' . $e->getMessage());
            $this->recentSearches = [];
        }
    }

    private function saveRecentSearch($destination)
    {
        if (Auth::check()) {
            try {
                $recentSearches = Session::get('recent_searches', []);
                
                // Remove if already exists (to avoid duplicates)
                $recentSearches = array_filter($recentSearches, function($search) use ($destination) {
                    return !isset($search['id']) || $search['id'] != $destination->id;
                });
                
                // Add to beginning of array
                array_unshift($recentSearches, $destination->toArray());
                
                // Keep only last 4 searches
                $recentSearches = array_slice($recentSearches, 0, 4);
                
                Session::put('recent_searches', $recentSearches);
                $this->recentSearches = $recentSearches;
            } catch (\Exception $e) {
                Log::error('Error saving recent search: ' . $e->getMessage());
            }
        }
    }

    /**
     * Clear recent searches
     */
    public function clearRecentSearches()
    {
        Session::forget('recent_searches');
        $this->recentSearches = [];
    }

    /**
     * Remove a specific recent search
     */
    public function removeRecentSearch($destinationId)
    {
        $recentSearches = Session::get('recent_searches', []);
        $recentSearches = array_filter($recentSearches, function($search) use ($destinationId) {
            return !isset($search['id']) || $search['id'] != $destinationId;
        });
        
        Session::put('recent_searches', array_values($recentSearches));
        $this->recentSearches = array_values($recentSearches);
    }

    /**
     * Handle clicking outside dropdown to close it
     */
    public function closeDropdown()
    {
        $this->showDestinationDropdown = false;
    }
}