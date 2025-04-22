<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'creator_id',
        'title',
        'description',
        'destination',
        'start_date',
        'end_date',
        'budget',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'budget' => 'decimal:2',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function members(): HasMany
    {
        return $this->hasMany(TripMember::class);
    }

    public function acceptedMembers()
    {
        return $this->members()->where('invitation_status', 'accepted');
    }

    public function pendingInvitations()
    {
        return $this->members()->where('invitation_status', 'pending');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'trip_members')
            ->withPivot('role', 'invitation_status')
            ->withTimestamps();
    }

    public function itineraries(): HasMany
    {
        return $this->hasMany(Itinerary::class);
    }

    public function savingsWallet(): HasOne
    {
        return $this->hasOne(SavingsWallet::class);
    }

    public function getDurationInDaysAttribute(): int
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    public function getTripDateRangeAttribute(): string
    {
        return $this->start_date->format('M j') . ' - ' . $this->end_date->format('M j, Y');
    }

    public function isCreator(int $userId): bool
    {
        return $this->creator_id === $userId;
    }

    public function isMember(int $userId): bool
    {
        return $this->members()
            ->where('user_id', $userId)
            ->where('invitation_status', 'accepted')
            ->exists();
    }

    public function canAccess(int $userId): bool
    {
        return $this->isCreator($userId) || $this->isMember($userId);
    }

    public function getStatusColorAttribute(): string
    {
        return [
            'planning' => 'blue',
            'active' => 'green',
            'completed' => 'purple',
            'cancelled' => 'red',
        ][$this->status] ?? 'gray';
    }
}