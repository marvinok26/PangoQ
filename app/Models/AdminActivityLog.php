<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'admin_name',
        'admin_email',
        'action',
        'resource_type',
        'resource_id',
        'resource_identifier',
        'description',
        'old_values',
        'new_values',
        'metadata',
        'ip_address',
        'user_agent',
        'session_id',
        'severity',
        'category'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'metadata' => 'array',
    ];

    /**
     * Get the admin who performed the action
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Scope for high severity actions
     */
    public function scopeHighSeverity($query)
    {
        return $query->whereIn('severity', ['high', 'critical']);
    }

    /**
     * Scope for specific resource type
     */
    public function scopeForResource($query, $resourceType)
    {
        return $query->where('resource_type', $resourceType);
    }

    /**
     * Log an admin action
     */
    public static function logAction($admin, $action, $resourceType = null, $resourceId = null, $description = null, $oldValues = null, $newValues = null, $severity = 'medium')
    {
        return self::create([
            'admin_id' => $admin->id,
            'admin_name' => $admin->name,
            'admin_email' => $admin->email,
            'action' => $action,
            'resource_type' => $resourceType,
            'resource_id' => $resourceId,
            'resource_identifier' => $resourceType ? self::getResourceIdentifier($resourceType, $resourceId) : null,
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'session_id' => session()->getId(),
            'severity' => $severity,
            'category' => self::getCategoryFromAction($action)
        ]);
    }

    /**
     * Get resource identifier for display
     */
    private static function getResourceIdentifier($resourceType, $resourceId)
    {
        switch ($resourceType) {
            case 'User':
                $user = User::find($resourceId);
                return $user ? $user->name : "User #{$resourceId}";
            case 'Trip':
                $trip = Trip::find($resourceId);
                return $trip ? $trip->title : "Trip #{$resourceId}";
            case 'WithdrawalRequest':
                $withdrawal = WithdrawalRequest::find($resourceId);
                return $withdrawal ? $withdrawal->withdrawal_number : "Withdrawal #{$resourceId}";
            default:
                return "{$resourceType} #{$resourceId}";
        }
    }

    /**
     * Categorize action for reporting
     */
    private static function getCategoryFromAction($action)
    {
        $financialActions = ['approve_withdrawal', 'reject_withdrawal', 'process_refund'];
        $userActions = ['create_user', 'update_user', 'delete_user', 'suspend_user'];
        $contentActions = ['approve_trip', 'flag_trip', 'feature_trip'];

        if (in_array($action, $financialActions)) {
            return 'financial';
        } elseif (in_array($action, $userActions)) {
            return 'user_management';
        } elseif (in_array($action, $contentActions)) {
            return 'content';
        }

        return 'general';
    }
}
