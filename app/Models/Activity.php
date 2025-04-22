<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class Activity extends Model
{
    use HasFactory, HasTranslations;

    public array $translatable = ['title', 'description', 'location'];

    protected $fillable = [
        'itinerary_id',
        'title',
        'description',
        'location',
        'start_time',
        'end_time',
        'cost',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'cost' => 'decimal:2',
    ];

    public function itinerary(): BelongsTo
    {
        return $this->belongsTo(Itinerary::class);
    }

    public function getDurationAttribute(): string
    {
        if (!$this->start_time || !$this->end_time) {
            return '';
        }

        return $this->start_time->format('H:i') . ' - ' . $this->end_time->format('H:i');
    }
}