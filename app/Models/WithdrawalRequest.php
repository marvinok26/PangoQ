<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WithdrawalRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'withdrawal_number',
        'user_id',
        'savings_wallet_id',
        'user_name',
        'user_email',
        'amount',
        'currency',
        'fee_amount',
        'net_amount',
        'payment_method',
        'payment_details',
        'status',
        'reason',
        'admin_notes',
        'rejection_reason',
        'reviewed_by_admin_id',
        'reviewed_at',
        'approved_by_admin_id',
        'approved_at',
        'processed_by_admin_id',
        'processed_at',
        'transaction_reference',
        'processor_response',
        'processor_metadata',
        'requires_verification',
        'verification_documents',
        'verification_completed',
        'verification_completed_at',
        'risk_level',
        'risk_notes'
    ];

    protected $casts = [
        'payment_details' => 'array',
        'processor_metadata' => 'array',
        'verification_documents' => 'array',
        'requires_verification' => 'boolean',
        'verification_completed' => 'boolean',
        'reviewed_at' => 'datetime',
        'approved_at' => 'datetime',
        'processed_at' => 'datetime',
        'verification_completed_at' => 'datetime',
        'amount' => 'decimal:2',
        'fee_amount' => 'decimal:2',
        'net_amount' => 'decimal:2'
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($withdrawal) {
            if (empty($withdrawal->withdrawal_number)) {
                $withdrawal->withdrawal_number = self::generateWithdrawalNumber();
            }
        });
    }

    /**
     * Generate unique withdrawal number
     */
    public static function generateWithdrawalNumber()
    {
        $prefix = 'WDR-' . date('Y') . '-';
        $number = str_pad(self::count() + 1, 6, '0', STR_PAD_LEFT);
        
        return $prefix . $number;
    }

    /**
     * Get the user who requested withdrawal
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the savings wallet
     */
    public function savingsWallet(): BelongsTo
    {
        return $this->belongsTo(SavingsWallet::class);
    }

    /**
     * Get the admin who reviewed
     */
    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by_admin_id');
    }

    /**
     * Get the admin who approved
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_admin_id');
    }

    /**
     * Get the admin who processed
     */
    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by_admin_id');
    }

    /**
     * Approve withdrawal
     */
    public function approve(User $admin, $notes = null)
    {
        $this->update([
            'status' => 'approved',
            'approved_by_admin_id' => $admin->id,
            'approved_at' => now(),
            'admin_notes' => $notes
        ]);

        // Log admin activity
        AdminActivityLog::logAction(
            $admin,
            'approve_withdrawal',
            'WithdrawalRequest',
            $this->id,
            "Approved withdrawal request for {$this->user_name} - Amount: {$this->amount}",
            null,
            ['status' => 'approved', 'approved_by' => $admin->name],
            'high'
        );
    }

    /**
     * Reject withdrawal
     */
    public function reject(User $admin, $reason, $notes = null)
    {
        $this->update([
            'status' => 'rejected',
            'reviewed_by_admin_id' => $admin->id,
            'reviewed_at' => now(),
            'rejection_reason' => $reason,
            'admin_notes' => $notes
        ]);

        // Log admin activity
        AdminActivityLog::logAction(
            $admin,
            'reject_withdrawal',
            'WithdrawalRequest',
            $this->id,
            "Rejected withdrawal request for {$this->user_name} - Reason: {$reason}",
            null,
            ['status' => 'rejected', 'rejected_by' => $admin->name],
            'medium'
        );
    }

    /**
     * Check if pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if approved
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Scope for pending withdrawals
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for high-risk withdrawals
     */
    public function scopeHighRisk($query)
    {
        return $query->where('risk_level', 'high');
    }
}