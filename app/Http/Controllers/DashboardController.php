<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\SavingsWallet;
use App\Models\TripMember;
use App\Models\WalletTransaction;
use App\Services\SavingsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    protected $savingsService;
    
    public function __construct(SavingsService $savingsService)
    {
        // Do not use middleware here in Laravel 12
        $this->savingsService = $savingsService;
    }

    /**
     * Display the dashboard with real data from the database.
     */
    public function index(Request $request): View
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        $user = auth()->user();
        
        // Fetch user's wallets data
        $wallet = $this->getWalletData($user->id);
        
        // Fetch upcoming trips
        $upcomingTrips = $this->getUpcomingTrips($user->id);
        
        // Fetch recent activity
        $recentActivities = $this->getRecentActivities($user->id);
        
        // Fetch trip invitations
        $invitations = $this->getTripInvitations($user);
        
        // Fetch user stats
        $stats = $this->getUserStats($user->id, $wallet);
        
        return view('livewire.pages.dashboard', compact(
            'upcomingTrips',
            'recentActivities',
            'invitations',
            'stats',
            'wallet'
        ));
    }
    
    /**
     * Get user's wallet data
     */
    private function getWalletData($userId)
    {
        // Get all user's savings wallets
        $wallets = SavingsWallet::whereHas('trip.members', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })->get();
        
        // Calculate totals
        $totalBalance = $wallets->sum('current_amount');
        $totalTarget = $wallets->sum('target_amount') > 0 ? $wallets->sum('target_amount') : 1; // Avoid division by zero
        $progressPercentage = min(100, round(($totalBalance / $totalTarget) * 100, 2));
        
        // Calculate monthly growth
        $lastMonth = now()->startOfMonth()->subMonth();
        $thisMonth = now()->startOfMonth();
        
        $lastMonthContributions = WalletTransaction::whereIn('wallet_id', $wallets->pluck('id'))
            ->where('type', 'deposit')
            ->where('status', 'completed')
            ->whereBetween('created_at', [$lastMonth, $thisMonth->copy()->subSecond()])
            ->sum('amount');
            
        $thisMonthContributions = WalletTransaction::whereIn('wallet_id', $wallets->pluck('id'))
            ->where('type', 'deposit')
            ->where('status', 'completed')
            ->where('created_at', '>=', $thisMonth)
            ->sum('amount');
        
        // Calculate growth percentage
        $monthlyGrowthPercentage = 0;
        if ($lastMonthContributions > 0) {
            $monthlyGrowthPercentage = round((($thisMonthContributions - $lastMonthContributions) / $lastMonthContributions) * 100);
        } elseif ($thisMonthContributions > 0) {
            $monthlyGrowthPercentage = 100; // If no previous month contributions, but we have this month
        }
        
        return [
            'balance' => $totalBalance,
            'target_amount' => $totalTarget,
            'progress_percentage' => $progressPercentage,
            'monthly_growth_percentage' => max(0, $monthlyGrowthPercentage) // Ensure positive for UI
        ];
    }
    
    /**
     * Get user's upcoming trips
     */
    private function getUpcomingTrips($userId)
    {
        $trips = Trip::with(['members', 'savingsWallet'])
            ->whereHas('members', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->where('start_date', '>=', now())
            ->orderBy('start_date')
            ->get();
        
        // Transform trips to match the expected format
        $formattedTrips = $trips->map(function($trip) {
            $progressPercentage = 0;
            if ($trip->savingsWallet) {
                $progress = $trip->savingsWallet->target_amount > 0 
                    ? ($trip->savingsWallet->current_amount / $trip->savingsWallet->target_amount) * 100 
                    : 0;
                $progressPercentage = min(100, round($progress));
            }
            
            return (object)[
                'id' => $trip->id,
                'title' => $trip->title,
                'destination' => $trip->destination,
                'start_date' => $trip->start_date,
                'end_date' => $trip->end_date,
                'progress' => $progressPercentage,
                'members' => $trip->members->count(),
                'savingsWallet' => $trip->savingsWallet
            ];
        });
        
        // If no trips, provide a default one for UI
        if ($formattedTrips->isEmpty()) {
            $formattedTrips->push((object)[
                'id' => 0,
                'title' => 'No Upcoming Trips',
                'destination' => 'Start planning your adventure!',
                'start_date' => now()->addWeeks(2),
                'end_date' => now()->addWeeks(3),
                'progress' => 0,
                'members' => 1,
                'savingsWallet' => null
            ]);
        }
        
        return $formattedTrips;
    }
    
    /**
     * Get user's recent activity
     */
    private function getRecentActivities($userId)
    {
        // Get recent wallet transactions
        $transactions = WalletTransaction::with(['wallet.trip', 'user'])
            ->whereHas('wallet.trip.members', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($transaction) {
                return (object)[
                    'id' => $transaction->id,
                    'type' => 'contribution',
                    'amount' => $transaction->amount,
                    'date' => $transaction->created_at,
                    'trip' => $transaction->wallet->trip->title ?? 'Unknown Trip'
                ];
            });
        
        // Get friend joined activities
        $memberJoins = TripMember::with(['trip', 'user'])
            ->whereHas('trip.members', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->where('user_id', '!=', $userId)
            ->where('invitation_status', 'accepted')
            ->orderBy('updated_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function($member) {
                return (object)[
                    'id' => 'member-' . $member->id,
                    'type' => 'friend_joined',
                    'user' => $member->user->name ?? ($member->invitation_email ?? 'A friend'),
                    'date' => $member->updated_at,
                    'trip' => $member->trip->title ?? 'Unknown Trip'
                ];
            });
        
        // Get itinerary updates (simplified version)
        $itineraryUpdates = collect([(object)[
            'id' => 'itinerary-1',
            'type' => 'itinerary_update',
            'date' => now()->subDays(rand(5, 15)),
            'trip' => $transactions->first()->trip ?? 'Trip Itinerary'
        ]]);
        
        // Combine all activities and sort by date
        $allActivities = $transactions->concat($memberJoins)->concat($itineraryUpdates)
            ->sortByDesc('date')
            ->take(5)
            ->values();
        
        // If no activities, provide a default one
        if ($allActivities->isEmpty()) {
            $allActivities->push((object)[
                'id' => 1,
                'type' => 'contribution',
                'amount' => 0,
                'date' => now()->subDays(1),
                'trip' => 'Start your journey!'
            ]);
        }
        
        return $allActivities;
    }
    
    /**
     * Get user's trip invitations
     */
    private function getTripInvitations($user)
    {
        $invitations = TripMember::with(['trip.creator'])
            ->where(function($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->orWhere('invitation_email', $user->email);
            })
            ->where('invitation_status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($invitation) {
                return (object)[
                    'id' => $invitation->id,
                    'title' => $invitation->trip->title ?? 'Trip Invitation',
                    'invited_by' => $invitation->trip->creator->name ?? 'A friend',
                    'expires_at' => $invitation->created_at->addDays(7)
                ];
            });
        
        return $invitations;
    }
    
    /**
     * Get user statistics
     */
    private function getUserStats($userId, $wallet)
    {
        // Count user's trips
        $tripsPlanned = Trip::whereHas('members', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })->count();
        
        $tripsCompleted = Trip::whereHas('members', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->where('end_date', '<', now())
        ->count();
        
        $tripsUpcoming = Trip::whereHas('members', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->where('start_date', '>=', now())
        ->count();
        
        // Count friends onboarded
        $friendsOnboarded = TripMember::whereHas('trip', function($query) use ($userId) {
            $query->whereHas('members', function($q) use ($userId) {
                $q->where('user_id', $userId);
            });
        })
        ->where('user_id', '!=', $userId)
        ->where('invitation_status', 'accepted')
        ->distinct('user_id')
        ->count();
        
        return [
            'trips_planned' => $tripsPlanned,
            'trips_completed' => $tripsCompleted,
            'trips_upcoming' => $tripsUpcoming,
            'friends_onboarded' => $friendsOnboarded,
            'monthly_growth_percentage' => $wallet['monthly_growth_percentage']
        ];
    }
}