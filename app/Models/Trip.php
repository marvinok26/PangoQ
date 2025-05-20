<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
        'total_cost',
        'selected_optional_activities',
        'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'budget' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'selected_optional_activities' => 'array'
    ];

    /**
     * Create trip itineraries from the template
     */
    public function createItinerariesFromTemplate()
    {
        if (!$this->tripTemplate) {
            return;
        }

        // Get regular activities from the template
        $regularActivities = $this->tripTemplate->regularActivities()->get()->groupBy('day_number');
        
        // Get selected optional activities
        $selectedOptionalActivities = $this->selected_optional_activities ?? [];
        $optionalActivityIds = [];
        
        // Extract IDs from selected optional activities array
        if (is_array($selectedOptionalActivities)) {
            if (isset(reset($selectedOptionalActivities)['id'])) {
                // Format: [activityId => ['id' => activityId, 'title' => '...', 'cost' => '...'], ...]
                $optionalActivityIds = array_keys($selectedOptionalActivities);
            } else {
                // Format: [activityId, activityId, ...]
                $optionalActivityIds = $selectedOptionalActivities;
            }
        }
        
        // Fetch the optional activities if we have selected IDs
        $optionalActivities = [];
        if (!empty($optionalActivityIds)) {
            $optionalActivities = TemplateActivity::whereIn('id', $optionalActivityIds)
                ->where('is_optional', true)
                ->get();
        }

        // Create itineraries for each day of the trip
        for ($day = 1; $day <= $this->tripTemplate->duration_days; $day++) {
            // Calculate the date for this day
            $date = Carbon::parse($this->start_date)->addDays($day - 1);
            
            // Create itinerary
            $itinerary = Itinerary::create([
                'trip_id' => $this->id,
                'title' => "Day $day: " . $this->destination,
                'description' => "Activities for day $day in " . $this->destination,
                'day_number' => $day,
                'date' => $date,
            ]);
            
            // Add regular activities for this day
            if (isset($regularActivities[$day])) {
                foreach ($regularActivities[$day] as $templateActivity) {
                    Activity::createFromTemplate($templateActivity, $itinerary->id);
                }
            }
        }
        
        // Now add optional activities to the appropriate days
        foreach ($optionalActivities as $activity) {
            $dayNumber = $activity->day_number ?? 1;
            
            // Find the itinerary for this day
            $itinerary = $this->itineraries()
                ->where('day_number', $dayNumber)
                ->first();
                
            // If no itinerary found for this day, add to first day
            if (!$itinerary) {
                $itinerary = $this->itineraries()
                    ->orderBy('day_number')
                    ->first();
            }
            
            // Add the optional activity if we have an itinerary
            if ($itinerary) {
                Activity::createFromTemplate($activity, $itinerary->id);
            }
        }
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function members()
    {
        return $this->hasMany(TripMember::class);
    }

    public function users()
{
    return $this->belongsToMany(User::class, 'trip_members', 'trip_id', 'user_id')
                ->withPivot('role', 'invitation_status')
                ->withTimestamps();
}

    public function tripTemplate()
    {
        return $this->belongsTo(TripTemplate::class);
    }

    public function itineraries()
    {
        return $this->hasMany(Itinerary::class);
    }

    public function savingsWallet()
    {
        return $this->hasOne(SavingsWallet::class);
    }
    
    /**
     * Get all activities across all itineraries.
     */
    public function allActivities()
    {
        return Activity::whereIn('itinerary_id', $this->itineraries()->pluck('id'));
    }
    
    /**
     * Get the trip duration in days.
     */
    public function getDurationAttribute()
    {
        if (!$this->start_date || !$this->end_date) {
            return 0;
        }
        
        return $this->start_date->diffInDays($this->end_date) + 1;
    }
    
    /**
     * Get the formatted date range.
     */
    public function getDateRangeAttribute()
    {
        if (!$this->start_date || !$this->end_date) {
            return 'Dates not set';
        }
        
        if ($this->start_date->format('Y-m') === $this->end_date->format('Y-m')) {
            return $this->start_date->format('M d') . ' - ' . $this->end_date->format('d, Y');
        }
        
        if ($this->start_date->format('Y') === $this->end_date->format('Y')) {
            return $this->start_date->format('M d') . ' - ' . $this->end_date->format('M d, Y');
        }
        
        return $this->start_date->format('M d, Y') . ' - ' . $this->end_date->format('M d, Y');
    }
}