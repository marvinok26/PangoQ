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
    public ?string $newActivityType = null;
    
    public array $activityTypes = [
        'Cultural', 
        'Beach', 
        'Adventure', 
        'Food', 
        'Shopping', 
        'Nightlife', 
        'Relaxation', 
        'Sightseeing'
    ];
    
    public array $recentCollaborators = [];
    
    protected $rules = [
        'newActivityTitle' => 'required|string|max:255',
        'newActivityLocation' => 'required|string|max:255',
        'newActivityDescription' => 'nullable|string',
        'newActivityStartTime' => 'nullable|date_format:H:i',
        'newActivityEndTime' => 'nullable|date_format:H:i|after_or_equal:newActivityStartTime',
        'newActivityCost' => 'nullable|numeric|min:0',
        'newActivityType' => 'nullable|string',
    ];
    
    public function mount(Trip $trip)
    {
        $this->trip = $trip;
        $this->loadItineraries();
        $this->loadRecentCollaborators();
        
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
            // For now, we'll simulate with a simple search based on the destination
            $destinationName = $this->trip->destination;
            
            $this->searchResults = [
                [
                    'name' => $this->placeSearch . ' Beach',
                    'location' => $destinationName,
                    'description' => 'Beautiful beach with crystal clear water',
                ],
                [
                    'name' => $this->placeSearch . ' Temple',
                    'location' => $destinationName,
                    'description' => 'Historic temple with cultural significance',
                ],
                [
                    'name' => $this->placeSearch . ' Restaurant',
                    'location' => $destinationName,
                    'description' => 'Local cuisine with authentic flavors',
                ],
                [
                    'name' => $this->placeSearch . ' Market',
                    'location' => $destinationName,
                    'description' => 'Vibrant market with local handicrafts',
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
        
        // Set default time based on time of day
        $currentHour = (int) date('H');
        if ($currentHour < 12) {
            $this->newActivityStartTime = '09:00';
            $this->newActivityEndTime = '11:00';
            $this->newActivityType = 'Cultural';
        } elseif ($currentHour < 17) {
            $this->newActivityStartTime = '14:00';
            $this->newActivityEndTime = '16:00';
            $this->newActivityType = 'Beach';
        } else {
            $this->newActivityStartTime = '19:00';
            $this->newActivityEndTime = '21:00';
            $this->newActivityType = 'Nightlife';
        }
        
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
            'type' => $this->newActivityType,
            'created_by' => Auth::id(),
        ]);
        
        $this->resetActivityForm();
        $this->loadItineraries();
        
        session()->flash('success', 'Activity added successfully!');
        $this->dispatch('activityAdded');
    }
    
    public function removeActivity($activityId)
    {
        $activity = Activity::find($activityId);
        
        if ($activity && $activity->itinerary->trip_id === $this->trip->id) {
            $activity->delete();
            $this->loadItineraries();
            session()->flash('success', 'Activity removed successfully!');
            $this->dispatch('activityRemoved');
        }
    }
    
    private function loadRecentCollaborators()
    {
        // In a real implementation, you would load this from a database of recent activities
        // For now, we'll just use placeholder data
        $currentUser = Auth::user();
        $initials = strtoupper(substr($currentUser->name, 0, 1) . (strpos($currentUser->name, ' ') ? substr($currentUser->name, strpos($currentUser->name, ' ') + 1, 1) : ''));
        
        $this->recentCollaborators = [
            [
                'id' => $currentUser->id,
                'name' => 'You',
                'initials' => $initials,
                'action' => 'added Uluwatu Temple 2h ago',
                'color' => 'blue',
            ],
        ];
        
        // Add trip members as collaborators if there are any
        $members = $this->trip->members()
            ->where('users.id', '!=', $currentUser->id)
            ->take(3)
            ->get();
        
        $actions = [
            'suggested Kuta Beach yesterday',
            'liked Sacred Monkey Forest',
            'added Rice Terraces Tour',
        ];
        
        $colors = ['green', 'yellow', 'purple'];
        
        foreach ($members as $index => $member) {
            $memberInitials = strtoupper(substr($member->name, 0, 1) . (strpos($member->name, ' ') ? substr($member->name, strpos($member->name, ' ') + 1, 1) : ''));
            
            $this->recentCollaborators[] = [
                'id' => $member->id,
                'name' => $member->name,
                'initials' => $memberInitials,
                'action' => $actions[$index] ?? 'joined the trip',
                'color' => $colors[$index] ?? 'gray',
            ];
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
        $this->newActivityType = null;
        $this->placeSearch = '';
        $this->searchResults = [];
        $this->showSearchResults = false;
    }
}