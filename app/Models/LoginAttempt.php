<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoginAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'user_id',
        'ip_address',
        'user_agent',
        'location_data',
        'device_fingerprint',
        'success',
        'failure_reason',
        'admin_attempt',
        'admin_portal',
        'two_factor_required',
        'two_factor_success',
        'session_id',
        'login_method',
        'oauth_provider',
        'risk_score',
        'blocked_reason',
        'attempted_at'
    ];

    protected $casts = [
        'location_data' => 'array',
        'success' => 'boolean',
        'admin_attempt' => 'boolean',
        'admin_portal' => 'boolean',
        'two_factor_required' => 'boolean',
        'two_factor_success' => 'boolean',
        'attempted_at' => 'datetime',
    ];

    /**
     * Get the user associated with this login attempt
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Log a successful login
     */
    public static function logSuccess($user, $isAdmin = false, $adminPortal = false)
    {
        return self::create([
            'email' => $user->email,
            'user_id' => $user->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'success' => true,
            'admin_attempt' => $isAdmin,
            'admin_portal' => $adminPortal,
            'session_id' => session()->getId(),
            'login_method' => 'email',
            'attempted_at' => now()
        ]);
    }

    /**
     * Log a failed login
     */
    public static function logFailure($email, $reason, $isAdminAttempt = false)
    {
        return self::create([
            'email' => $email,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'success' => false,
            'failure_reason' => $reason,
            'admin_attempt' => $isAdminAttempt,
            'attempted_at' => now()
        ]);
    }

    /**
     * Check for suspicious activity
     */
    public static function isSuspiciousActivity($email, $ipAddress = null)
    {
        $query = self::where('email', $email)
                    ->where('success', false)
                    ->where('created_at', '>', now()->subHour());

        if ($ipAddress) {
            $query->where('ip_address', $ipAddress);
        }

        return $query->count() >= 5;
    }

    /**
     * Get failed attempts count for email in timeframe
     */
    public static function getFailedAttemptsCount($email, $minutes = 60)
    {
        return self::where('email', $email)
                  ->where('success', false)
                  ->where('created_at', '>', now()->subMinutes($minutes))
                  ->count();
    }

    /**
     * Scope for successful logins
     */
    public function scopeSuccessful($query)
    {
        return $query->where('success', true);
    }

    /**
     * Scope for failed logins
     */
    public function scopeFailed($query)
    {
        return $query->where('success', false);
    }

    /**
     * Scope for admin attempts
     */
    public function scopeAdminAttempts($query)
    {
        return $query->where('admin_attempt', true);
    }

    /**
     * Scope for recent attempts
     */
    public function scopeRecent($query, $minutes = 60)
    {
        return $query->where('attempted_at', '>', now()->subMinutes($minutes));
    }
}