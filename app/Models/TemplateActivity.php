<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_template_id',
        'title',
        'description',
        'location',
        'day_number',
        'time_of_day',
        'start_time',
        'end_time',
        'cost',
        'category',
        'image_url'
    ];

    public function tripTemplate()
    {
        return $this->belongsTo(TripTemplate::class);
    }
    
    // Convert template activity to regular activity
    public function toActivity($itinerary_id)
    {
        return [
            'itinerary_id' => $itinerary_id,
            'title' => $this->title,
            'description' => $this->description,
            'location' => $this->location,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'cost' => $this->cost,
            'category' => $this->category
        ];
    }
}