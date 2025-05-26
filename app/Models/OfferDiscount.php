<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfferDiscount extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'value',
        'minimum_amount',
        'maximum_discount',
        'target_audience',
        'target_user_ids',
        'target_criteria',
        'applicable_destinations',
        'applicable_trip_types',
        'usage_limit_total',
        'usage_limit_per_user',
        'current_usage_count',
        'valid_from',
        'valid_until',
        'is_active',
        'created_by_admin_id',
        'updated_by_admin_id',
        'admin_notes',
        'display_badge',
        'marketing_message',
        'featured_image',
        'is_featured',
        'sort_order',
        'auto_apply',
        'stackable',
        'exclude_with'
    ];

    protected $casts = [
        'target_user_ids' => 'array',
        'target_criteria' => 'array',
        'applicable_destinations' => 'array',
        'applicable_trip_types' => 'array',
        'exclude_with' => 'array',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'auto_apply' => 'boolean',
        'stackable' => 'boolean',
        'value' => 'decimal:2',
        'minimum_amount' => 'decimal:2',
        'maximum_discount' => 'decimal:2',
    ];

    /**
     * Get the admin who created this offer
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_admin_id');
    }

    /**
     * Get the admin who last updated this offer
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_admin_id');
    }

    /**
     * Check if offer is currently valid
     */
    public function isValid(): bool
    {
        $now = now();
        
        return $this->is_active && 
               $now->between($this->valid_from, $this->valid_until) &&
               ($this->usage_limit_total === null || $this->current_usage_count < $this->usage_limit_total);
    }

       /**
     * Check if user is eligible for this offer
     */
    public function isEligibleUser(User $user): bool
    {
        // Check target audience
        switch ($this->target_audience) {
            case 'all':
                return true;
            case 'new_users':
                return $user->created_at->diffInDays(now()) <= 30;
            case 'returning_users':
                return $user->trips()->exists();
            case 'specific_users':
                return in_array($user->id, $this->target_user_ids ?? []);
            case 'premium_users':
                return $user->account_type === 'premium';
            default:
                return false;
        }
    }

    /**
     * Calculate discount amount
     */
    public function calculateDiscount($amount): float
    {
        if ($amount < $this->minimum_amount) {
            return 0;
        }

        switch ($this->type) {
            case 'percentage':
                $discount = ($amount * $this->value) / 100;
                break;
            case 'fixed':
                $discount = $this->value;
                break;
            default:
                return 0;
        }

        // Apply maximum discount limit
        if ($this->maximum_discount && $discount > $this->maximum_discount) {
            $discount = $this->maximum_discount;
        }

        return round($discount, 2);
    }

    /**
     * Apply offer usage
     */
    public function recordUsage(User $user = null)
    {
        $this->increment('current_usage_count');

        // Log admin activity if created by admin
        if ($this->created_by_admin_id) {
            AdminActivityLog::logAction(
                $this->createdBy,
                'offer_used',
                'OfferDiscount',
                $this->id,
                "Offer '{$this->code}' was used" . ($user ? " by {$user->name}" : '')
            );
        }
    }

    /**
     * Check if offer has reached usage limit
     */
    public function hasReachedLimit(): bool
    {
        return $this->usage_limit_total && 
               $this->current_usage_count >= $this->usage_limit_total;
    }

    /**
     * Scope for active offers
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where('valid_from', '<=', now())
                    ->where('valid_until', '>=', now());
    }

    /**
     * Scope for featured offers
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for auto-apply offers
     */
    public function scopeAutoApply($query)
    {
        return $query->where('auto_apply', true);
    }
}