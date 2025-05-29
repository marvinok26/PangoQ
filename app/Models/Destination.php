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
     * This is a helper method that doesn't conflict with the database column
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

        // If it starts with 'http', it's already a full URL
        if (str_starts_with($value, 'http')) {
            return $value;
        }

        // Otherwise, it's in storage/app/public (uploaded files)
        return asset('storage/' . $value);
    }

    /**
     * Get the raw image URL from database (for admin forms)
     */
    public function getRawImageUrl()
    {
        return $this->attributes['image_url'] ?? null;
    }

    /**
     * Alternative accessor for full image URL (backup method)
     */
    public function getFullImageUrlAttribute()
    {
        return $this->image_url; // This will call the accessor above
    }
}