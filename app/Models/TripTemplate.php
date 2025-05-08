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
        'duration_days',
        'base_price',
        'difficulty_level',
        'trip_style',
        'is_featured'
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
    
    // Get all activities for a specific day
    public function getDayActivities($day)
    {
        return $this->activities()->where('day_number', $day)->orderBy('start_time')->get();
    }
    
    // Calculate total cost of all activities
    public function getTotalActivitiesCost()
    {
        return $this->activities()->sum('cost');
    }
    
    // Get a summary of trip details for display
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
            'activities_count' => $this->activities->count(),
        ];
    }
}