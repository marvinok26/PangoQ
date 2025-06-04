<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TripInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id',
        'user_id',
        'invited_by',
        'email',
        'name',
        'message',
        'role',
        'status',
        'token',
        'expires_at',
        'accepted_at',
        'declined_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'accepted_at' => 'datetime',
        'declined_at' => 'datetime'
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_DECLINED = 'declined';
    const STATUS_EXPIRED = 'expired';

    const ROLE_MEMBER = 'member';
    const ROLE_ORGANIZER = 'organizer';
    const ROLE_VIEWER = 'viewer';

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the trip this invitation belongs to
     */
    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    /**
     * Get the user who was invited (if they have an account)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who sent the invitation
     */
    public function invitedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    // ==================== SCOPES ====================

    /**
     * Scope for pending invitations
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING)
                    ->where('expires_at', '>', now());
    }

    /**
     * Scope for accepted invitations
     */
    public function scopeAccepted($query)
    {
        return $query->where('status', self::STATUS_ACCEPTED);
    }

    /**
     * Scope for declined invitations
     */
    public function scopeDeclined($query)
    {
        return $query->where('status', self::STATUS_DECLINED);
    }

    /**
     * Scope for expired invitations
     */
    public function scopeExpired($query)
    {
        return $query->where(function($q) {
            $q->where('status', self::STATUS_EXPIRED)
              ->orWhere('expires_at', '<=', now());
        });
    }

    /**
     * Scope for invitations by email
     */
    public function scopeByEmail($query, $email)
    {
        return $query->where('email', $email);
    }

    // ==================== ATTRIBUTES ====================

    /**
     * Get the invitation status with expiry check
     */
    public function getActualStatusAttribute(): string
    {
        if ($this->expires_at && $this->expires_at->isPast() && $this->status === self::STATUS_PENDING) {
            return self::STATUS_EXPIRED;
        }
        
        return $this->status;
    }

    /**
     * Get the status color for UI
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->actual_status) {
            self::STATUS_PENDING => 'yellow',
            self::STATUS_ACCEPTED => 'green',
            self::STATUS_DECLINED => 'red',
            self::STATUS_EXPIRED => 'gray',
            default => 'gray'
        };
    }

    /**
     * Get the status display name
     */
    public function getStatusDisplayAttribute(): string
    {
        return match($this->actual_status) {
            self::STATUS_PENDING => 'Pending',
            self::STATUS_ACCEPTED => 'Accepted',
            self::STATUS_DECLINED => 'Declined',
            self::STATUS_EXPIRED => 'Expired',
            default => ucfirst($this->status)
        };
    }

    /**
     * Check if invitation is expired
     */
    public function getIsExpiredAttribute(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if invitation can be accepted
     */
    public function getCanBeAcceptedAttribute(): bool
    {
        return $this->status === self::STATUS_PENDING && !$this->is_expired;
    }

    /**
     * Get days until expiry
     */
    public function getDaysUntilExpiryAttribute(): ?int
    {
        if (!$this->expires_at || $this->is_expired) {
            return null;
        }
        
        return now()->diffInDays($this->expires_at);
    }

    /**
     * Get acceptance URL
     */
    public function getAcceptanceUrlAttribute(): string
    {
        return route('trip-invitations.accept', $this->token);
    }

    /**
     * Get decline URL
     */
    public function getDeclineUrlAttribute(): string
    {
        return route('trip-invitations.decline', $this->token);
    }

    // ==================== METHODS ====================

    /**
     * Accept the invitation
     */
    public function accept(?User $user = null): bool
    {
        if (!$this->can_be_accepted) {
            return false;
        }

        $this->update([
            'status' => self::STATUS_ACCEPTED,
            'accepted_at' => now(),
            'user_id' => $user?->id
        ]);

        // Create trip member record
        $this->trip->members()->create([
            'user_id' => $user?->id,
            'invitation_email' => $this->email,
            'role' => $this->role,
            'invitation_status' => 'accepted'
        ]);

        return true;
    }

    /**
     * Decline the invitation
     */
    public function decline(): bool
    {
        if (!$this->can_be_accepted) {
            return false;
        }

        $this->update([
            'status' => self::STATUS_DECLINED,
            'declined_at' => now()
        ]);

        return true;
    }

    /**
     * Resend the invitation
     */
    public function resend(?Carbon $expiresAt = null): self
    {
        $this->update([
            'token' => Str::random(64),
            'expires_at' => $expiresAt ?? now()->addDays(7),
            'status' => self::STATUS_PENDING,
            'accepted_at' => null,
            'declined_at' => null
        ]);

        return $this;
    }

    /**
     * Mark as expired
     */
    public function markAsExpired(): void
    {
        $this->update(['status' => self::STATUS_EXPIRED]);
    }

    // ==================== STATIC METHODS ====================

    /**
     * Create a new invitation
     */
    public static function createInvitation(
        Trip $trip,
        string $email,
        ?string $name = null,
        ?string $message = null,
        string $role = self::ROLE_MEMBER,
        ?User $invitedBy = null,
        ?Carbon $expiresAt = null
    ): self {
        return self::create([
            'trip_id' => $trip->id,
            'email' => strtolower($email),
            'name' => $name,
            'message' => $message,
            'role' => $role,
            'invited_by' => $invitedBy?->id,
            'token' => Str::random(64),
            'expires_at' => $expiresAt ?? now()->addDays(7),
            'status' => self::STATUS_PENDING
        ]);
    }

    /**
     * Find invitation by token
     */
    public static function findByToken(string $token): ?self
    {
        return self::where('token', $token)->first();
    }

    /**
     * Clean up expired invitations
     */
    public static function cleanupExpired(): int
    {
        return self::where('status', self::STATUS_PENDING)
                  ->where('expires_at', '<=', now())
                  ->update(['status' => self::STATUS_EXPIRED]);
    }

    // ==================== MODEL EVENTS ====================

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invitation) {
            // Auto-generate token if not provided
            if (!$invitation->token) {
                $invitation->token = Str::random(64);
            }

            // Set default expiry if not provided
            if (!$invitation->expires_at) {
                $invitation->expires_at = now()->addDays(7);
            }

            // Check if user exists and link them
            if (!$invitation->user_id) {
                $user = User::where('email', $invitation->email)->first();
                if ($user) {
                    $invitation->user_id = $user->id;
                }
            }
        });

        static::created(function ($invitation) {
            // Send invitation email
            // Mail::to($invitation->email)->send(new TripInvitationMail($invitation));
        });
    }
}
