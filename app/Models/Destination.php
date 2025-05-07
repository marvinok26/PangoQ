<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'country',
        'city',
        'description',
        'image_url',
    ];

    /**
     * Get the trips associated with the destination.
     */
    public function trips()
    {
        return $this->hasMany(Trip::class);
    }
}