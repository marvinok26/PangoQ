<?php

// Update UserController.php - change view paths to admin.user-management

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
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
                  ->orWhere('account_number', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
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

        // Filter by verification status
        if ($request->filled('verified')) {
            if ($request->verified === 'yes') {
                $query->whereNotNull('email_verified_at');
            } else {
                $query->whereNull('email_verified_at');
            }
        }

        // Filter by registration date
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by admin role
        if ($request->filled('admin_role')) {
            $query->where('admin_role', $request->admin_role);
        }

        $users = $query->latest()->paginate(15);

        // Get statistics for current filter
        $stats = [
            'total_users' => $query->count(),
            'active_users' => (clone $query)->where('account_status', 'active')->count(),
            'suspended_users' => (clone $query)->where('account_status', 'suspended')->count(),
            'admin_users' => (clone $query)->where('is_admin', true)->count(),
            'verified_users' => (clone $query)->whereNotNull('email_verified_at')->count(),
        ];

        // Points to resources/views/admin/user-management/index.blade.php
        return view('admin.user-management.index', compact('users', 'stats'));
    }

    /**
     * Show user details
     */
    public function show(User $user): View
    {
        $user->load([
            'createdTrips', 
            'savingsWallets', 
            'walletTransactions',
            'tripMemberships.trip'
        ]);
        
        $activities = ActivityLog::forModel(User::class, $user->id)
            ->with('user')
            ->latest()
            ->take(20)
            ->get();

        // Get user statistics
        $stats = [
            'total_trips_created' => $user->createdTrips->count(),
            'total_trips_member' => $user->trips->count(),
            'total_savings' => $user->total_savings ?? 0,
            'total_transactions' => $user->walletTransactions->count(),
            'completed_transactions' => $user->walletTransactions->where('status', 'completed')->count(),
            'pending_transactions' => $user->walletTransactions->where('status', 'pending')->count(),
            'account_age_days' => $user->created_at->diffInDays(now()),
            'last_activity' => $user->updated_at,
        ];

        // Points to resources/views/admin/user-management/show.blade.php
        return view('admin.user-management.show', compact('user', 'activities', 'stats'));
    }

    /**
     * Update user status
     */
    public function updateStatus(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'account_status' => 'required|in:active,inactive,suspended',
            'reason' => 'nullable|string|max:255'
        ]);

        // Prevent self-suspension for super admins
        if ($user->id === auth()->id() && $request->account_status === 'suspended') {
            return back()->with('error', 'You cannot suspend your own account.');
        }

        $oldStatus = $user->account_status;

        DB::beginTransaction();
        try {
            $user->update(['account_status' => $request->account_status]);

            ActivityLog::log('user_status_updated', $user, [
                'old_status' => $oldStatus,
                'new_status' => $request->account_status,
                'reason' => $request->reason,
                'updated_by' => auth()->user()->name
            ]);

            DB::commit();

            return back()->with('success', 'User status updated successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to update user status: ' . $e->getMessage());
        }
    }

    /**
     * Update user admin privileges
     */
    public function updateAdminStatus(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'is_admin' => 'required|boolean',
            'admin_role' => 'nullable|in:admin,moderator,support',
            'admin_notes' => 'nullable|string|max:500'
        ]);

        // Prevent self-demotion
        if ($user->id === auth()->id() && !$request->is_admin) {
            return back()->with('error', 'You cannot remove your own admin privileges.');
        }

        $oldAdminStatus = $user->is_admin;
        $oldAdminRole = $user->admin_role;

        DB::beginTransaction();
        try {
            $updateData = [
                'is_admin' => $request->is_admin,
                'admin_notes' => $request->admin_notes
            ];

            if ($request->is_admin) {
                $updateData['admin_role'] = $request->admin_role;
                if (!$oldAdminStatus) {
                    $updateData['admin_since'] = now();
                }
            } else {
                $updateData['admin_role'] = null;
                $updateData['admin_since'] = null;
            }

            $user->update($updateData);

            ActivityLog::log('user_admin_status_updated', $user, [
                'old_admin_status' => $oldAdminStatus,
                'new_admin_status' => $request->is_admin,
                'old_admin_role' => $oldAdminRole,
                'new_admin_role' => $request->admin_role,
                'admin_notes' => $request->admin_notes,
                'updated_by' => auth()->user()->name
            ]);

            DB::commit();

            $message = $request->is_admin ? 'User granted admin privileges.' : 'User admin privileges removed.';
            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to update admin status: ' . $e->getMessage());
        }
    }

    /**
     * Reset user password
     */
    public function resetPassword(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
            'reason' => 'required|string|max:255'
        ]);

        DB::beginTransaction();
        try {
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);

            ActivityLog::log('user_password_reset_by_admin', $user, [
                'reason' => $request->reason,
                'reset_by' => auth()->user()->name
            ]);

            DB::commit();

            return back()->with('success', 'User password reset successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to reset password: ' . $e->getMessage());
        }
    }

    /**
     * Verify user email
     */
    public function verifyEmail(User $user): RedirectResponse
    {
        if ($user->email_verified_at) {
            return back()->with('info', 'User email is already verified.');
        }

        $user->update(['email_verified_at' => now()]);

        ActivityLog::log('user_email_verified_by_admin', $user, [
            'verified_by' => auth()->user()->name
        ]);

        return back()->with('success', 'User email verified successfully.');
    }

    /**
     * Bulk actions for users
     */
    public function bulkAction(Request $request): RedirectResponse
    {
        $request->validate([
            'action' => 'required|in:activate,suspend,verify_email,export',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'reason' => 'nullable|string|max:255'
        ]);

        // Prevent self-action
        if (in_array(auth()->id(), $request->user_ids)) {
            return back()->with('error', 'You cannot perform bulk actions on your own account.');
        }

        $users = User::whereIn('id', $request->user_ids);
        $count = $users->count();

        DB::beginTransaction();
        try {
            switch ($request->action) {
                case 'activate':
                    $users->update(['account_status' => 'active']);
                    $message = "{$count} users activated successfully.";
                    break;

                case 'suspend':
                    $users->update(['account_status' => 'suspended']);
                    $message = "{$count} users suspended successfully.";
                    break;

                case 'verify_email':
                    $users->whereNull('email_verified_at')->update(['email_verified_at' => now()]);
                    $message = "Email verification updated for eligible users.";
                    break;

                case 'export':
                    // TODO: Implement user export functionality
                    $message = "Export functionality will be implemented soon.";
                    break;
            }

            ActivityLog::log('users_bulk_action', null, [
                'action' => $request->action,
                'user_ids' => $request->user_ids,
                'count' => $count,
                'reason' => $request->reason,
                'performed_by' => auth()->user()->name
            ]);

            DB::commit();

            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Bulk action failed: ' . $e->getMessage());
        }
    }
}