<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet_id',
        'user_id',
        'type',
        'amount',
        'status',
        'payment_method',
        'transaction_reference',
        // Adding admin tracking fields (optional for future use)
        'processed_by_admin_id',
        'admin_notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    // ============ EXISTING RELATIONSHIPS (PRESERVED) ============
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(SavingsWallet::class, 'wallet_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ============ EXISTING METHODS (PRESERVED) ============
    public function isDeposit(): bool
    {
        return $this->type === 'deposit';
    }

    public function isWithdrawal(): bool
    {
        return $this->type === 'withdrawal';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    // ============ NEW ADMIN METHODS (MINIMAL) ============
    /**
     * Get admin who processed this transaction
     */
    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by_admin_id');
    }

    /**
     * Log transaction activity
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($transaction) {
            ActivityLog::log('transaction_created', $transaction, $transaction->toArray());
        });

        static::updated(function ($transaction) {
            if ($transaction->isDirty()) {
                ActivityLog::log('transaction_updated', $transaction, $transaction->getChanges(), $transaction->getOriginal());
            }
        });
    }
}