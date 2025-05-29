<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'creator_id',
        'trip_template_id',
        'planning_type',
        'title',
        'description',
        'destination',
        'start_date',
        'end_date',
        'budget',
        'total_cost',
        'selected_optional_activities',
        'status',
        // Admin fields
        'admin_status',
        'reviewed_by',
        'reviewed_at',
        'admin_notes',
        'is_featured',
        'report_count'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'budget' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'selected_optional_activities' => 'array',
        'reviewed_at' => 'datetime',
        'is_featured' => 'boolean',
        'report_count' => 'integer'
    ];

    // ============ MODEL EVENTS - BUDGET SYNC ============

    protected static function boot()
    {
        parent::boot();

        // When trip is updated, sync budget with savings wallet
        static::updated(function ($trip) {
            // Check if budget was changed
            if ($trip->isDirty('budget') && $trip->budget) {
                $trip->syncBudgetWithSavingsWallet();
            }
        });
    }

    /**
     * Sync trip budget with savings wallet minimum_goal
     * This ensures the savings wallet reflects the updated budget
     */
    public function syncBudgetWithSavingsWallet()
    {
        if ($this->savingsWallet && $this->budget) {
            // Only update if the new budget is higher than current minimum_goal
            // This prevents decreasing the savings goal if people have already saved more
            if ($this->budget > $this->savingsWallet->minimum_goal) {
                $this->savingsWallet->update([
                    'minimum_goal' => $this->budget
                ]);

                // Log this change for transparency
                Log::info("Trip budget updated - Savings wallet minimum goal synced", [
                    'trip_id' => $this->id,
                    'old_minimum_goal' => $this->savingsWallet->getOriginal('minimum_goal'),
                    'new_minimum_goal' => $this->budget,
                    'current_amount' => $this->savingsWallet->current_amount
                ]);
            }
        }
    }


    /**
     * Create trip itineraries from the template
     */
    public function createItinerariesFromTemplate()
    {
        if (!$this->tripTemplate) {
            return;
        }

        // Get regular activities from the template
        $regularActivities = $this->tripTemplate->regularActivities()->get()->groupBy('day_number');

        // Get selected optional activities
        $selectedOptionalActivities = $this->selected_optional_activities ?? [];
        $optionalActivityIds = [];

        // Extract IDs from selected optional activities array
        if (is_array($selectedOptionalActivities)) {
            if (isset(reset($selectedOptionalActivities)['id'])) {
                // Format: [activityId => ['id' => activityId, 'title' => '...', 'cost' => '...'], ...]
                $optionalActivityIds = array_keys($selectedOptionalActivities);
            } else {
                // Format: [activityId, activityId, ...]
                $optionalActivityIds = $selectedOptionalActivities;
            }
        }

        // Fetch the optional activities if we have selected IDs
        $optionalActivities = [];
        if (!empty($optionalActivityIds)) {
            $optionalActivities = TemplateActivity::whereIn('id', $optionalActivityIds)
                ->where('is_optional', true)
                ->get();
        }

        // Create itineraries for each day of the trip
        for ($day = 1; $day <= $this->tripTemplate->duration_days; $day++) {
            // Calculate the date for this day
            $date = Carbon::parse($this->start_date)->addDays($day - 1);

            // Create itinerary
            $itinerary = Itinerary::create([
                'trip_id' => $this->id,
                'title' => "Day $day: " . $this->destination,
                'description' => "Activities for day $day in " . $this->destination,
                'day_number' => $day,
                'date' => $date,
            ]);

            // Add regular activities for this day
            if (isset($regularActivities[$day])) {
                foreach ($regularActivities[$day] as $templateActivity) {
                    Activity::createFromTemplate($templateActivity, $itinerary->id);
                }
            }
        }

        // Now add optional activities to the appropriate days
        foreach ($optionalActivities as $activity) {
            $dayNumber = $activity->day_number ?? 1;

            // Find the itinerary for this day
            $itinerary = $this->itineraries()
                ->where('day_number', $dayNumber)
                ->first();

            // If no itinerary found for this day, add to first day
            if (!$itinerary) {
                $itinerary = $this->itineraries()
                    ->orderBy('day_number')
                    ->first();
            }

            // Add the optional activity if we have an itinerary
            if ($itinerary) {
                Activity::createFromTemplate($activity, $itinerary->id);
            }
        }
    }



    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function members()
    {
        return $this->hasMany(TripMember::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'trip_members', 'trip_id', 'user_id')
            ->withPivot('role', 'invitation_status')
            ->withTimestamps();
    }

    public function tripTemplate()
    {
        return $this->belongsTo(TripTemplate::class);
    }



    public function itineraries()
    {
        return $this->hasMany(Itinerary::class);
    }

    public function savingsWallet()
    {
        return $this->hasOne(SavingsWallet::class);
    }

    /**
     * Get all activities across all itineraries.
     */
    public function allActivities()
    {
        return Activity::whereIn('itinerary_id', $this->itineraries()->pluck('id'));
    }

    /**
     * Get the trip duration in days.
     */
    public function getDurationAttribute()
    {
        if (!$this->start_date || !$this->end_date) {
            return 0;
        }

        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    /**
     * Get the formatted date range.
     */
    public function getDateRangeAttribute()
    {
        if (!$this->start_date || !$this->end_date) {
            return 'Dates not set';
        }

        if ($this->start_date->format('Y-m') === $this->end_date->format('Y-m')) {
            return $this->start_date->format('M d') . ' - ' . $this->end_date->format('d, Y');
        }

        if ($this->start_date->format('Y') === $this->end_date->format('Y')) {
            return $this->start_date->format('M d') . ' - ' . $this->end_date->format('M d, Y');
        }

        return $this->start_date->format('M d, Y') . ' - ' . $this->end_date->format('M d, Y');
    }

    // ============ ADMIN-RELATED METHODS (NEW) ============

    /**
     * Admin who reviewed this trip
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Refund requests for this trip
     */
    public function refundRequests()
    {
        return $this->hasMany(RefundRequest::class);
    }

    /**
     * Check if trip is approved by admin
     */
    public function isApproved(): bool
    {
        return $this->admin_status === 'approved';
    }

    /**
     * Check if trip is under review
     */
    public function isUnderReview(): bool
    {
        return $this->admin_status === 'under_review';
    }

    /**
     * Check if trip is flagged
     */
    public function isFlagged(): bool
    {
        return $this->admin_status === 'flagged';
    }

    /**
     * Check if trip is featured
     */
    public function isFeatured(): bool
    {
        return $this->is_featured === true;
    }

    /**
     * Mark trip as featured
     */
    public function markAsFeatured(): void
    {
        $this->update(['is_featured' => true]);
    }

    /**
     * Remove featured status
     */
    public function removeFeatured(): void
    {
        $this->update(['is_featured' => false]);
    }

    /**
     * Increment report count
     */
    public function incrementReports(): void
    {
        $this->increment('report_count');
    }

    /**
     * Scope for featured trips
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for approved trips
     */
    public function scopeApproved($query)
    {
        return $query->where('admin_status', 'approved');
    }

    /**
     * Scope for flagged trips
     */
    public function scopeFlagged($query)
    {
        return $query->where('admin_status', 'flagged');
    }

    /**
     * Scope for trips under review
     */
    public function scopeUnderReview($query)
    {
        return $query->where('admin_status', 'under_review');
    }


    /**
     * Get the trip template (alias for tripTemplate for consistency)
     */
    public function template()
    {
        return $this->belongsTo(TripTemplate::class, 'trip_template_id');
    }

    /**
     * Get destination information from the trip template
     * Since destination is stored as string in trips table, this provides fallback
     */
    public function destinationInfo()
    {
        return $this->tripTemplate ? $this->tripTemplate->destination : null;
    }

    // Replace the getImageUrlAttribute method in your Trip model with this FINAL version

    /**
     * Get the image URL for this trip
     * Priority: Trip image -> Template featured_image -> Destination image_url
     */
    public function getImageUrlAttribute()
    {
        // Check if trip has its own image field (add this column if needed)
        if (isset($this->attributes['image_url']) && $this->attributes['image_url']) {
            $imagePath = $this->attributes['image_url'];
            return $this->formatImageUrl($imagePath);
        }

        // Check template featured_image
        if ($this->tripTemplate && $this->tripTemplate->featured_image) {
            $imagePath = $this->tripTemplate->featured_image;
            return $this->formatImageUrl($imagePath);
        }

        // Check destination image_url via template
        if ($this->tripTemplate && $this->tripTemplate->destination && $this->tripTemplate->destination->image_url) {
            $imagePath = $this->tripTemplate->destination->image_url;
            // Destination image_url is already a full URL, so return as-is
            return $imagePath;
        }

        return null;
    }

    /**
     * Format image path to full URL
     */
    private function formatImageUrl($imagePath)
    {
        if (!$imagePath) {
            return null;
        }

        // If it's already a full URL, return as is
        if (str_starts_with($imagePath, 'http')) {
            return $imagePath;
        }

        // For template images like "image4.jpeg", they're stored in public/images/
        // Based on your server logs showing /images/image11.jpg requests
        return url('images/' . $imagePath);
    }

    /**
     * Get the destination name with fallback to template destination
     */
    public function getDestinationNameAttribute()
    {
        // Use the destination string from trips table first
        if ($this->destination) {
            return $this->destination;
        }

        // Fallback to template destination
        if ($this->tripTemplate && $this->tripTemplate->destination) {
            return $this->tripTemplate->destination->name;
        }

        return 'Unknown Destination';
    }
}
