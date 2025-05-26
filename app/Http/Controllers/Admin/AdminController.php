<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use App\Models\Trip;
use App\Models\SavingsWallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Show admin dashboard
     */
    public function dashboard(): View
    {
        // Check if user is authenticated and is admin
        if (!auth()->check()) {
            return redirect()->route('admin.login');
        }

        if (!auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }

        $user = auth()->user();
        
        // Log admin access
        Log::info('Admin dashboard accessed', [
            'admin_id' => $user->id,
            'admin_email' => $user->email
        ]);

        try {
            $stats = $this->getDashboardStats();
            $recentActivities = $this->getRecentActivities();
            $chartData = $this->getChartData();
            $alerts = $this->getSystemAlerts();
            
            // Add recent activities to stats array for the view
            $stats['recent_activities'] = $recentActivities;
            
            // Points to resources/views/admin/dashboard/index.blade.php
            return view('admin.dashboard.index', compact('stats', 'recentActivities', 'chartData', 'alerts'));
            
        } catch (\Exception $e) {
            Log::error('Error loading admin dashboard', [
                'admin_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Fallback data
            $stats = $this->getDefaultStats();
            $stats['recent_activities'] = collect();
            $recentActivities = collect();
            $chartData = [];
            $alerts = [];
            
            return view('admin.dashboard.index', compact('stats', 'recentActivities', 'chartData', 'alerts'));
        }
    }

    /**
     * Get dashboard statistics
     */
    private function getDashboardStats(): array
    {
        return [
            // User Statistics
            'total_users' => User::count(),
            'active_users' => User::where('account_status', 'active')->count(),
            'suspended_users' => User::where('account_status', 'suspended')->count(),
            'admin_users' => User::where('is_admin', true)->count(),
            'verified_users' => User::whereNotNull('email_verified_at')->count(),
            'new_users_today' => User::whereDate('created_at', today())->count(),
            'new_users_this_week' => User::where('created_at', '>=', now()->startOfWeek())->count(),
            'new_users_this_month' => User::whereMonth('created_at', now()->month)->count(),

            // Trip Statistics
            'total_trips' => Trip::count(),
            'active_trips' => Trip::where('status', 'active')->count(),
            'completed_trips' => Trip::where('status', 'completed')->count(),
            'planning_trips' => Trip::where('status', 'planning')->count(),
            'featured_trips' => Trip::where('is_featured', true)->count(),
            'flagged_trips' => Trip::where('admin_status', 'flagged')->count(),
            'trips_under_review' => Trip::where('admin_status', 'under_review')->count(),
            'new_trips_today' => Trip::whereDate('created_at', today())->count(),
            'new_trips_this_week' => Trip::where('created_at', '>=', now()->startOfWeek())->count(),

            // Financial Statistics
            'total_wallets' => SavingsWallet::count(),
            'flagged_wallets' => SavingsWallet::where('admin_flagged', true)->count(),
            'total_savings_amount' => SavingsWallet::sum('current_amount'),
            'total_transactions' => WalletTransaction::count(),
            'pending_transactions' => WalletTransaction::where('status', 'pending')->count(),
            'completed_transactions' => WalletTransaction::where('status', 'completed')->count(),
            'failed_transactions' => WalletTransaction::where('status', 'failed')->count(),
            'transactions_today' => WalletTransaction::whereDate('created_at', today())->count(),
            'transaction_volume_today' => WalletTransaction::whereDate('created_at', today())
                                                          ->where('status', 'completed')
                                                          ->sum('amount'),
        ];
    }

    /**
     * Get recent admin activities
     */
    private function getRecentActivities()
    {
        try {
            return ActivityLog::with('user')
                ->whereHas('user', function($query) {
                    $query->where('is_admin', true);
                })
                ->latest()
                ->take(15)
                ->get();
        } catch (\Exception $e) {
            Log::warning('Could not load recent activities', ['error' => $e->getMessage()]);
            return collect();
        }
    }

    /**
     * Get chart data for dashboard
     */
    private function getChartData(): array
    {
        try {
            // Users registered in the last 30 days
            $userRegistrations = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->where('created_at', '>=', now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->pluck('count', 'date');

            // Trip creation in the last 30 days
            $tripCreations = Trip::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->where('created_at', '>=', now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->pluck('count', 'date');

            // Transaction volume in the last 30 days
            $transactionVolume = WalletTransaction::selectRaw('DATE(created_at) as date, SUM(amount) as total')
                ->where('created_at', '>=', now()->subDays(30))
                ->where('status', 'completed')
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->pluck('total', 'date');

            return [
                'user_registrations' => $userRegistrations,
                'trip_creations' => $tripCreations,
                'transaction_volume' => $transactionVolume,
            ];
        } catch (\Exception $e) {
            Log::warning('Could not load chart data', ['error' => $e->getMessage()]);
            return [
                'user_registrations' => collect(),
                'trip_creations' => collect(),
                'transaction_volume' => collect(),
            ];
        }
    }

    /**
     * Get system alerts that need attention
     */
    private function getSystemAlerts(): array
    {
        try {
            return [
                'flagged_trips' => Trip::where('admin_status', 'flagged')->count(),
                'flagged_wallets' => SavingsWallet::where('admin_flagged', true)->count(),
                'suspended_users' => User::where('account_status', 'suspended')->count(),
                'pending_transactions' => WalletTransaction::where('status', 'pending')->count(),
                'trips_under_review' => Trip::where('admin_status', 'under_review')->count(),
                'unverified_users' => User::whereNull('email_verified_at')
                                         ->where('created_at', '<=', now()->subDays(7))
                                         ->count(),
                'high_value_transactions' => WalletTransaction::where('amount', '>', 10000)
                                                              ->where('status', 'pending')
                                                              ->count(),
            ];
        } catch (\Exception $e) {
            Log::warning('Could not load system alerts', ['error' => $e->getMessage()]);
            return [
                'flagged_trips' => 0,
                'flagged_wallets' => 0,
                'suspended_users' => 0,
                'pending_transactions' => 0,
                'trips_under_review' => 0,
                'unverified_users' => 0,
                'high_value_transactions' => 0,
            ];
        }
    }

    /**
     * Get default stats in case of error
     */
    private function getDefaultStats(): array
    {
        return [
            'total_users' => 0,
            'active_users' => 0,
            'suspended_users' => 0,
            'admin_users' => 0,
            'verified_users' => 0,
            'new_users_today' => 0,
            'new_users_this_week' => 0,
            'new_users_this_month' => 0,
            'total_trips' => 0,
            'active_trips' => 0,
            'completed_trips' => 0,
            'planning_trips' => 0,
            'featured_trips' => 0,
            'flagged_trips' => 0,
            'trips_under_review' => 0,
            'new_trips_today' => 0,
            'new_trips_this_week' => 0,
            'total_wallets' => 0,
            'flagged_wallets' => 0,
            'total_savings_amount' => 0,
            'total_transactions' => 0,
            'pending_transactions' => 0,
            'completed_transactions' => 0,
            'failed_transactions' => 0,
            'transactions_today' => 0,
            'transaction_volume_today' => 0,
        ];
    }
}