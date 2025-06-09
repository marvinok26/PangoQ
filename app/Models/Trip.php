<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'creator_id', // FIXED: changed from user_id to creator_id
        'trip_template_id',
        'title',
        'description',
        'destination',
        'destination_country',
        'start_date',
        'end_date',
        'travelers',
        'budget',
        'total_cost',
        'planning_type', // FIXED: changed from trip_type to planning_type
        'status',
        'is_public',
        'currency'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'budget' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'is_public' => 'boolean',
        'travelers' => 'integer'
    ];

    protected $appends = [
        'duration_days',
        'formatted_dates',
        'progress_percentage',
        'is_upcoming',
        'is_current',
        'is_past'
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the user who created the trip
     */
    public function creator(): BelongsTo // FIXED: renamed from user() to creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Alias for backward compatibility
     */
    public function user(): BelongsTo
    {
        return $this->creator();
    }

    /**
     * Get the trip template if this is a pre-planned trip
     */
    public function tripTemplate(): BelongsTo
    {
        return $this->belongsTo(TripTemplate::class);
    }

    /**
     * Get the destination model
     */
    public function destinationModel(): BelongsTo
    {
        return $this->belongsTo(Destination::class, 'destination', 'name');
    }

    /**
     * Get all trip members (including the owner)
     */
    public function members(): HasMany
    {
        return $this->hasMany(TripMember::class);
    }

    /**
     * Get accepted trip members only
     */
    public function acceptedMembers()
    {
        return $this->members()->accepted();
    }

    /**
     * Get pending trip invitations
     */
    public function pendingInvitations()
    {
        return $this->members()->pending();
    }

    /**
     * Get trip itineraries
     */
    public function itineraries(): HasMany
    {
        return $this->hasMany(Itinerary::class)->orderBy('day_number');
    }

    /**
     * Get savings wallet for this trip
     */
    public function savingsWallet(): HasOne
    {
        return $this->hasOne(SavingsWallet::class);
    }

    // ==================== COMPUTED PROPERTIES ====================

    /**
     * Get trip duration in days
     */
    public function getDurationDaysAttribute(): int
    {
        if (!$this->start_date || !$this->end_date) {
            return 0;
        }
        
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    /**
     * Get formatted date range
     */
    public function getFormattedDatesAttribute(): string
    {
        if (!$this->start_date || !$this->end_date) {
            return 'Dates TBD';
        }

        if ($this->start_date->year === $this->end_date->year) {
            if ($this->start_date->month === $this->end_date->month) {
                return $this->start_date->format('M j') . ' - ' . $this->end_date->format('j, Y');
            }
            return $this->start_date->format('M j') . ' - ' . $this->end_date->format('M j, Y');
        }
        
        return $this->start_date->format('M j, Y') . ' - ' . $this->end_date->format('M j, Y');
    }

    /**
     * Get short formatted date range
     */
    public function getShortFormattedDatesAttribute(): string
    {
        if (!$this->start_date || !$this->end_date) {
            return 'TBD';
        }

        return $this->start_date->format('M j') . ' - ' . $this->end_date->format('M j');
    }

    /**
     * Calculate progress percentage based on savings or planning completion
     */
    public function getProgressPercentageAttribute(): int
    {
        // If there's a savings wallet, use savings progress
        if ($this->savingsWallet && $this->savingsWallet->target_amount > 0) {
            return $this->savingsWallet->progress_percentage;
        }

        // Otherwise, calculate based on planning completion
        $totalSteps = 5; // planning steps
        $completedSteps = 0;

        if ($this->destination) $completedSteps++;
        if ($this->start_date && $this->end_date) $completedSteps++;
        if ($this->budget > 0) $completedSteps++;
        if ($this->itineraries()->count() > 0) $completedSteps++;
        if ($this->status !== 'planning') $completedSteps++;

        return min(100, round(($completedSteps / $totalSteps) * 100));
    }

    /**
     * Check if trip is upcoming
     */
    public function getIsUpcomingAttribute(): bool
    {
        return $this->start_date && $this->start_date->isFuture();
    }

    /**
     * Check if trip is currently happening
     */
    public function getIsCurrentAttribute(): bool
    {
        if (!$this->start_date || !$this->end_date) {
            return false;
        }
        
        $now = now()->startOfDay();
        return $now->between($this->start_date, $this->end_date);
    }

    /**
     * Check if trip is in the past
     */
    public function getIsPastAttribute(): bool
    {
        return $this->end_date && $this->end_date->isPast();
    }

    /**
     * Get days until trip starts
     */
    public function getDaysUntilTripAttribute(): ?int
    {
        if (!$this->start_date || $this->start_date->isPast()) {
            return null;
        }
        
        return now()->diffInDays($this->start_date);
    }

    /**
     * Get formatted budget
     */
    public function getFormattedBudgetAttribute(): string
    {
        if (!$this->budget || $this->budget == 0) {
            return 'No budget set';
        }
        
        return '$' . number_format($this->budget, 0);
    }

    /**
     * Get formatted total cost
     */
    public function getFormattedTotalCostAttribute(): string
    {
        if (!$this->total_cost || $this->total_cost == 0) {
            return 'Free';
        }
        
        return '$' . number_format($this->total_cost, 0);
    }

    // ==================== SCOPES ====================

    /**
     * Scope for trips owned by a specific user
     */
    public function scopeOwnedBy($query, $userId)
    {
        return $query->where('creator_id', $userId); // FIXED: changed user_id to creator_id
    }

    /**
     * Scope for upcoming trips
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now());
    }

    /**
     * Scope for current trips
     */
    public function scopeCurrent($query)
    {
        return $query->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
    }

    /**
     * Scope for past trips
     */
    public function scopePast($query)
    {
        return $query->where('end_date', '<', now());
    }

    /**
     * Scope for public trips
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope for trips by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for pre-planned trips
     */
    public function scopePrePlanned($query)
    {
        return $query->where('planning_type', 'pre_planned'); // FIXED: changed trip_type to planning_type
    }

    /**
     * Scope for self-planned trips
     */
    public function scopeSelfPlanned($query)
    {
        return $query->where('planning_type', 'self_planned'); // FIXED: changed trip_type to planning_type
    }

    // ==================== HELPER METHODS ====================

    /**
     * Check if user can edit this trip
     */
    public function canBeEditedBy(User $user): bool
    {
        // Owner can always edit
        if ($this->creator_id === $user->id) { // FIXED: changed user_id to creator_id
            return true;
        }

        // Members with appropriate role can edit (if you implement roles)
        return $this->members()
                   ->where('user_id', $user->id)
                   ->where('role', 'organizer')
                   ->where('invitation_status', 'accepted')
                   ->exists();
    }

    /**
     * Check if user can view this trip
     */
    public function canBeViewedBy(?User $user): bool
    {
        // Public trips can be viewed by anyone
        if ($this->is_public) {
            return true;
        }

        // Not logged in users can't view private trips
        if (!$user) {
            return false;
        }

        // Owner can always view
        if ($this->creator_id === $user->id) { // FIXED: changed user_id to creator_id
            return true;
        }

        // Members can view
        return $this->members()
                   ->where('user_id', $user->id)
                   ->where('invitation_status', 'accepted')
                   ->exists();
    }

    /**
     * Get total number of activities across all itineraries
     */
    public function getTotalActivitiesAttribute(): int
    {
        return $this->itineraries()
                   ->withCount('activities')
                   ->get()
                   ->sum('activities_count');
    }

    /**
     * Get estimated total cost including activities
     */
    public function getEstimatedTotalCostAttribute(): float
    {
        $baseCost = $this->total_cost ?? 0;
        
        $activitiesCost = $this->itineraries()
                              ->with('activities')
                              ->get()
                              ->flatMap->activities
                              ->sum('cost');
        
        return $baseCost + $activitiesCost;
    }

    /**
     * Mark trip as confirmed
     */
    public function markAsConfirmed(): void
    {
        // FIXED: updated status values based on migration (removed 'confirmed')
        $this->update(['status' => 'active']);
    }

    /**
     * Mark trip as completed
     */
    public function markAsCompleted(): void
    {
        $this->update(['status' => 'completed']);
    }

    /**
     * Cancel the trip
     */
    public function cancel(): void
    {
        // FIXED: 'cancelled' status doesn't exist in migration, use 'planning' or add migration
        $this->update(['status' => 'planning']);
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'planning' => 'yellow',
            'active' => 'green', // FIXED: changed from 'confirmed'
            'completed' => 'gray',
            default => 'gray'
        };
    }

    /**
     * Get status display name
     */
    public function getStatusDisplayAttribute(): string
    {
        return match($this->status) {
            'planning' => 'Planning',
            'active' => 'Active', // FIXED: changed from 'confirmed'
            'completed' => 'Completed',
            default => ucfirst($this->status)
        };
    }

    /**
     * Create default itineraries for the trip duration
     */
    public function createDefaultItineraries(): void
    {
        if (!$this->start_date || !$this->end_date) {
            return;
        }

        for ($day = 1; $day <= $this->duration_days; $day++) {
            $date = $this->start_date->copy()->addDays($day - 1);
            
            $this->itineraries()->create([
                'title' => 'Day ' . $day,
                'description' => 'Day ' . $day . ' activities in ' . $this->destination,
                'day_number' => $day,
                'date' => $date,
            ]);
        }
    }

    /**
 * Admin who reviewed the trip
 */
public function reviewer(): BelongsTo
{
    return $this->belongsTo(User::class, 'reviewed_by');
}
}