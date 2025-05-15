<?php

namespace App\Services;

use App\Models\Trip;
use App\Models\TripMember;
use App\Models\SavingsWallet;
use App\Models\WalletTransaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class DashboardService
{
    protected SavingsService $savingsService;

    public function __construct(SavingsService $savingsService)
    {
        $this->savingsService = $savingsService;
    }

    public function getUserWalletData(int $userId): array
    {
        // Get all user's wallets from their trips
        $wallets = SavingsWallet::whereHas('trip', function ($query) use ($userId) {
            $query->whereHas('members', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            });
        })->get();

        // Calculate totals across all wallets
        $totalBalance = $wallets->sum('current_amount');
        $totalTarget = $wallets->sum('target_amount');
        $progressPercentage = ($totalTarget > 0) ? min(100, round(($totalBalance / $totalTarget) * 100, 2)) : 0;

        // Calculate monthly growth
        $lastMonth = now()->subMonth();
        $lastMonthTotal = WalletTransaction::whereIn('wallet_id', $wallets->pluck('id'))
            ->where('type', 'deposit')
            ->where('status', 'completed')
            ->where('created_at', '<=', $lastMonth)
            ->sum('amount');

        $thisMonthTotal = WalletTransaction::whereIn('wallet_id', $wallets->pluck('id'))
            ->where('type', 'deposit')
            ->where('status', 'completed')
            ->where('created_at', '>', $lastMonth)
            ->sum('amount');

        $monthlyGrowthPercentage = ($lastMonthTotal > 0) ?
            round((($thisMonthTotal - $lastMonthTotal) / $lastMonthTotal) * 100, 2) : 0;

        return [
            'balance' => $totalBalance,
            'target_amount' => $totalTarget,
            'progress_percentage' => $progressPercentage,
            'monthly_growth_percentage' => max(0, $monthlyGrowthPercentage) // Ensure it's not negative for UI
        ];
    }

    public function getUpcomingTrips(int $userId): Collection
    {
        return Trip::with(['members', 'savingsWallet'])
            ->whereHas('members', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->where('start_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->get()
            ->map(function ($trip) {
                $memberCount = $trip->members->count();
                $progressPercentage = 0;

                if ($trip->savingsWallet) {
                    $progressPercentage = $trip->savingsWallet->progress_percentage;
                }

                return (object)[
                    'id' => $trip->id,
                    'title' => $trip->title,
                    'destination' => $trip->destination,
                    'start_date' => $trip->start_date,
                    'end_date' => $trip->end_date,
                    'progress' => $progressPercentage,
                    'members' => $memberCount,
                    'savingsWallet' => $trip->savingsWallet
                ];
            });
    }

    public function getRecentActivities(int $userId): Collection
    {
        // Get recent wallet transactions
        $transactions = WalletTransaction::with(['wallet.trip', 'user'])
            ->whereHas('wallet.trip.members', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($transaction) {
                return (object)[
                    'id' => $transaction->id,
                    'type' => 'contribution',
                    'amount' => $transaction->amount,
                    'date' => $transaction->created_at,
                    'trip' => $transaction->wallet->trip->title
                ];
            });

        // Get recent trip member additions
        $members = TripMember::with(['trip', 'user'])
            ->whereHas('trip.members', function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->where('role', 'organizer');
            })
            ->where('user_id', '!=', $userId)
            ->where('invitation_status', 'accepted')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($member) {
                return (object)[
                    'id' => 'member-' . $member->id,
                    'type' => 'friend_joined',
                    'user' => $member->user->name,
                    'date' => $member->updated_at,
                    'trip' => $member->trip->title
                ];
            });

        // Combine all activity types and sort by date
        return $transactions->concat($members)
            ->sortByDesc('date')
            ->take(5);
    }

    public function getPendingInvitations(int $userId): Collection
    {
        $user = User::find($userId);

        return TripMember::with(['trip', 'trip.creator'])
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere('invitation_email', $user->email);
            })
            ->where('invitation_status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($member) {
                return (object)[
                    'id' => $member->id,
                    'title' => $member->trip->title,
                    'invited_by' => $member->trip->creator->name,
                    'expires_at' => $member->created_at->addDays(7)
                ];
            });
    }

    public function getUserStats(int $userId): array
    {
        // Get all user's trips
        $userTrips = Trip::whereHas('members', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->get();

        // Calculate statistics
        $tripsPlanned = $userTrips->count();
        $tripsCompleted = $userTrips->where('end_date', '<', now())->count();
        $tripsUpcoming = $userTrips->where('start_date', '>=', now())->count();

        // Count friends onboarded (people they invited who registered)
        $friendsOnboarded = TripMember::whereHas('trip', function ($query) use ($userId) {
            $query->where('creator_id', $userId);
        })
            ->where('user_id', '!=', $userId)
            ->where('invitation_status', 'accepted')
            ->distinct('user_id')
            ->count('user_id');

        // Get wallet statistics
        $walletData = $this->getUserWalletData($userId);

        return [
            'trips_planned' => $tripsPlanned,
            'trips_completed' => $tripsCompleted,
            'trips_upcoming' => $tripsUpcoming,
            'friends_onboarded' => $friendsOnboarded,
            'total_saved' => $walletData['balance'] ?? 0,
            'monthly_growth_percentage' => $walletData['monthly_growth_percentage'] ?? 0
        ];
    }
}
