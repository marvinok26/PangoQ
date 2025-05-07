<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Itinerary extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'trip_id',
        'title',
        'description',
        'day_number',
        'date',
    ];
    
    protected $casts = [
        'date' => 'date',
    ];
    
    /**
     * Get the trip that owns the itinerary.
     */
    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }
    
    /**
     * Get the activities for the itinerary.
     */
    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class)->orderBy('start_time');
    }
    
    /**
     * Get the number of activities in this itinerary.
     */
    public function getActivityCountAttribute(): int
    {
        return $this->activities()->count();
    }
    
    /**
     * Get the activities for the itinerary grouped by time of day.
     */
    public function activitiesByTimeOfDay(): array
    {
        $activities = $this->activities()->get();
        $groupedActivities = [
            'morning' => [],
            'afternoon' => [],
            'evening' => [],
        ];
        
        foreach ($activities as $activity) {
            $hour = (int) date('H', strtotime($activity->start_time));
            
            if ($hour < 12) {
                $groupedActivities['morning'][] = $activity;
            } elseif ($hour < 17) {
                $groupedActivities['afternoon'][] = $activity;
            } else {
                $groupedActivities['evening'][] = $activity;
            }
        }
        
        return $groupedActivities;
    }
    
    /**
     * Get the date formatted nicely.
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->date->format('M d, Y');
    }
    
    /**
     * Get the short date.
     */
    public function getShortDateAttribute(): string
    {
        return $this->date->format('M d');
    }
}