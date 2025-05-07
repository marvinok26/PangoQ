<?php

namespace App\Livewire\Trips;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ItineraryPlanning extends Component
{
    public $destination;
    public $tripTitle;
    public $startDate;
    public $endDate;
    public $budget;
    public $travelers;
    public $activeDay = 1;
    public $dayActivities = [];
    public $totalDays;
    
    // For new activities
    public $newActivity = [
        'title' => '',
        'description' => '',
        'location' => '',
        'start_time' => '09:00',
        'end_time' => '12:00',
        'cost' => '',
        'category' => '',
        'time_of_day' => 'morning'
    ];
    
    // Invited friends
    public $inviteEmails = [];
    
    // Suggested activities
    public $suggestedActivities = [];
    
    public function mount()
    {
        // Get trip details from session
        $selectedDestination = session('selected_destination');
        $tripDetails = session('trip_details');
        $tripInvites = session('trip_invites');
        
        if ($selectedDestination) {
            $this->destination = $selectedDestination['name'] ?? '';
        }
        
        if ($tripDetails) {
            $this->tripTitle = $tripDetails['title'] ?? '';
            $this->startDate = $tripDetails['start_date'] ?? '';
            $this->endDate = $tripDetails['end_date'] ?? '';
            $this->budget = $tripDetails['budget'] ?? '';
            $this->travelers = $tripDetails['travelers'] ?? 4;
            
            // Calculate total days
            if ($this->startDate && $this->endDate) {
                $start = Carbon::parse($this->startDate);
                $end = Carbon::parse($this->endDate);
                $this->totalDays = $start->diffInDays($end) + 1; // Include start & end days
            }
        }
        
        if ($tripInvites) {
            $this->inviteEmails = $tripInvites;
        }
        
        // Load saved activities if available
        $savedActivities = session('trip_activities');
        if ($savedActivities) {
            $this->dayActivities = $savedActivities;
        } else {
            // Initialize empty activities for each day
            for ($i = 1; $i <= $this->totalDays; $i++) {
                $this->dayActivities[$i] = [];
            }
        }
        
        // Load some suggested activities
        $this->loadSuggestedActivities();
    }

    public function render()
    {
        return view('livewire.trips.itinerary-planning');
    }
    
    public function changeActiveDay($day)
    {
        if ($day >= 1 && $day <= $this->totalDays) {
            $this->activeDay = $day;
        }
    }
    
    public function addActivity()
    {
        $this->validate([
            'newActivity.title' => 'required|string|max:255',
            'newActivity.time_of_day' => 'required|in:morning,afternoon,evening',
            'newActivity.start_time' => 'required',
            'newActivity.end_time' => 'required',
            'newActivity.location' => 'required|string',
            'newActivity.cost' => 'nullable|numeric|min:0',
            'newActivity.category' => 'required|string',
        ]);
        
        // Generate a unique ID for the activity
        $id = uniqid();
        
        // Add to current day's activities
        $this->dayActivities[$this->activeDay][] = array_merge($this->newActivity, ['id' => $id]);
        
        // Save to session
        session(['trip_activities' => $this->dayActivities]);
        
        // Reset form
        $this->resetNewActivity();
        
        // Close modal
        $this->dispatch('closeAddActivityModal');
    }
    
    public function resetNewActivity()
    {
        $this->newActivity = [
            'title' => '',
            'description' => '',
            'location' => '',
            'start_time' => '09:00',
            'end_time' => '12:00',
            'cost' => '',
            'category' => '',
            'time_of_day' => 'morning'
        ];
    }
    
    public function removeActivity($day, $activityId)
    {
        if (isset($this->dayActivities[$day])) {
            $this->dayActivities[$day] = array_filter($this->dayActivities[$day], function($activity) use ($activityId) {
                return $activity['id'] !== $activityId;
            });
            
            // Re-index array
            $this->dayActivities[$day] = array_values($this->dayActivities[$day]);
            
            // Save to session
            session(['trip_activities' => $this->dayActivities]);
        }
    }
    
    public function addSuggestedActivity($index)
    {
        $suggestion = $this->suggestedActivities[$index] ?? null;
        
        if ($suggestion) {
            // Determine time of day based on activity type
            $timeOfDay = 'afternoon'; // Default
            if (stripos($suggestion['title'], 'breakfast') !== false || 
                stripos($suggestion['title'], 'morning') !== false) {
                $timeOfDay = 'morning';
            } elseif (stripos($suggestion['title'], 'dinner') !== false || 
                      stripos($suggestion['title'], 'sunset') !== false || 
                      stripos($suggestion['title'], 'night') !== false || 
                      stripos($suggestion['title'], 'evening') !== false) {
                $timeOfDay = 'evening';
            }
            
            // Set appropriate times based on time of day
            $startTime = $timeOfDay === 'morning' ? '09:00' : ($timeOfDay === 'afternoon' ? '14:00' : '19:00');
            $endTime = $timeOfDay === 'morning' ? '12:00' : ($timeOfDay === 'afternoon' ? '17:00' : '21:00');
            
            // Add to current day's activities
            $this->dayActivities[$this->activeDay][] = [
                'id' => uniqid(),
                'title' => $suggestion['title'],
                'description' => $suggestion['description'],
                'location' => $suggestion['location'],
                'start_time' => $startTime,
                'end_time' => $endTime,
                'cost' => $suggestion['cost'],
                'category' => $suggestion['category'],
                'time_of_day' => $timeOfDay
            ];
            
            // Save to session
            session(['trip_activities' => $this->dayActivities]);
        }
    }
    
    private function loadSuggestedActivities()
    {
        // In a real app, these would come from a database based on the destination
        $this->suggestedActivities = [
            [
                'id' => 'sugg1',
                'title' => 'Local City Tour',
                'location' => $this->destination,
                'description' => 'Explore the highlights of ' . $this->destination . ' with a knowledgeable local guide.',
                'cost' => 45,
                'category' => 'cultural'
            ],
            [
                'id' => 'sugg2',
                'title' => 'Beach Day',
                'location' => 'Popular Beach in ' . $this->destination,
                'description' => 'Relax by the ocean, swim, and enjoy beach activities.',
                'cost' => 10,
                'category' => 'relaxation'
            ],
            [
                'id' => 'sugg3',
                'title' => 'Local Food Tour',
                'location' => 'Market District in ' . $this->destination,
                'description' => 'Taste the local cuisine and discover hidden food gems.',
                'cost' => 55,
                'category' => 'food'
            ],
            [
                'id' => 'sugg4',
                'title' => 'Sunset Cruise',
                'location' => 'Harbor area in ' . $this->destination,
                'description' => 'Enjoy a beautiful sunset from the water with drinks and snacks.',
                'cost' => 75,
                'category' => 'adventure'
            ]
        ];
    }
    
    public function continueToNextStep()
    {
        // Save activities to session
        session(['trip_activities' => $this->dayActivities]);
        
        // Log for debugging
        Log::info("Itinerary planning saved, dispatching specific event to move to invites");
        
        // Use a specific named event
        $this->dispatch('completeItineraryStep');
    }
}