<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TripMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id',
        'user_id',
        'invitation_email',
        'role',
        'invitation_status',
    ];

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isPending(): bool
    {
        return $this->invitation_status === 'pending';
    }

    public function isAccepted(): bool
    {
        return $this->invitation_status === 'accepted';
    }

    public function isOrganizer(): bool
    {
        return $this->role === 'organizer';
    }
}