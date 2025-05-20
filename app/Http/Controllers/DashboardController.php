<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\SavingsWallet;
use App\Models\TripMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Carbon\Carbon;

class DashboardController extends Controller
{
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
        
        // Debug: Log user information
        Log::info('Dashboard for user', [
            'user_id' => $user->id,
            'email' => $user->email
        ]);
        
        try {
            // Get trips where user is the creator
            $createdTrips = $user->createdTrips()->get();
            
            // Get trips where user is a member
            $memberTrips = Trip::join('trip_members', 'trips.id', '=', 'trip_members.trip_id')
                ->where('trip_members.user_id', $user->id)
                ->where('trip_members.invitation_status', 'accepted')
                ->select('trips.*')
                ->distinct()
                ->get();
            
            // Combine both sets and remove duplicates
            $allTrips = $createdTrips->merge($memberTrips)->unique('id');
            
            // Log what we found
            Log::info('Found trips for dashboard', [
                'user_id' => $user->id,
                'trips_count' => $allTrips->count(),
                'trip_ids' => $allTrips->pluck('id')->toArray()
            ]);
            
            // Format trips for display
            $upcomingTrips = $allTrips->map(function($trip) {
                // Make sure date is Carbon instance
                $startDate = $trip->start_date instanceof Carbon ? 
                    $trip->start_date : 
                    Carbon::parse($trip->start_date);
                    
                $endDate = $trip->end_date instanceof Carbon ? 
                    $trip->end_date : 
                    Carbon::parse($trip->end_date);
                
                // Get savings wallet data if it exists
                $progressPercentage = 0;
                if ($trip->savingsWallet) {
                    $progressPercentage = $trip->savingsWallet->progress_percentage;
                }
                
                // Count members
                $memberCount = $trip->members ? $trip->members->count() : 1;
                
                return (object)[
                    'id' => $trip->id,
                    'title' => $trip->title,
                    'destination' => $trip->destination,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'progress' => $progressPercentage,
                    'members' => $memberCount
                ];
            });
            
            // Sort by start date
            $upcomingTrips = $upcomingTrips->sortBy('start_date');
            
            // If no trips, provide a default
            if ($upcomingTrips->isEmpty()) {
                $upcomingTrips = collect([(object)[
                    'id' => 0,
                    'title' => 'No Upcoming Trips',
                    'destination' => 'Start planning your adventure!',
                    'start_date' => now()->addWeeks(2),
                    'end_date' => now()->addWeeks(3),
                    'progress' => 0,
                    'members' => 1,
                ]]);
            }
            
            // Get wallet data
            $walletData = $this->getWalletData($user, $allTrips);
            
            // Create activities based on trips
            $recentActivities = $this->getRecentActivities($allTrips);
            
            // Get pending invitations
            $invitations = $this->getPendingInvitations($user);
            
            // Calculate stats
            $stats = $this->calculateStats($user, $allTrips);
            
        } catch (\Exception $e) {
            // Log error for debugging
            Log::error('Error loading dashboard data', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Set default values
            $upcomingTrips = collect([(object)[
                'id' => 0,
                'title' => 'No Upcoming Trips',
                'destination' => 'Start planning your adventure!',
                'start_date' => now()->addWeeks(2),
                'end_date' => now()->addWeeks(3),
                'progress' => 0,
                'members' => 1,
            ]]);
            
            $walletData = [
                'balance' => 0,
                'target_amount' => 1.00,
                'progress_percentage' => 0,
                'monthly_growth_percentage' => 0
            ];
            
            $recentActivities = collect([(object)[
                'id' => 1,
                'type' => 'contribution',
                'amount' => 0,
                'date' => now()->subDays(1),
                'trip' => 'Start your journey!'
            ]]);
            
            $invitations = collect();
            
            $stats = [
                'trips_planned' => 0,
                'trips_completed' => 0,
                'trips_upcoming' => 0,
                'friends_onboarded' => 0,
                'monthly_growth_percentage' => 0
            ];
        }
        
        return view('livewire.pages.dashboard', [
            'upcomingTrips' => $upcomingTrips,
            'wallet' => $walletData,
            'recentActivities' => $recentActivities,
            'invitations' => $invitations,
            'stats' => $stats,
        ]);
    }
    
    /**
     * Get wallet data for the dashboard
     */
    private function getWalletData($user, $trips)
    {
        $wallets = SavingsWallet::whereIn('trip_id', $trips->pluck('id'))->get();
        
        $balance = $wallets->sum('current_amount');
        $targetAmount = $wallets->sum('minimum_goal');
        
        if ($targetAmount <= 0) {
            $targetAmount = max(1, $trips->sum('budget') ?: 1);
        }
        
        $progressPercentage = 0;
        if ($targetAmount > 0) {
            $progressPercentage = min(100, round(($balance / $targetAmount) * 100, 2));
        }
        
        return [
            'balance' => $balance,
            'target_amount' => $targetAmount,
            'progress_percentage' => $progressPercentage,
            'monthly_growth_percentage' => 0 // Calculate this later if needed
        ];
    }
    
    /**
     * Get recent activities for the dashboard
     */
    private function getRecentActivities($trips)
    {
        $activities = collect();
        
        foreach ($trips->take(3) as $index => $trip) {
            $activities->push((object)[
                'id' => $index + 1,
                'type' => 'itinerary_update',
                'date' => $trip->created_at ?: now()->subDays($index + 1),
                'trip' => $trip->title
            ]);
        }
        
        if ($activities->isEmpty()) {
            $activities->push((object)[
                'id' => 1,
                'type' => 'contribution',
                'amount' => 0,
                'date' => now()->subDays(1),
                'trip' => 'Start your journey!'
            ]);
        }
        
        return $activities;
    }
    
    /**
     * Get pending invitations for the user
     */
    private function getPendingInvitations($user)
    {
        return collect(); // Implement later if needed
    }
    
    /**
     * Calculate stats for the dashboard
     */
    private function calculateStats($user, $trips)
    {
        $now = now();
        
        return [
            'trips_planned' => $trips->count(),
            'trips_completed' => $trips->filter(function($trip) use ($now) {
                return $trip->end_date < $now;
            })->count(),
            'trips_upcoming' => $trips->filter(function($trip) use ($now) {
                return $trip->start_date >= $now;
            })->count(),
            'friends_onboarded' => 0,
            'monthly_growth_percentage' => 0
        ];
    }
}