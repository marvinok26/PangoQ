<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display users list
     */
    public function index(Request $request): View
    {
        $query = User::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('account_number', 'like', "%{$search}%");
            });
        }

        // Filter by account status
        if ($request->filled('status')) {
            $query->where('account_status', $request->status);
        }

        // Filter by admin status
        if ($request->filled('is_admin')) {
            $query->where('is_admin', $request->is_admin);
        }

        $users = $query->latest()->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show user details
     */
    public function show(User $user): View
    {
        $user->load(['createdTrips', 'savingsWallets', 'walletTransactions']);
        
        $activities = ActivityLog::forModel(User::class, $user->id)
            ->with('user')
            ->latest()
            ->take(20)
            ->get();

        return view('admin.users.show', compact('user', 'activities'));
    }

    /**
     * Update user status
     */
    public function updateStatus(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'account_status' => 'required|in:active,inactive,suspended'
        ]);

        $oldStatus = $user->account_status;
        $user->update(['account_status' => $request->account_status]);

        ActivityLog::log('user_status_updated', $user, [
            'old_status' => $oldStatus,
            'new_status' => $request->account_status
        ]);

        return back()->with('success', 'User status updated successfully.');
    }
}