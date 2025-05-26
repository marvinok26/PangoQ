<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportTicketResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'support_ticket_id',
        'user_id',
        'is_admin_response',
        'author_name',
        'author_email',
        'message',
        'attachments',
        'is_internal',
        'response_type'
    ];

    protected $casts = [
        'attachments' => 'array',
        'is_admin_response' => 'boolean',
        'is_internal' => 'boolean',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($response) {
            // Update first response time if this is the first admin response
            if ($response->is_admin_response && !$response->is_internal) {
                $ticket = $response->supportTicket;
                if (!$ticket->first_response_at) {
                    $ticket->update([
                        'first_response_at' => now(),
                        'response_time_minutes' => $ticket->created_at->diffInMinutes(now())
                    ]);
                }
            }
        });
    }

    /**
     * Get the support ticket
     */
    public function supportTicket(): BelongsTo
    {
        return $this->belongsTo(SupportTicket::class);
    }

    /**
     * Get the response author
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if this is an admin response
     */
    public function isAdminResponse(): bool
    {
        return $this->is_admin_response;
    }

    /**
     * Check if this is an internal note
     */
    public function isInternal(): bool
    {
        return $this->is_internal;
    }

    /**
     * Scope for admin responses
     */
    public function scopeAdminResponses($query)
    {
        return $query->where('is_admin_response', true);
    }

    /**
     * Scope for public responses (not internal)
     */
    public function scopePublic($query)
    {
        return $query->where('is_internal', false);
    }
}