<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'itinerary_id',
        'title',
        'description',
        'location',
        'start_time',
        'end_time',
        'cost',
        'type',
        'created_by',
    ];
    
    protected $casts = [
        'cost' => 'decimal:2',
    ];
    
    /**
     * Get the itinerary that owns the activity.
     */
    public function itinerary(): BelongsTo
    {
        return $this->belongsTo(Itinerary::class);
    }
    
    /**
     * Get the user who created the activity.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    /**
     * Get the formatted time range.
     */
    public function getFormattedTimeRangeAttribute(): string
    {
        return date('g:i A', strtotime($this->start_time)) . ' - ' . date('g:i A', strtotime($this->end_time));
    }
    
    /**
     * Get the formatted cost.
     */
    public function getFormattedCostAttribute(): string
    {
        if ($this->cost === null) {
            return 'Free';
        }
        
        return '$' . number_format($this->cost, 2);
    }
    
    /**
     * Get the time of day category.
     */
    public function getTimeOfDayAttribute(): string
    {
        $hour = (int) date('H', strtotime($this->start_time));
        
        if ($hour < 12) {
            return 'morning';
        } elseif ($hour < 17) {
            return 'afternoon';
        } else {
            return 'evening';
        }
    }
    
    /**
     * Get the time of day icon.
     */
    public function getTimeOfDayIconAttribute(): string
    {
        return match($this->time_of_day) {
            'morning' => 'coffee',
            'afternoon' => 'umbrella',
            'evening' => 'moon',
            default => 'clock',
        };
    }
    
    /**
     * Get the time of day color.
     */
    public function getTimeOfDayColorAttribute(): string
    {
        return match($this->time_of_day) {
            'morning' => 'yellow',
            'afternoon' => 'orange',
            'evening' => 'indigo',
            default => 'gray',
        };
    }
}