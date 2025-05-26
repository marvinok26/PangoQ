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
        'phone_number',
        'id_card_number',
        'passport_number',
        'date_of_birth',
        'gender',
        'nationality',
        'address',
        'account_number',
        'account_type',
        'currency',
        'linked_bank_account',
        'wallet_provider',
        'account_status',
        'preferred_payment_method',
        'daily_transaction_limit',
        // Admin fields (new additions)
        'is_admin',
        'admin_role',
        'admin_since',
        'admin_notes'
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
        'date_of_birth' => 'date',
        'daily_transaction_limit' => 'decimal:2',
        // Admin field casts (new additions)
        'is_admin' => 'boolean',
        'admin_since' => 'datetime',
        'last_admin_login' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Generate account number on user creation if not set
        static::creating(function ($user) {
            if (empty($user->account_number)) {
                $user->account_number = self::generateAccountNumber();
            }
        });
    }

    /**
     * Generate a unique account number.
     */
    public static function generateAccountNumber()
    {
        $prefix = '0551';
        $random = sprintf('%06d', mt_rand(0, 999999));
        
        $accountNumber = $prefix . $random;
        
        // Ensure it's unique
        while (self::where('account_number', $accountNumber)->exists()) {
            $random = sprintf('%06d', mt_rand(0, 999999));
            $accountNumber = $prefix . $random;
        }
        
        return $accountNumber;
    }

    // ============ ADMIN-RELATED METHODS (NEW) ============
    
    /**
     * Check if user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->is_admin && $this->account_status === 'active';
    }

    /**
     * Check if user is a super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->isAdmin() && $this->admin_role === 'super_admin';
    }

    /**
     * Check if user can manage other users
     */
    public function canManageUsers(): bool
    {
        return $this->isAdmin() && in_array($this->admin_role, ['super_admin', 'admin']);
    }

    /**
     * Check if user can manage financial operations
     */
    public function canManageFinancials(): bool
    {
        return $this->isAdmin() && in_array($this->admin_role, ['super_admin', 'admin']);
    }

    /**
     * Check if user can manage trips
     */
    public function canManageTrips(): bool
    {
        return $this->isAdmin();
    }

    // ============ ADMIN RELATIONSHIPS (NEW) ============
    
    /**
     * Support tickets assigned to this admin
     */
    public function assignedTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class, 'assigned_to');
    }

    /**
     * Support tickets resolved by this admin
     */
    public function resolvedTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class, 'resolved_by');
    }

    /**
     * Withdrawal requests processed by this admin
     */
    public function processedWithdrawals(): HasMany
    {
        return $this->hasMany(WithdrawalRequest::class, 'processed_by');
    }

    /**
     * Refund requests processed by this admin
     */
    public function processedRefunds(): HasMany
    {
        return $this->hasMany(RefundRequest::class, 'processed_by');
    }

    /**
     * Admin notifications created by this admin
     */
    public function adminNotifications(): HasMany
    {
        return $this->hasMany(AdminNotification::class, 'created_by');
    }

    /**
     * Offers/discounts created by this admin
     */
    public function createdOffers(): HasMany
    {
        return $this->hasMany(OfferDiscount::class, 'created_by');
    }

    /**
     * Activity logs for this user
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Support tickets created by this user (as customer)
     */
    public function supportTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class);
    }

    /**
     * Withdrawal requests made by this user
     */
    public function withdrawalRequests(): HasMany
    {
        return $this->hasMany(WithdrawalRequest::class);
    }

    /**
     * Refund requests made by this user
     */
    public function refundRequests(): HasMany
    {
        return $this->hasMany(RefundRequest::class);
    }

    /**
     * Trips reviewed by this admin
     */
    public function reviewedTrips(): HasMany
    {
        return $this->hasMany(Trip::class, 'reviewed_by');
    }

    // ============ EXISTING RELATIONSHIPS (PRESERVED) ============

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
     * Get all savings wallets for this user.
     */
    public function savingsWallets()
    {
        return $this->hasManyThrough(SavingsWallet::class, Trip::class, 'creator_id');
    }

    /**
     * Get the user's total savings amount.
     */
    public function getTotalSavingsAttribute()
    {
        return $this->savingsWallets()->sum('current_amount');
    }

    /**
     * Get the user's total savings goal.
     */
    public function getTotalSavingsGoalAttribute()
    {
        // Use minimum_goal instead of target_amount
        return $this->savingsWallets()->sum('minimum_goal');
    }

    /**
     * Get the user's savings progress percentage.
     */
    public function getSavingsProgressPercentageAttribute()
    {
        $goal = $this->total_savings_goal;
        
        if ($goal <= 0) {
            return 0;
        }
        
        $percentage = ($this->total_savings / $goal) * 100;
        return min(100, round($percentage, 1));
    }

    /**
     * Get the user's profile photo URL.
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