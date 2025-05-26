<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Show admin dashboard
     */
    public function dashboard(): View
    {
        $stats = $this->getDashboardStats();
        
        return view('admin.dashboard', compact('stats'));
    }

    /**
     * Get dashboard statistics
     */
    private function getDashboardStats(): array
    {
        return [
            'total_users' => \App\Models\User::count(),
            'active_users' => \App\Models\User::where('account_status', 'active')->count(),
            'admin_users' => \App\Models\User::where('is_admin', true)->count(),
            'total_trips' => \App\Models\Trip::count(),
            'active_trips' => \App\Models\Trip::where('status', 'active')->count(),
            'flagged_trips' => \App\Models\Trip::where('admin_status', 'flagged')->count(),
            'featured_trips' => \App\Models\Trip::where('is_featured', true)->count(),
            'total_wallets' => \App\Models\SavingsWallet::count(),
            'flagged_wallets' => \App\Models\SavingsWallet::where('admin_flagged', true)->count(),
            'total_transactions' => \App\Models\WalletTransaction::count(),
            'recent_activities' => ActivityLog::with('user')
                ->adminActions()
                ->latest()
                ->take(10)
                ->get()
        ];
    }
}