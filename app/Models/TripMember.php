<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripMember extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'trip_id',
        'user_id',
        'role',
        'invitation_status',
        'invitation_email'
    ];
    
    // A trip member belongs to a trip
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
    
    // A trip member belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Scope for pending invitations
    public function scopePending($query)
    {
        return $query->where('invitation_status', 'pending');
    }
    
    // Scope for accepted invitations
    public function scopeAccepted($query)
    {
        return $query->where('invitation_status', 'accepted');
    }
    
    // Scope for declined invitations
    public function scopeDeclined($query)
    {
        return $query->where('invitation_status', 'declined');
    }
    
    // Helper to check if user has a specific role
    public function hasRole($role)
    {
        return $this->role === $role;
    }
    
    // Helper to check if invitation is accepted
    public function isAccepted()
    {
        return $this->invitation_status === 'accepted';
    }
    
    // Helper to check if invitation is pending
    public function isPending()
    {
        return $this->invitation_status === 'pending';
    }
    
    // Helper to check if invitation is declined
    public function isDeclined()
    {
        return $this->invitation_status === 'declined';
    }
}