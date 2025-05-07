<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with static data
     * No authentication check required - accessible to all users
     */
    public function index(Request $request): View
    {
        // Static demo data to match React dashboard
        $userBalance = 1250.75;
        $targetAmount = 2500.00;
        $completionPercentage = ($userBalance / $targetAmount) * 100;
        
        $upcomingTrips = [
            (object)[
                'id' => 1,
                'title' => 'Bali Adventure',
                'destination' => 'Bali, Indonesia',
                'start_date' => now()->addMonths(2),
                'end_date' => now()->addMonths(2)->addDays(10),
                'progress' => 50,
                'members' => 5,
                'savingsWallet' => (object)[
                    'current_amount' => 1250.75,
                    'target_amount' => 2500.00,
                    'progress_percentage' => 50
                ]
            ],
            (object)[
                'id' => 2,
                'title' => 'Safari Weekend',
                'destination' => 'Maasai Mara, Kenya',
                'start_date' => now()->addMonth(),
                'end_date' => now()->addMonth()->addDays(3),
                'progress' => 75,
                'members' => 3,
                'savingsWallet' => (object)[
                    'current_amount' => 450.00,
                    'target_amount' => 600.00,
                    'progress_percentage' => 75
                ]
            ]
        ];
        
        $recentActivities = [
            (object)[
                'id' => 1,
                'type' => 'contribution',
                'amount' => 200.00,
                'date' => now()->subDays(4),
                'trip' => 'Bali Adventure'
            ],
            (object)[
                'id' => 2,
                'type' => 'contribution',
                'amount' => 150.00,
                'date' => now()->subDays(7),
                'trip' => 'Safari Weekend'
            ],
            (object)[
                'id' => 3,
                'type' => 'friend_joined',
                'user' => 'Sarah Johnson',
                'date' => now()->subDays(10),
                'trip' => 'Bali Adventure'
            ],
            (object)[
                'id' => 4,
                'type' => 'itinerary_update',
                'date' => now()->subDays(15),
                'trip' => 'Safari Weekend'
            ]
        ];
        
        $invitations = [
            (object)[
                'id' => 1,
                'title' => 'European Tour',
                'invited_by' => 'Mike Williams',
                'expires_at' => now()->addMonth(),
            ]
        ];
        
        $stats = [
            'total_saved' => $userBalance,
            'trips_planned' => 4,
            'trips_completed' => 2,
            'trips_upcoming' => 2,
            'friends_onboarded' => 8,
            'monthly_growth_percentage' => 12
        ];
        
        $wallet = [
            'balance' => $userBalance,
            'target_amount' => $targetAmount,
            'progress_percentage' => $completionPercentage,
            'monthly_growth_percentage' => 12
        ];
        
        return view('livewire.pages.dashboard', compact(
            'upcomingTrips',
            'recentActivities',
            'invitations',
            'stats',
            'wallet'
        ));
    }
}