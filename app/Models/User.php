<?php

namespace App\Models;

use App\Traits\HasSavings;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasSavings;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'auth_provider',
        'auth_provider_id',
        'profile_photo_path',
        'language',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * The trips that this user has created.
     */
    public function createdTrips(): HasMany
    {
        return $this->hasMany(Trip::class, 'creator_id');
    }

    /**
     * The trip memberships of this user.
     */
    public function tripMemberships(): HasMany
    {
        return $this->hasMany(TripMember::class);
    }

    /**
     * The trips that this user is a member of.
     */
    public function trips(): BelongsToMany
    {
        return $this->belongsToMany(Trip::class, 'trip_members')
            ->withPivot('role', 'invitation_status')
            ->wherePivot('invitation_status', 'accepted')
            ->withTimestamps();
    }

    /**
     * All the wallet transactions made by this user.
     */
    public function walletTransactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

/**
 * Get the user's profile photo URL.
 *
 * @return string
 */
public function getPhotoUrlAttribute(): string
{
    // If profile_photo_path is a complete URL (social media avatar)
    if ($this->profile_photo_path && filter_var($this->profile_photo_path, FILTER_VALIDATE_URL)) {
        return $this->profile_photo_path;
    }
    
    // If it's a local path
    if ($this->profile_photo_path) {
        // Simple approach that assumes files are in public/storage
        return url('storage/' . $this->profile_photo_path);
    }
    
    // Fallback to Gravatar
    $hash = md5(strtolower(trim($this->email)));
    return "https://www.gravatar.com/avatar/{$hash}?d=mp&s=200";
}

    /**
     * Get the user's initials (for avatar fallback).
     */
    public function getInitialsAttribute(): string
    {
        $parts = explode(' ', $this->name);

        if (count($parts) >= 2) {
            return mb_substr($parts[0], 0, 1) . mb_substr(end($parts), 0, 1);
        }

        return mb_substr($this->name, 0, 2);
    }
}
