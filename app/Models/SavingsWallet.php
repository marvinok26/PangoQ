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
        'name',
        'minimum_goal',
        'custom_goal',
        'current_amount',
        'target_date',
        'contribution_frequency',
        'currency',
    ];

    protected $casts = [
        'minimum_goal' => 'decimal:2',
        'custom_goal' => 'decimal:2',
        'current_amount' => 'decimal:2',
        'target_date' => 'date',
    ];

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class, 'wallet_id');
    }

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
}