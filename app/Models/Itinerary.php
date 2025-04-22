<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Itinerary extends Model
{
    use HasFactory, HasTranslations;

    public array $translatable = ['title', 'description'];

    protected $fillable = [
        'trip_id',
        'title',
        'description',
        'day_number',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class)->orderBy('start_time');
    }
}