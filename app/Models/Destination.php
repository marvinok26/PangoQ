<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'country',
        'city',
        'description',
        'image_url',
        'latitude',
        'longitude',
        'timezone',
        'currency',
        'language',
        'best_time_to_visit',
        'climate',
        'is_featured',
        'popularity_score'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_featured' => 'boolean',
        'popularity_score' => 'integer'
    ];

    protected $appends = [
        'full_image_url',
        'trip_count',
        'formatted_location',
        'price_range',
        'average_rating'
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Get trip templates for this destination
     */
    public function tripTemplates(): HasMany
    {
        return $this->hasMany(TripTemplate::class);
    }

    /**
     * Get featured trip templates for this destination
     */
    public function featuredTripTemplates()
    {
        return $this->tripTemplates()->where('is_featured', true);
    }

    /**
     * Get trips for this destination
     */
    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class, 'destination', 'name');
    }

    /**
     * Get activities related to this destination
     */
    public function activities()
    {
        return $this->hasManyThrough(
            TemplateActivity::class,
            TripTemplate::class,
            'destination_id',
            'trip_template_id'
        );
    }

    // ==================== COMPUTED ATTRIBUTES ====================

    /**
     * Get the full image URL attribute
     * UPDATED: Fixed to handle your seeded images properly
     */
    public function getFullImageUrlAttribute(): ?string
    {
        if (!$this->image_url) {
            return $this->getDefaultImageUrl();
        }

        // If it's already a full URL (starts with http), return as-is
        if (str_starts_with($this->image_url, 'http')) {
            return $this->image_url;
        }

        // UPDATED: Handle seeded images properly
        // Your seeded images are stored as just filenames like "image7.jpg"
        if (preg_match('/^image\d+\.(jpg|jpeg|png|gif|webp)$/i', $this->image_url)) {
            // Check if file exists in public/images directory
            $imagePath = public_path('images/' . $this->image_url);
            if (file_exists($imagePath)) {
                return asset('images/' . $this->image_url);
            }
            // If file doesn't exist, return default
            return $this->getDefaultImageUrl();
        }

        // Handle images that already have path prefix
        if (str_starts_with($this->image_url, 'images/')) {
            $imagePath = public_path($this->image_url);
            if (file_exists($imagePath)) {
                return asset($this->image_url);
            }
            return $this->getDefaultImageUrl();
        }

        // For uploaded images, check if they exist in storage
        if (Storage::disk('public')->exists($this->image_url)) {
            return asset('storage/' . $this->image_url);
        }

        // If nothing matches, assume it's a filename and try images folder
        $imagePath = public_path('images/' . $this->image_url);
        if (file_exists($imagePath)) {
            return asset('images/' . $this->image_url);
        }

        // Fallback to default image
        return $this->getDefaultImageUrl();
    }

    /**
     * UPDATED: Better accessor for image_url that handles the transformation
     */
    public function getImageUrlAttribute($value)
    {
        if (!$value) {
            return $this->getDefaultImageUrl();
        }

        // If it's already a full URL, return as-is
        if (str_starts_with($value, 'http')) {
            return $value;
        }

        // For seeded images (imageX.jpg pattern), add the images/ prefix
        if (preg_match('/^image\d+\.(jpg|jpeg|png|gif|webp)$/i', $value)) {
            $imagePath = public_path('images/' . $value);
            if (file_exists($imagePath)) {
                return asset('images/' . $value);
            }
            return $this->getDefaultImageUrl();
        }

        // If it already has images/ prefix, use asset helper
        if (str_starts_with($value, 'images/')) {
            return asset($value);
        }

        // For storage uploads
        if (Storage::disk('public')->exists($value)) {
            return asset('storage/' . $value);
        }

        // Fallback: try adding images/ prefix
        $imagePath = public_path('images/' . $value);
        if (file_exists($imagePath)) {
            return asset('images/' . $value);
        }

        return $this->getDefaultImageUrl();
    }

    /**
     * Get a default image URL when no image is available
     */
    private function getDefaultImageUrl(): string
    {
        // Check if a default placeholder exists
        $placeholderPath = public_path('images/placeholder.jpg');
        if (file_exists($placeholderPath)) {
            return asset('images/placeholder.jpg');
        }

        // Generate a placeholder image based on destination name
        $destinationSlug = str_replace(' ', '+', $this->name);
        return "https://images.unsplash.com/400x300/?travel,{$destinationSlug}";
    }

    /**
     * Get the raw image filename (for admin forms)
     */
    public function getRawImageAttribute(): ?string
    {
        return $this->attributes['image_url'] ?? null;
    }

    /**
     * Get trip count for this destination
     */
    public function getTripCountAttribute(): int
    {
        return $this->tripTemplates()->count();
    }

    /**
     * Get formatted location string
     */
    public function getFormattedLocationAttribute(): string
    {
        $parts = array_filter([
            $this->name,
            $this->city && $this->city !== $this->name ? $this->city : null,
            $this->country
        ]);

        return implode(', ', $parts);
    }

    /**
     * Get short location format (just city, country)
     */
    public function getShortLocationAttribute(): string
    {
        if ($this->city && $this->city !== $this->name) {
            return $this->city . ', ' . $this->country;
        }
        return $this->name . ', ' . $this->country;
    }

    /**
     * Get price range for this destination's trip templates
     */
    public function getPriceRangeAttribute(): ?string
    {
        $templates = $this->tripTemplates;
        
        if ($templates->isEmpty()) {
            return null;
        }

        $minPrice = $templates->min('base_price');
        $maxPrice = $templates->max('base_price');

        if ($minPrice == $maxPrice) {
            return '$' . number_format($minPrice, 0);
        }

        return '$' . number_format($minPrice, 0) . ' - $' . number_format($maxPrice, 0);
    }

    /**
     * Get average price for trip templates
     */
    public function getAveragePriceAttribute(): ?float
    {
        return $this->tripTemplates()->avg('base_price');
    }

    /**
     * Get minimum price for trip templates
     */
    public function getMinPriceAttribute(): ?float
    {
        return $this->tripTemplates()->min('base_price');
    }

    /**
     * Get maximum price for trip templates
     */
    public function getMaxPriceAttribute(): ?float
    {
        return $this->tripTemplates()->max('base_price');
    }

    /**
     * Get average rating for this destination (placeholder for future rating system)
     */
    public function getAverageRatingAttribute(): ?float
    {
        // This could be implemented when you have a ratings system
        // For now, generate a realistic rating based on popularity
        if ($this->popularity_score) {
            return min(5.0, 3.5 + ($this->popularity_score / 100));
        }
        return null;
    }

    /**
     * Get best time to visit with enhanced logic
     */
    public function getBestTimeToVisitAttribute(): string
    {
        // Return stored value if available
        if ($this->attributes['best_time_to_visit']) {
            return $this->attributes['best_time_to_visit'];
        }

        // Generate recommendations based on location
        return $this->generateBestTimeRecommendation();
    }

    /**
     * Generate best time to visit recommendation
     */
    private function generateBestTimeRecommendation(): string
    {
        $country = strtolower($this->country);
        $name = strtolower($this->name);

        // Kenya
        if (str_contains($country, 'kenya') || str_contains($name, 'kenya')) {
            return 'June to October (Dry season, Great Migration)';
        }

        // Indonesia/Bali
        if (str_contains($country, 'indonesia') || str_contains($name, 'bali')) {
            return 'April to October (Dry season)';
        }

        // Japan
        if (str_contains($country, 'japan') || str_contains($name, 'tokyo') || str_contains($name, 'kyoto')) {
            return 'March to May, September to November (Spring & Autumn)';
        }

        // Greece
        if (str_contains($country, 'greece') || str_contains($name, 'santorini')) {
            return 'April to October (Warm, dry weather)';
        }

        // Italy
        if (str_contains($country, 'italy') || str_contains($name, 'venice')) {
            return 'April to June, September to October (Mild weather)';
        }

        // France
        if (str_contains($country, 'france') || str_contains($name, 'paris')) {
            return 'April to June, September to October (Pleasant weather)';
        }

        // Spain
        if (str_contains($country, 'spain') || str_contains($name, 'barcelona')) {
            return 'May to September (Warm, sunny weather)';
        }

        // Morocco
        if (str_contains($country, 'morocco') || str_contains($name, 'marrakech')) {
            return 'March to May, September to November (Mild temperatures)';
        }

        // South Africa
        if (str_contains($country, 'south africa') || str_contains($name, 'cape town')) {
            return 'November to March (Summer season)';
        }

        // Australia
        if (str_contains($country, 'australia') || str_contains($name, 'sydney')) {
            return 'December to February (Summer season)';
        }

        // Brazil
        if (str_contains($country, 'brazil') || str_contains($name, 'rio')) {
            return 'December to March (Summer season)';
        }

        // USA
        if (str_contains($country, 'united states') || str_contains($name, 'new york')) {
            return 'April to June, September to November (Mild seasons)';
        }

        // Default
        return 'Year-round destination';
    }

    /**
     * Get climate information
     */
    public function getClimateInfoAttribute(): string
    {
        if ($this->climate) {
            return $this->climate;
        }

        // Generate basic climate info based on location
        $country = strtolower($this->country);
        
        if (str_contains($country, 'kenya')) {
            return 'Tropical savanna climate with dry and wet seasons';
        } elseif (str_contains($country, 'indonesia')) {
            return 'Tropical climate with high humidity and monsoon seasons';
        } elseif (str_contains($country, 'japan')) {
            return 'Humid subtropical climate with four distinct seasons';
        } elseif (str_contains($country, 'greece')) {
            return 'Mediterranean climate with hot, dry summers';
        }

        return 'Varied climate depending on season';
    }

    // ==================== SCOPES ====================

    /**
     * Scope for destinations with trip templates
     */
    public function scopeWithTripTemplates(Builder $query): Builder
    {
        return $query->has('tripTemplates');
    }

    /**
     * Scope for featured destinations
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true)
                    ->orWhereHas('tripTemplates', function($q) {
                        $q->where('is_featured', true);
                    });
    }

    /**
     * Scope for popular destinations (have multiple trip templates)
     */
    public function scopePopular(Builder $query, int $minTemplates = 2): Builder
    {
        return $query->has('tripTemplates', '>=', $minTemplates)
                    ->orderBy('popularity_score', 'desc');
    }

    /**
     * Search destinations by name, city, or country
     */
    public function scopeSearch(Builder $query, string $searchTerm): Builder
    {
        $searchTerm = strtolower(trim($searchTerm));
        
        return $query->where(function($q) use ($searchTerm) {
            $q->whereRaw('LOWER(name) LIKE ?', ["%{$searchTerm}%"])
              ->orWhereRaw('LOWER(city) LIKE ?', ["%{$searchTerm}%"])
              ->orWhereRaw('LOWER(country) LIKE ?', ["%{$searchTerm}%"])
              ->orWhereRaw('LOWER(description) LIKE ?', ["%{$searchTerm}%"]);
        });
    }

    /**
     * Scope by country
     */
    public function scopeByCountry(Builder $query, string $country): Builder
    {
        return $query->where('country', $country);
    }

    /**
     * Scope by continent (basic implementation)
     */
    public function scopeByContinent(Builder $query, string $continent): Builder
    {
        $continentCountries = [
            'africa' => ['kenya', 'south africa', 'morocco', 'egypt', 'tanzania'],
            'asia' => ['japan', 'indonesia', 'thailand', 'china', 'india'],
            'europe' => ['france', 'italy', 'spain', 'greece', 'germany'],
            'north america' => ['united states', 'canada', 'mexico'],
            'south america' => ['brazil', 'argentina', 'peru', 'chile'],
            'oceania' => ['australia', 'new zealand', 'fiji']
        ];

        $countries = $continentCountries[strtolower($continent)] ?? [];
        
        return $query->whereIn('country', $countries);
    }

    /**
     * Scope for trending destinations
     */
    public function scopeTrending(Builder $query): Builder
    {
        return $query->withCount(['trips' => function($q) {
                        $q->where('created_at', '>=', now()->subMonths(3));
                    }])
                    ->orderBy('trips_count', 'desc')
                    ->orderBy('popularity_score', 'desc');
    }

    // ==================== HELPER METHODS ====================

    /**
     * Get destinations for API responses
     */
    public function toSearchResult(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'city' => $this->city,
            'country' => $this->country,
            'formatted_location' => $this->formatted_location,
            'short_location' => $this->short_location,
            'image_url' => $this->image_url, // This will use the accessor
            'trip_count' => $this->trip_count,
            'price_range' => $this->price_range,
            'min_price' => $this->min_price,
            'average_rating' => $this->average_rating,
            'best_time_to_visit' => $this->best_time_to_visit,
            'is_featured' => $this->is_featured
        ];
    }

    /**
     * Check if destination is popular (has multiple templates)
     */
    public function isPopular(): bool
    {
        return $this->trip_count >= 2;
    }

    /**
     * Check if destination is trending
     */
    public function isTrending(): bool
    {
        $recentTrips = $this->trips()
                           ->where('created_at', '>=', now()->subMonths(3))
                           ->count();
        
        return $recentTrips >= 5;
    }

    /**
     * Get coordinates as array
     */
    public function getCoordinates(): ?array
    {
        if ($this->latitude && $this->longitude) {
            return [
                'lat' => (float) $this->latitude,
                'lng' => (float) $this->longitude
            ];
        }
        return null;
    }

    /**
     * Calculate distance to another destination
     */
    public function distanceTo(Destination $other): ?float
    {
        if (!$this->getCoordinates() || !$other->getCoordinates()) {
            return null;
        }

        $earthRadius = 6371; // km

        $lat1 = deg2rad($this->latitude);
        $lon1 = deg2rad($this->longitude);
        $lat2 = deg2rad($other->latitude);
        $lon2 = deg2rad($other->longitude);

        $deltaLat = $lat2 - $lat1;
        $deltaLon = $lon2 - $lon1;

        $a = sin($deltaLat/2) * sin($deltaLat/2) +
             cos($lat1) * cos($lat2) *
             sin($deltaLon/2) * sin($deltaLon/2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return $earthRadius * $c;
    }

    /**
     * Get nearby destinations
     */
    public function getNearbyDestinations(int $radiusKm = 500, int $limit = 5)
    {
        if (!$this->getCoordinates()) {
            return collect([]);
        }

        return static::where('id', '!=', $this->id)
                    ->whereNotNull('latitude')
                    ->whereNotNull('longitude')
                    ->get()
                    ->filter(function($destination) use ($radiusKm) {
                        return $this->distanceTo($destination) <= $radiusKm;
                    })
                    ->sortBy(function($destination) {
                        return $this->distanceTo($destination);
                    })
                    ->take($limit);
    }

    /**
     * Increment popularity score
     */
    public function incrementPopularity(int $points = 1): void
    {
        $this->increment('popularity_score', $points);
    }

    /**
     * Get top activities for this destination
     */
    public function getTopActivities(int $limit = 5)
    {
        return $this->activities()
                   ->where('is_highlight', true)
                   ->orderBy('cost', 'asc')
                   ->take($limit)
                   ->get();
    }

    /**
     * Get activity categories available at this destination
     */
    public function getActivityCategories(): array
    {
        return $this->activities()
                   ->select('category')
                   ->distinct()
                   ->whereNotNull('category')
                   ->pluck('category')
                   ->toArray();
    }

    /**
     * Get SEO-friendly slug
     */
    public function getSlugAttribute(): string
    {
        return str_slug($this->name . '-' . $this->country);
    }

    /**
     * Update image URL
     */
    public function updateImage(string $imagePath): void
    {
        $this->update(['image_url' => $imagePath]);
    }

    /**
     * Delete associated image file
     */
    public function deleteImage(): void
    {
        if ($this->image_url && !str_starts_with($this->image_url, 'image')) {
            Storage::disk('public')->delete($this->image_url);
        }
    }

    // ==================== MODEL EVENTS ====================

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($destination) {
            // Clean up associated data when deleting destination
            $destination->deleteImage();
        });

        static::updating(function ($destination) {
            // Clean up old image when updating
            if ($destination->isDirty('image_url')) {
                $oldImage = $destination->getOriginal('image_url');
                if ($oldImage && !str_starts_with($oldImage, 'image')) {
                    Storage::disk('public')->delete($oldImage);
                }
            }
        });
    }
}