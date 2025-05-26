<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RefundRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'refund_number',
        'user_id',
        'user_name',
        'user_email',
        'trip_id',
        'wallet_transaction_id',
        'original_transaction_reference',
        'original_amount',
        'refund_amount',
        'refund_percentage',
        'processing_fee',
        'net_refund_amount',
        'currency',
        'reason_category',
        'reason_description',
        'customer_message',
        'status',
        'priority',
        'admin_notes',
        'rejection_reason',
        'approval_notes',
        'processing_notes',
        'reviewed_by_admin_id',
        'reviewed_at',
        'approved_by_admin_id',
        'approved_at',
        'processed_by_admin_id',
        'processed_at',
        'completed_at',
        'refund_method',
        'refund_details',
        'external_refund_id',
        'processor_response',
        'processor_metadata',
        'supporting_documents',
        'refund_policy_applied',
        'auto_approved',
        'escalation_level',
        'escalation_reason',
        'resolution_time_hours',
        'customer_satisfaction',
        'follow_up_required',
        'follow_up_notes'
    ];

    protected $casts = [
        'refund_details' => 'array',
        'processor_metadata' => 'array',
        'supporting_documents' => 'array',
        'refund_policy_applied' => 'array',
        'auto_approved' => 'boolean',
        'follow_up_required' => 'boolean',
        'reviewed_at' => 'datetime',
        'approved_at' => 'datetime',
        'processed_at' => 'datetime',
        'completed_at' => 'datetime',
        'original_amount' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'processing_fee' => 'decimal:2',
        'net_refund_amount' => 'decimal:2',
        'refund_percentage' => 'decimal:2'
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($refund) {
            if (empty($refund->refund_number)) {
                $refund->refund_number = self::generateRefundNumber();
            }
        });
    }

    /**
     * Generate unique refund number
     */
    public static function generateRefundNumber()
    {
        $prefix = 'RFD-' . date('Y') . '-';
        $number = str_pad(self::count() + 1, 6, '0', STR_PAD_LEFT);
        
        return $prefix . $number;
    }

    /**
     * Get the user who requested refund
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the related trip
     */
    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    /**
     * Get the related wallet transaction
     */
    public function walletTransaction(): BelongsTo
    {
        return $this->belongsTo(WalletTransaction::class);
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
     * Approve refund request
     */
    public function approve(User $admin, $notes = null, $customAmount = null)
    {
        $refundAmount = $customAmount ?? $this->refund_amount;
        
        $this->update([
            'status' => 'approved',
            'approved_by_admin_id' => $admin->id,
            'approved_at' => now(),
            'approval_notes' => $notes,
            'refund_amount' => $refundAmount,
            'net_refund_amount' => $refundAmount - $this->processing_fee
        ]);

        // Log admin activity
        AdminActivityLog::logAction(
            $admin,
            'approve_refund',
            'RefundRequest',
            $this->id,
            "Approved refund request for {$this->user_name} - Amount: {$refundAmount}",
            null,
            [
                'status' => 'approved', 
                'approved_by' => $admin->name,
                'refund_amount' => $refundAmount
            ],
            'high'
        );
    }

    /**
     * Reject refund request
     */
    public function reject(User $admin, $reason, $notes = null)
    {
        $this->update([
            'status' => 'rejected',
            'reviewed_by_admin_id' => $admin->id,
            'reviewed_at' => now(),
            'rejection_reason' => $reason,
            'admin_notes' => $notes,
            'resolution_time_hours' => $this->created_at->diffInHours(now())
        ]);

        // Log admin activity
        AdminActivityLog::logAction(
            $admin,
            'reject_refund',
            'RefundRequest',
            $this->id,
            "Rejected refund request for {$this->user_name} - Reason: {$reason}",
            null,
            ['status' => 'rejected', 'rejected_by' => $admin->name],
            'medium'
        );
    }

    /**
     * Mark as processed
     */
    public function markAsProcessed(User $admin, $externalId = null, $processorResponse = null)
    {
        $this->update([
            'status' => 'completed',
            'processed_by_admin_id' => $admin->id,
            'processed_at' => now(),
            'completed_at' => now(),
            'external_refund_id' => $externalId,
            'processor_response' => $processorResponse,
            'resolution_time_hours' => $this->created_at->diffInHours(now())
        ]);

        // Log admin activity
        AdminActivityLog::logAction(
            $admin,
            'process_refund',
            'RefundRequest',
            $this->id,
            "Processed refund for {$this->user_name} - Amount: {$this->net_refund_amount}",
            null,
            ['status' => 'completed', 'processed_by' => $admin->name],
            'high'
        );
    }

    /**
     * Calculate refund amount based on policy
     */
    public function calculateRefundAmount($policy = null)
    {
        // This would implement your refund policy logic
        // For now, return the full amount minus processing fee
        $baseAmount = $this->original_amount;
        $processingFee = $this->processing_fee ?? 0;
        
        // Apply refund policy based on reason, time elapsed, etc.
        switch ($this->reason_category) {
            case 'trip_cancelled':
                $refundPercentage = 100;
                break;
            case 'service_issue':
                $refundPercentage = 90;
                break;
            case 'customer_request':
                // Time-based refund policy
                $hoursElapsed = $this->created_at->diffInHours($this->trip?->start_date ?? now());
                if ($hoursElapsed > 72) {
                    $refundPercentage = 50;
                } else if ($hoursElapsed > 24) {
                    $refundPercentage = 75;
                } else {
                    $refundPercentage = 90;
                }
                break;
            default:
                $refundPercentage = 80;
        }

        $refundAmount = ($baseAmount * $refundPercentage) / 100;
        $netRefund = $refundAmount - $processingFee;

        $this->update([
            'refund_percentage' => $refundPercentage,
            'refund_amount' => $refundAmount,
            'net_refund_amount' => max(0, $netRefund)
        ]);

        return $netRefund;
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
        return in_array($this->status, ['approved', 'processing', 'completed']);
    }

    /**
     * Check if completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if high priority
     */
    public function isHighPriority(): bool
    {
        return in_array($this->priority, ['high', 'urgent']) || 
               $this->original_amount > 1000 ||
               $this->escalation_level > 1;
    }

    /**
     * Scope for pending refunds
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved refunds
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for high priority refunds
     */
    public function scopeHighPriority($query)
    {
        return $query->where(function($q) {
            $q->whereIn('priority', ['high', 'urgent'])
              ->orWhere('original_amount', '>', 1000)
              ->orWhere('escalation_level', '>', 1);
        });
    }

    /**
     * Scope for overdue refunds
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'pending')
                    ->where('created_at', '<', now()->subHours(48));
    }
}