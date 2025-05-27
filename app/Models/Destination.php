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


    /**
     * Get the full URL for the destination image
     */
    public function getImageUrlAttribute($value)
    {
        if (!$value) {
            return null;
        }

        // If it starts with 'image', it's in public/images (seeded data)
        if (str_starts_with($value, 'image')) {
            return asset('images/' . $value);
        }

        // Otherwise, it's in storage/app/public (uploaded files)
        return asset('storage/' . $value);
    }
}
