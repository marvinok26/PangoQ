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
        'image_url',
        'is_optional',
        'is_highlight'
    ];
    
    protected $casts = [
        'is_optional' => 'boolean',
        'is_highlight' => 'boolean',
        'cost' => 'decimal:2'
    ];
    
    public function tripTemplate()
    {
        return $this->belongsTo(TripTemplate::class);
    }
}