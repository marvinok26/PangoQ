<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Notification extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'read_at',
        // Adding admin fields
        'created_by_admin_id',
        'is_admin_notification',
        'priority'
    ];
    
    protected $casts = [
        'read_at' => 'datetime',
        'is_admin_notification' => 'boolean'
    ];
    
    // ============ EXISTING RELATIONSHIPS (PRESERVED) ============
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    // ============ EXISTING METHODS (PRESERVED) ============
    public function isRead(): bool
    {
        return $this->read_at !== null;
    }
    
    public function markAsRead(): void
    {
        $this->update(['read_at' => Carbon::now()]);
    }

    // ============ NEW ADMIN METHODS (MINIMAL) ============
    /**
     * Get admin who created this notification
     */
    public function createdByAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_admin_id');
    }

    /**
     * Create admin notification for user
     */
    public static function createAdminNotification($userId, $title, $message, $type = 'info', $priority = 'normal')
    {
        return self::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'created_by_admin_id' => auth()->id(),
            'is_admin_notification' => true,
            'priority' => $priority
        ]);
    }

    /**
     * Create bulk admin notifications
     */
    public static function createBulkAdminNotifications($userIds, $title, $message, $type = 'info')
    {
        $notifications = [];
        $adminId = auth()->id();

        foreach ($userIds as $userId) {
            $notifications[] = [
                'user_id' => $userId,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'created_by_admin_id' => $adminId,
                'is_admin_notification' => true,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        self::insert($notifications);
        
        ActivityLog::log('bulk_notifications_sent', null, [
            'recipient_count' => count($userIds),
            'title' => $title,
            'type' => $type
        ]);
    }

    /**
     * Scope for admin notifications
     */
    public function scopeAdminNotifications($query)
    {
        return $query->where('is_admin_notification', true);
    }

    /**
     * Scope for high priority
     */
    public function scopeHighPriority($query)
    {
        return $query->where('priority', 'high');
    }
}