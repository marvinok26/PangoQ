<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'country',
        'city',
        'description',
        'image_url',
    ];

    /**
     * Get the trip templates for the destination.
     */
    public function tripTemplates()
    {
        return $this->hasMany(TripTemplate::class);
    }
}