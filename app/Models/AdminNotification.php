<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdminNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'type',
        'category',
        'created_by_admin_id',
        'sender_name',
        'audience',
        'target_admin_ids',
        'target_admin_roles',
        'target_user_ids',
        'target_user_criteria',
        'status',
        'scheduled_at',
        'sent_at',
        'total_recipients',
        'successful_deliveries',
        'failed_deliveries',
        'channels',
        'channel_settings',
        'total_reads',
        'total_clicks',
        'engagement_stats',
        'is_html',
        'attachments',
        'action_url',
        'action_text',
        'priority',
        'expires_at',
        'is_persistent',
        'is_template',
        'template_name',
        'template_id'
    ];

    protected $casts = [
        'target_admin_ids' => 'array',
        'target_admin_roles' => 'array',
        'target_user_ids' => 'array',
        'target_user_criteria' => 'array',
        'channels' => 'array',
        'channel_settings' => 'array',
        'engagement_stats' => 'array',
        'attachments' => 'array',
        'is_html' => 'boolean',
        'is_persistent' => 'boolean',
        'is_template' => 'boolean',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the admin who created this notification
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_admin_id');
    }

    /**
     * Get notification deliveries
     */
    public function deliveries(): HasMany
    {
        return $this->hasMany(NotificationDelivery::class);
    }

    /**
     * Get template (if this notification is based on a template)
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(AdminNotification::class, 'template_id');
    }

    /**
     * Check if notification is active
     */
    public function isActive(): bool
    {
        $now = now();
        
        return $this->status === 'sent' && 
               (!$this->expires_at || $this->expires_at->isFuture());
    }

    /**
     * Send notification
     */
    public function send()
    {
        // Implementation would depend on your notification system
        // This is a placeholder for the notification sending logic
        $this->update([
            'status' => 'sent',
            'sent_at' => now()
        ]);
    }

    /**
     * Scope for active notifications
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'sent')
                    ->where(function($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                    });
    }

    /**
     * Scope for templates
     */
    public function scopeTemplates($query)
    {
        return $query->where('is_template', true);
    }
}