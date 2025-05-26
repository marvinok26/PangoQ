<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class SupportTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'subject',
        'description',
        'user_id',
        'user_name',
        'user_email',
        'category',
        'priority',
        'status',
        'assigned_admin_id',
        'assigned_at',
        'first_response_at',
        'resolved_at',
        'closed_at',
        'attachments',
        'tags',
        'internal_notes',
        'resolution_summary',
        'response_time_minutes',
        'resolution_time_minutes',
        'satisfaction_rating',
        'satisfaction_feedback'
    ];

    protected $casts = [
        'attachments' => 'array',
        'tags' => 'array',
        'assigned_at' => 'datetime',
        'first_response_at' => 'datetime',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            if (empty($ticket->ticket_number)) {
                $ticket->ticket_number = self::generateTicketNumber();
            }
        });
    }

    /**
     * Generate unique ticket number
     */
    public static function generateTicketNumber()
    {
        $prefix = 'TKT-' . date('Y') . '-';
        $number = str_pad(self::count() + 1, 6, '0', STR_PAD_LEFT);
        
        return $prefix . $number;
    }

    /**
     * Get the user who created the ticket
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin assigned to the ticket
     */
    public function assignedAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_admin_id');
    }

    /**
     * Get ticket responses
     */
    public function responses(): HasMany
    {
        return $this->hasMany(SupportTicketResponse::class);
    }

    /**
     * Check if ticket is open
     */
    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    /**
     * Check if ticket is resolved
     */
    public function isResolved(): bool
    {
        return $this->status === 'resolved';
    }

    /**
     * Assign ticket to admin
     */
    public function assignTo(User $admin)
    {
        $this->update([
            'assigned_admin_id' => $admin->id,
            'assigned_at' => now(),
            'status' => 'in_progress'
        ]);
    }

    /**
     * Mark as resolved
     */
    public function markAsResolved(User $admin, $resolutionSummary = null)
    {
        $resolvedAt = now();
        
        $this->update([
            'status' => 'resolved',
            'resolved_at' => $resolvedAt,
            'resolution_summary' => $resolutionSummary,
            'resolution_time_minutes' => $this->created_at->diffInMinutes($resolvedAt)
        ]);

        // Log admin activity
        AdminActivityLog::logAction(
            $admin,
            'resolve_ticket',
            'SupportTicket',
            $this->id,
            "Resolved support ticket: {$this->subject}"
        );
    }

    /**
     * Scope for open tickets
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    /**
     * Scope for urgent tickets
     */
    public function scopeUrgent($query)
    {
        return $query->where('priority', 'urgent');
    }

    /**
     * Scope for assigned tickets
     */
    public function scopeAssigned($query)
    {
        return $query->whereNotNull('assigned_admin_id');
    }
}