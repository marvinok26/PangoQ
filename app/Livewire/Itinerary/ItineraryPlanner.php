<?php

namespace App\Livewire\Itinerary;

use App\Models\Activity;
use App\Models\Destination;
use App\Models\Itinerary;
use App\Models\Trip;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ItineraryPlanner extends Component
{
    public Trip $trip;
    public array $itineraries = [];
    public ?int $activeItineraryId = null;
    
    // Search for places
    public string $placeSearch = '';
    public array $searchResults = [];
    public bool $showSearchResults = false;
    
    // New activity
    public ?Itinerary $currentItinerary = null;
    public string $newActivityTitle = '';
    public string $newActivityLocation = '';
    public string $newActivityDescription = '';
    public ?string $newActivityStartTime = null;
    public ?string $newActivityEndTime = null;
    public ?float $newActivityCost = null;
    
    protected $rules = [
        'newActivityTitle' => 'required|string|max:255',
        'newActivityLocation' => 'required|string|max:255',
        'newActivityDescription' => 'nullable|string',
        'newActivityStartTime' => 'nullable|date_format:H:i',
        'newActivityEndTime' => 'nullable|date_format:H:i|after_or_equal:newActivityStartTime',
        'newActivityCost' => 'nullable|numeric|min:0',
    ];
    
    public function mount(Trip $trip)
    {
        $this->trip = $trip;
        $this->loadItineraries();
        
        if (count($this->itineraries) > 0) {
            $this->activeItineraryId = $this->itineraries[0]['id'];
            $this->currentItinerary = Itinerary::find($this->activeItineraryId);
        }
    }
    
    public function render()
    {
        return view('livewire.itinerary.itinerary-planner');
    }
    
    public function loadItineraries()
    {
        $this->itineraries = $this->trip->itineraries()
            ->orderBy('day_number')
            ->with('activities')
            ->get()
            ->toArray();
    }
    
    public function setActiveItinerary($itineraryId)
    {
        $this->activeItineraryId = $itineraryId;
        $this->currentItinerary = Itinerary::find($itineraryId);
        $this->resetActivityForm();
    }
    
    public function searchPlaces()
    {
        if (strlen($this->placeSearch) >= 2) {
            // In a real implementation, this would be connected to Google Places API or similar
            // For now, we'll simulate with a simple search
            $this->searchResults = [
                [
                    'name' => 'Example Tourist Attraction',
                    'location' => $this->trip->destination,
                    'description' => 'A popular tourist destination',
                ],
                [
                    'name' => $this->placeSearch . ' Restaurant',
                    'location' => $this->trip->destination,
                    'description' => 'Delicious local cuisine',
                ],
                [
                    'name' => $this->placeSearch . ' Hotel',
                    'location' => $this->trip->destination,
                    'description' => 'Comfortable accommodation',
                ],
            ];
            
            $this->showSearchResults = true;
        } else {
            $this->searchResults = [];
            $this->showSearchResults = false;
        }
    }
    
    public function selectPlace($place)
    {
        $this->newActivityTitle = $place['name'];
        $this->newActivityLocation = $place['location'];
        $this->newActivityDescription = $place['description'];
        $this->showSearchResults = false;
    }
    
    public function addActivity()
    {
        if (!$this->currentItinerary) {
            session()->flash('error', 'Please select a day first');
            return;
        }
        
        $this->validate();
        
        $activity = Activity::create([
            'itinerary_id' => $this->currentItinerary->id,
            'title' => $this->newActivityTitle,
            'location' => $this->newActivityLocation,
            'description' => $this->newActivityDescription,
            'start_time' => $this->newActivityStartTime,
            'end_time' => $this->newActivityEndTime,
            'cost' => $this->newActivityCost,
        ]);
        
        $this->resetActivityForm();
        $this->loadItineraries();
        
        session()->flash('success', 'Activity added successfully!');
    }
    
    public function removeActivity($activityId)
    {
        $activity = Activity::find($activityId);
        
        if ($activity && $activity->itinerary->trip->canAccess(Auth::id())) {
            $activity->delete();
            $this->loadItineraries();
            session()->flash('success', 'Activity removed successfully!');
        }
    }
    
    public function resetActivityForm()
    {
        $this->newActivityTitle = '';
        $this->newActivityLocation = '';
        $this->newActivityDescription = '';
        $this->newActivityStartTime = null;
        $this->newActivityEndTime = null;
        $this->newActivityCost = null;
        $this->placeSearch = '';
        $this->searchResults = [];
        $this->showSearchResults = false;
    }
}