<?php

namespace App\Livewire\Itinerary;

use App\Models\Activity;
use App\Models\Itinerary;
use App\Models\Trip;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ActivityForm extends Component
{
    public Trip $trip;
    public Itinerary $itinerary;
    public ?Activity $activity = null;
    public bool $isEditing = false;
    
    // Form fields
    public string $title = '';
    public string $location = '';
    public ?string $description = null;
    public ?string $start_time = null;
    public ?string $end_time = null;
    public ?float $cost = null;
    public ?string $type = null;
    
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
    
    // For place search
    public string $placeSearch = '';
    public array $searchResults = [];
    public bool $showSearchResults = false;
    
    protected $rules = [
        'title' => 'required|string|max:255',
        'location' => 'required|string|max:255',
        'description' => 'nullable|string',
        'start_time' => 'required|date_format:H:i',
        'end_time' => 'required|date_format:H:i|after_or_equal:start_time',
        'cost' => 'nullable|numeric|min:0',
        'type' => 'nullable|string',
    ];
    
    public function mount(Trip $trip, Itinerary $itinerary, Activity $activity = null)
    {
        $this->trip = $trip;
        $this->itinerary = $itinerary;
        
        if ($activity && $activity->id) {
            $this->activity = $activity;
            $this->isEditing = true;
            
            // Fill the form with the activity data
            $this->title = $activity->title;
            $this->location = $activity->location;
            $this->description = $activity->description;
            $this->start_time = substr($activity->start_time, 0, 5); // Just get HH:MM
            $this->end_time = substr($activity->end_time, 0, 5); // Just get HH:MM
            $this->cost = $activity->cost;
            $this->type = $activity->type;
        } else {
            // Set default values for a new activity
            $this->isEditing = false;
            
            // Default times based on time of day
            $currentHour = (int) date('H');
            
            if ($currentHour < 12) {
                // Morning activity
                $this->start_time = '09:00';
                $this->end_time = '11:00';
                $this->type = 'Cultural';
            } elseif ($currentHour < 17) {
                // Afternoon activity
                $this->start_time = '14:00';
                $this->end_time = '16:00';
                $this->type = 'Beach';
            } else {
                // Evening activity
                $this->start_time = '19:00';
                $this->end_time = '21:00';
                $this->type = 'Nightlife';
            }
        }
    }
    
    public function render()
    {
        return view('livewire.itinerary.activity-form');
    }
    
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
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
        $this->title = $place['name'];
        $this->location = $place['location'];
        $this->description = $place['description'];
        
        $this->showSearchResults = false;
    }
    
    public function save()
    {
        $this->validate();
        
        $data = [
            'title' => $this->title,
            'location' => $this->location,
            'description' => $this->description,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'cost' => $this->cost,
            'type' => $this->type,
        ];
        
        if ($this->isEditing) {
            // Update existing activity
            $this->activity->update($data);
            $message = 'Activity updated successfully!';
        } else {
            // Create new activity
            $data['created_by'] = Auth::id();
            $this->itinerary->activities()->create($data);
            $message = 'Activity added successfully!';
        }
        
        // Emit event to refresh the day view
        $this->dispatch('activityAdded');
        
        session()->flash('success', $message);
        
        // Redirect back to the itinerary view
        return redirect()->route('trips.itineraries.show', [
            'trip' => $this->trip->id,
            'itinerary' => $this->itinerary->id,
        ]);
    }
    
    public function cancel()
    {
        return redirect()->route('trips.itineraries.show', [
            'trip' => $this->trip->id,
            'itinerary' => $this->itinerary->id,
        ]);
    }
}