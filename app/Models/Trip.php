<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'creator_id',
        'trip_template_id',
        'planning_type',
        'title',
        'description',
        'destination',
        'start_date',
        'end_date',
        'budget',
        'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'budget' => 'decimal:2',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function members()
    {
        return $this->hasMany(TripMember::class);
    }

    public function itineraries()
    {
        return $this->hasMany(Itinerary::class);
    }

    public function savingsWallet()
    {
        return $this->hasOne(SavingsWallet::class);
    }
    
    public function tripTemplate()
    {
        return $this->belongsTo(TripTemplate::class);
    }
    
    // Helper method to create itineraries from a trip template
    public function createItinerariesFromTemplate()
    {
        if (!$this->tripTemplate) {
            return false;
        }
        
        // Get the trip template
        $template = $this->tripTemplate;
        
        // Clear any existing itineraries for this trip
        $this->itineraries()->delete();
        
        // Create an itinerary for each day in the template
        for ($day = 1; $day <= $template->duration_days; $day++) {
            $date = clone $this->start_date;
            $date->addDays($day - 1);
            
            // Create the itinerary for this day
            $itinerary = $this->itineraries()->create([
                'title' => "Day $day: " . $this->destination,
                'description' => "Itinerary for day $day in " . $this->destination,
                'day_number' => $day,
                'date' => $date,
            ]);
            
            // Get template activities for this day
            $activities = $template->getDayActivities($day);
            
            // Create activities for this itinerary
            foreach ($activities as $templateActivity) {
                $itinerary->activities()->create($templateActivity->toActivity($itinerary->id));
            }
        }
        
        return true;
    }
}