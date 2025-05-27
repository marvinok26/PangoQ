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

    // ============ ADDITIONAL METHODS FOR ADMIN INTERFACE ============

    /**
     * Set highlights from array (mutator for admin forms)
     */
    // public function setHighlightsAttribute($value): void
    // {
    //     if (is_array($value)) {
    //         // Filter out empty values and encode
    //         $filtered = array_filter($value, function ($item) {
    //             return !empty(trim($item ?? ''));
    //         });
    //         $this->attributes['highlights'] = json_encode(array_values($filtered));
    //     } elseif (is_string($value)) {
    //         // If it's already a JSON string, keep it as is
    //         $this->attributes['highlights'] = $value;
    //     } else {
    //         $this->attributes['highlights'] = null;
    //     }
    // }

    /**
 * Process highlights before saving
 */
public function processHighlights($highlights)
{
    if (is_array($highlights)) {
        $filtered = array_filter($highlights, function($item) {
            return !empty(trim($item ?? ''));
        });
        return json_encode(array_values($filtered));
    }
    return $highlights;
}

    /**
     * Get the total cost including base price and all regular activities
     */
    public function getTotalCostAttribute(): float
    {
        return $this->base_price + $this->getRegularActivitiesCost();
    }

    /**
     * Get the total cost with all optional activities included
     */
    public function getTotalCostWithOptionalAttribute(): float
    {
        return $this->base_price + $this->getTotalActivitiesCost();
    }

    /**
     * Get optional activities cost as attribute
     */
    public function getOptionalActivitiesCostAttribute(): float
    {
        return $this->getOptionalActivitiesCost();
    }

    /**
     * Scope for featured templates
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for templates by difficulty
     */
    public function scopeByDifficulty($query, string $difficulty)
    {
        return $query->where('difficulty_level', $difficulty);
    }

    /**
     * Scope for templates by trip style
     */
    public function scopeByStyle($query, string $style)
    {
        return $query->where('trip_style', $style);
    }

    /**
     * Check if template has activities for all days
     */
    public function hasCompleteItinerary(): bool
    {
        $daysWithActivities = $this->activities()
            ->distinct('day_number')
            ->count();

        return $daysWithActivities >= $this->duration_days;
    }

    /**
     * Get activities grouped by day for admin interface
     */
    public function getActivitiesByDay(): array
    {
        return $this->activities()
            ->orderBy('day_number')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_number')
            ->toArray();
    }

    /**
     * Get highlight activities
     */
    public function highlightActivities()
    {
        return $this->activities()->where('is_highlight', true);
    }

    /**
     * Check if template is complete enough for publishing
     */
    public function isPublishReady(): bool
    {
        return $this->activities()->count() > 0 &&
            $this->hasCompleteItinerary() &&
            !empty($this->title) &&
            !empty($this->description) &&
            $this->base_price > 0;
    }

    /**
     * Get template status for admin display
     */
    public function getStatusAttribute(): string
    {
        if (!$this->isPublishReady()) {
            return 'incomplete';
        }

        if ($this->is_featured) {
            return 'featured';
        }

        return 'active';
    }

    /**
     * Get days that have no activities scheduled
     */
    public function getEmptyDays(): array
    {
        $daysWithActivities = $this->activities()
            ->pluck('day_number')
            ->unique()
            ->toArray();

        $allDays = range(1, $this->duration_days);

        return array_diff($allDays, $daysWithActivities);
    }

    /**
     * Calculate template completeness score (0-100)
     */
    public function getCompletenessScore(): int
    {
        $score = 0;

        // Basic information (40 points)
        if (!empty($this->title)) $score += 10;
        if (!empty($this->description) && strlen($this->description) > 50) $score += 10;
        if ($this->base_price > 0) $score += 10;
        if (!empty($this->highlights_array)) $score += 10;

        // Activities (40 points)
        $activitiesCount = $this->activities()->count();
        if ($activitiesCount > 0) {
            $score += min(20, $activitiesCount * 2); // Up to 20 points for activities

            if ($this->hasCompleteItinerary()) $score += 10; // All days covered
            if ($this->highlightActivities()->count() > 0) $score += 5; // Has highlights
            if ($this->optionalActivities()->count() > 0) $score += 5; // Has optional activities
        }

        // Media and presentation (20 points)
        if ($this->featured_image) $score += 10;

        $activitiesWithImages = $this->activities()->whereNotNull('image_url')->count();
        if ($activitiesCount > 0) {
            $imagePercentage = $activitiesWithImages / $activitiesCount;
            $score += (int) ($imagePercentage * 10); // Up to 10 points for activity images
        }

        return min(100, $score);
    }

    /**
     * Get formatted price range including optional activities - FIXED
     */
    public function getPriceRangeAttribute(): string
    {
        $minPrice = $this->total_cost;
        $maxPrice = $this->total_cost_with_optional;

        if ($minPrice == $maxPrice) {
            return '$' . number_format($minPrice, 0);
        }

        return '$' . number_format($minPrice, 0) . ' - $' . number_format($maxPrice, 0);
    }

/**
 * Get the full URL for the featured image
 */
public function getFeaturedImageUrlAttribute()
{
    if (!$this->featured_image) {
        return null;
    }
    
    // If it starts with 'image', it's in public/images (seeded data)
    if (str_starts_with($this->featured_image, 'image')) {
        return asset('images/' . $this->featured_image);
    }
    
    // Otherwise, it's in storage/app/public
    return asset('storage/' . $this->featured_image);
}
}
