<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'destination_id',
        'title',
        'description',
        'highlights',
        'duration_days',
        'base_price',
        'difficulty_level',
        'trip_style',
        'is_featured',
        'featured_image'
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'highlights' => 'array'
    ];

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function activities()
    {
        return $this->hasMany(TemplateActivity::class);
    }

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }
    
    /**
     * Get all activities for a specific day
     */
    public function getDayActivities($day)
    {
        return $this->activities()->where('day_number', $day)->orderBy('start_time')->get();
    }
    
    /**
     * Get regular (non-optional) activities
     */
    public function regularActivities()
    {
        return $this->activities()->where('is_optional', false);
    }
    
    /**
     * Get optional activities
     */
    public function optionalActivities()
    {
        return $this->activities()->where('is_optional', true);
    }
    
    /**
     * Calculate total cost of all activities
     */
    public function getTotalActivitiesCost()
    {
        return $this->activities()->sum('cost');
    }
    
    /**
     * Calculate total cost of all regular activities
     */
    public function getRegularActivitiesCost()
    {
        return $this->regularActivities()->sum('cost');
    }
    
    /**
     * Calculate total cost of all optional activities
     */
    public function getOptionalActivitiesCost()
    {
        return $this->optionalActivities()->sum('cost');
    }
    
    /**
     * Get the highlights as an array
     */
    public function getHighlightsArrayAttribute()
    {
        if (!$this->highlights) {
            return [];
        }
        
        if (is_array($this->highlights)) {
            return $this->highlights;
        }
        
        return json_decode($this->highlights, true) ?? [];
    }
    
    /**
     * Get a summary of trip details for display
     */
    public function getSummary()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'destination' => $this->destination->name,
            'duration' => $this->duration_days,
            'price' => $this->base_price,
            'difficulty' => $this->difficulty_level,
            'style' => $this->trip_style,
            'activities_count' => $this->regularActivities()->count(),
            'optional_activities_count' => $this->optionalActivities()->count(),
        ];
    }
}