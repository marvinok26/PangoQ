<?php

namespace App\Livewire\Itinerary;

use App\Models\Activity;
use App\Models\Itinerary;
use App\Models\Trip;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class DayView extends Component
{
    public Trip $trip;
    public Itinerary $itinerary;
    public array $activitiesByTimeOfDay = [];
    public array $mapMarkers = [];
    
    protected $listeners = ['activityAdded' => 'refreshActivities', 'activityRemoved' => 'refreshActivities'];
    
    public function mount(Trip $trip, Itinerary $itinerary)
    {
        $this->trip = $trip;
        $this->itinerary = $itinerary;
        
        $this->refreshActivities();
        $this->generateMapMarkers();
    }
    
    public function render()
    {
        return view('livewire.itinerary.day-view');
    }
    
    public function refreshActivities()
    {
        // Reload the itinerary with fresh activities
        $this->itinerary->refresh();
        $this->itinerary->load('activities');
        
        // Group activities by time of day
        $this->activitiesByTimeOfDay = $this->itinerary->activitiesByTimeOfDay();
        
        // Generate map markers
        $this->generateMapMarkers();
    }
    
    public function removeActivity($activityId)
    {
        $activity = Activity::find($activityId);
        
        if ($activity && $activity->itinerary_id === $this->itinerary->id) {
            $activity->delete();
            $this->refreshActivities();
            session()->flash('success', 'Activity removed successfully!');
            $this->dispatch('activityRemoved');
        }
    }
    
    public function toggleLike($activityId)
    {
        // In a real application, you would have a likes table
        // For now, we'll just show a success message
        session()->flash('success', 'Activity liked!');
    }
    
    private function generateMapMarkers()
    {
        $this->mapMarkers = [];
        
        // In a real application, you would get actual coordinates from a geolocation service
        // For now, we'll just use some placeholder data based on the location name
        $placeholderCoordinates = [
            'Uluwatu Temple' => ['-8.8287', '115.0920'],
            'Padang Padang Beach' => ['-8.8149', '115.0919'],
            'Sacred Monkey Forest' => ['-8.5152', '115.2629'],
            'Ubud Market' => ['-8.5068', '115.2624'],
            'Kuta Beach' => ['-8.7180', '115.1686'],
            'Tanah Lot' => ['-8.6215', '115.0867'],
            'Seminyak Beach' => ['-8.6908', '115.1571'],
        ];
        
        $index = 1;
        foreach ($this->itinerary->activities as $activity) {
            // Use the location name to lookup coordinates, or generate some random ones
            $coordinates = $placeholderCoordinates[$activity->location] ?? [
                (string) (mt_rand(-870, -860) / 100),  // latitude
                (string) (mt_rand(11500, 11520) / 100) // longitude
            ];
            
            $this->mapMarkers[] = [
                'id' => $index,
                'activity_id' => $activity->id,
                'title' => $activity->title,
                'lat' => $coordinates[0],
                'lng' => $coordinates[1],
                'type' => $activity->type ?? 'default'
            ];
            
            $index++;
        }
    }
}