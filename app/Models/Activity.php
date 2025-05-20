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
        'category',
        'image_url',
        'is_optional', 
        'is_highlight',
        'created_by',
        'type'
    ];
    
    protected $casts = [
        'cost' => 'decimal:2',
        'is_optional' => 'boolean',
        'is_highlight' => 'boolean'
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
        if (!$this->start_time || !$this->end_time) {
            return 'Flexible';
        }
        
        return date('g:i A', strtotime($this->start_time)) . ' - ' . date('g:i A', strtotime($this->end_time));
    }
    
    /**
     * Get the formatted cost.
     */
    public function getFormattedCostAttribute(): string
    {
        if ($this->cost === null || $this->cost == 0) {
            return 'Free';
        }
        
        return '$' . number_format($this->cost, 2);
    }
    
    /**
     * Get the time of day category.
     */
    public function getTimeOfDayAttribute(): string
    {
        if (!$this->start_time) {
            return 'flexible';
        }
        
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
            'flexible' => 'calendar',
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
            'flexible' => 'blue',
            default => 'gray',
        };
    }
    
    /**
     * Determine if this is an optional activity.
     */
    public function isOptional(): bool
    {
        return $this->is_optional === true;
    }
    
    /**
     * Create an Activity from a TemplateActivity.
     */
    public static function createFromTemplate(TemplateActivity $templateActivity, $itineraryId): self
    {
        return self::create([
            'itinerary_id' => $itineraryId,
            'title' => $templateActivity->title,
            'description' => $templateActivity->description,
            'location' => $templateActivity->location,
            'start_time' => $templateActivity->start_time,
            'end_time' => $templateActivity->end_time,
            'cost' => $templateActivity->cost,
            'type' => $templateActivity->category ?? 'activity',
            'category' => $templateActivity->category ?? 'activity',
            'is_optional' => $templateActivity->is_optional ?? false,
        ]);
    }
}