<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'status'
    ];
    
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'budget' => 'decimal:2',
    ];
    
    // Relationships
    
    // A trip belongs to a creator (user)
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
    
    // A trip has many members
    public function members()
    {
        return $this->hasMany(TripMember::class);
    }
    
    // A trip has many itineraries (days)
    public function itineraries()
    {
        return $this->hasMany(Itinerary::class);
    }
    
    // A trip has a savings wallet
    public function savingsWallet()
    {
        return $this->hasOne(SavingsWallet::class);
    }
    
    // Get all users associated with the trip
    public function users()
    {
        return $this->belongsToMany(User::class, 'trip_members', 'trip_id', 'user_id')
            ->withPivot('role', 'invitation_status')
            ->withTimestamps();
    }
    
    // Helpers
    
    // Calculate duration in days
    public function getDurationAttribute()
    {
        return $this->start_date->diffInDays($this->end_date) + 1; // +1 to include the end day
    }
    
    // Check if user is a member of the trip
    public function isMember($userId)
    {
        return $this->members()->where('user_id', $userId)->exists();
    }
    
    // Check if user is the organizer of the trip
    public function isOrganizer($userId)
    {
        return $this->creator_id === $userId || 
               $this->members()->where('user_id', $userId)
                   ->where('role', 'organizer')
                   ->exists();
    }
    
    // Scope for upcoming trips
    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>=', now())
            ->orderBy('start_date', 'asc');
    }
    
    // Scope for past trips
    public function scopePast($query)
    {
        return $query->where('end_date', '<', now())
            ->orderBy('end_date', 'desc');
    }
}