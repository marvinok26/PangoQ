<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class SavingsWallet extends Model
{
    use HasFactory, HasTranslations;

    public array $translatable = ['name'];

    protected $fillable = [
        'trip_id',
        'user_id', // You already have this field
        'name',
        'minimum_goal',
        'custom_goal',
        'current_amount',
        'target_date',
        'contribution_frequency',
        'currency',
        // Adding admin oversight fields (optional)
        'admin_flagged',
        'admin_notes'
    ];

    protected $casts = [
        'minimum_goal' => 'decimal:2',
        'custom_goal' => 'decimal:2',
        'current_amount' => 'decimal:2',
        'target_date' => 'date',
        'admin_flagged' => 'boolean'
    ];

    // ============ EXISTING RELATIONSHIPS (PRESERVED) ============
    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getUserAttribute()
    {
        return $this->belongsTo(User::class, 'user_id')->first() ?: null;
    }


    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class, 'wallet_id');
    }

    // ============ EXISTING METHODS (PRESERVED) ============
    /**
     * Get the effective target amount (either custom_goal if set, or minimum_goal).
     */
    public function getTargetAmountAttribute(): float
    {
        return $this->custom_goal ?? $this->minimum_goal;
    }

    public function getProgressPercentageAttribute(): float
    {
        if ($this->target_amount <= 0) {
            return 0;
        }

        $percentage = ($this->current_amount / $this->target_amount) * 100;
        return min(100, round($percentage, 2));
    }

    public function getRemainingAmountAttribute(): float
    {
        return max(0, $this->target_amount - $this->current_amount);
    }

    // ============ NEW ADMIN METHODS (MINIMAL) ============
    /**
     * Flag wallet for admin review
     */
    public function flagForReview($reason = null)
    {
        $this->update([
            'admin_flagged' => true,
            'admin_notes' => $reason
        ]);

        ActivityLog::log('wallet_flagged', $this, ['reason' => $reason]);
    }

    /**
     * Clear admin flag
     */
    public function clearFlag()
    {
        $this->update([
            'admin_flagged' => false,
            'admin_notes' => null
        ]);

        ActivityLog::log('wallet_flag_cleared', $this);
    }

    /**
     * Scope for flagged wallets
     */
    public function scopeFlagged($query)
    {
        return $query->where('admin_flagged', true);
    }

    /**
     * Log wallet activity
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($wallet) {
            ActivityLog::log('wallet_created', $wallet, $wallet->toArray());
        });

        static::updated(function ($wallet) {
            if ($wallet->isDirty() && !$wallet->isDirty(['updated_at'])) {
                ActivityLog::log('wallet_updated', $wallet, $wallet->getChanges(), $wallet->getOriginal());
            }
        });
    }
}
