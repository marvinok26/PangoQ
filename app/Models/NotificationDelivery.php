<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationDelivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_notification_id',
        'recipient_id',
        'recipient_type',
        'recipient_email',
        'recipient_name',
        'channel',
        'status',
        'sent_at',
        'delivered_at',
        'read_at',
        'clicked_at',
        'failed_at',
        'error_message',
        'error_code',
        'retry_count',
        'next_retry_at',
        'external_id',
        'channel_metadata',
        'click_count',
        'click_details'
    ];

    protected $casts = [
        'channel_metadata' => 'array',
        'click_details' => 'array',
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'read_at' => 'datetime',
        'clicked_at' => 'datetime',
        'failed_at' => 'datetime',
        'next_retry_at' => 'datetime',
    ];

    /**
     * Get the admin notification
     */
    public function adminNotification(): BelongsTo
    {
        return $this->belongsTo(AdminNotification::class);
    }

    /**
     * Get the recipient
     */
    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    /**
     * Mark as read
     */
    public function markAsRead()
    {
        if (!$this->read_at) {
            $this->update([
                'status' => 'read',
                'read_at' => now()
            ]);

            // Update parent notification stats
            $this->adminNotification->increment('total_reads');
        }
    }

    /**
     * Mark as clicked
     */
    public function markAsClicked()
    {
        $this->update([
            'status' => 'clicked',
            'clicked_at' => now(),
            'click_count' => $this->click_count + 1
        ]);

        // Update parent notification stats
        $this->adminNotification->increment('total_clicks');
    }

    /**
     * Check if delivered successfully
     */
    public function isDelivered(): bool
    {
        return in_array($this->status, ['delivered', 'read', 'clicked']);
    }

    /**
     * Scope for failed deliveries
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope for pending retries
     */
    public function scopePendingRetry($query)
    {
        return $query->where('status', 'failed')
                    ->where('retry_count', '<', 3)
                    ->where('next_retry_at', '<=', now());
    }
}