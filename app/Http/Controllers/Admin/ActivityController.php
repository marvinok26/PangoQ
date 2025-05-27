<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityController extends Controller
{
    /**
     * Display activity logs
     */
    public function index(Request $request): View
    {
        $query = ActivityLog::with('user');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('model_type', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by model type
        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by admin actions only
        if ($request->filled('admin_only')) {
            $query->adminActions();
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by IP address (for security analysis)
        if ($request->filled('ip_address')) {
            $query->where('ip_address', $request->ip_address);
        }

        $activities = $query->latest()->paginate(20);

        // Get unique actions and model types for filters
        $actions = ActivityLog::distinct()->pluck('action')->filter()->sort();
        $modelTypes = ActivityLog::distinct()->pluck('model_type')->filter()->sort();

        // Get statistics for current filter
        $stats = [
            'total_activities' => $query->count(),
            'admin_activities' => (clone $query)->adminActions()->count(),
            'unique_users' => (clone $query)->distinct('user_id')->count('user_id'),
            'unique_ips' => (clone $query)->distinct('ip_address')->count('ip_address'),
            'today_activities' => (clone $query)->whereDate('created_at', today())->count(),
        ];

        return view('admin.platform.activities.index', compact('activities', 'actions', 'modelTypes', 'stats'));
    }

    /**
     * Show activity details
     */
    public function show(ActivityLog $activity): View
    {
        $activity->load('user');

        // Get related activities (same user, same model, within time range)
        $relatedActivities = [];
        if ($activity->user_id && $activity->model_type && $activity->model_id) {
            $relatedActivities = ActivityLog::where('user_id', $activity->user_id)
                ->where('model_type', $activity->model_type)
                ->where('model_id', $activity->model_id)
                ->where('id', '!=', $activity->id)
                ->where('created_at', '>=', $activity->created_at->subHours(24))
                ->where('created_at', '<=', $activity->created_at->addHours(24))
                ->with('user')
                ->latest()
                ->take(10)
                ->get();
        }

        // Get user's recent activities
        $userRecentActivities = [];
        if ($activity->user) {
            $userRecentActivities = ActivityLog::where('user_id', $activity->user_id)
                ->where('id', '!=', $activity->id)
                ->latest()
                ->take(10)
                ->get();
        }
        
        return view('admin.platform.activities.show', compact('activity', 'relatedActivities', 'userRecentActivities'));
    }

    /**
     * Get security insights
     */
    public function security(Request $request): View
    {
        $period = $request->input('period', 7); // days

        // Suspicious activity patterns
        $suspiciousActivities = [
            // Multiple failed login attempts
            'failed_logins' => ActivityLog::where('action', 'login_failed')
                ->where('created_at', '>=', now()->subDays($period))
                ->groupBy('ip_address')
                ->havingRaw('COUNT(*) > 3')
                ->selectRaw('ip_address, COUNT(*) as attempts')
                ->orderByDesc('attempts')
                ->limit(10)
                ->get(),

            // Admin actions from different IPs
            'admin_ips' => ActivityLog::adminActions()
                ->where('created_at', '>=', now()->subDays($period))
                ->groupBy(['user_id', 'ip_address'])
                ->with('user')
                ->selectRaw('user_id, ip_address, COUNT(*) as actions')
                ->orderByDesc('actions')
                ->limit(15)
                ->get(),

            // High activity users
            'high_activity' => ActivityLog::where('created_at', '>=', now()->subDays($period))
                ->groupBy('user_id')
                ->havingRaw('COUNT(*) > 50')
                ->with('user')
                ->selectRaw('user_id, COUNT(*) as actions')
                ->orderByDesc('actions')
                ->limit(10)
                ->get(),
        ];

        // Activity timeline
        $activityTimeline = ActivityLog::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays($period))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top actions
        $topActions = ActivityLog::where('created_at', '>=', now()->subDays($period))
            ->groupBy('action')
            ->selectRaw('action, COUNT(*) as count')
            ->orderByDesc('count')
            ->take(10)
            ->get();

        return view('admin.platform.activities.security', compact(
            'suspiciousActivities',
            'activityTimeline', 
            'topActions',
            'period'
        ));
    }

    /**
     * Export activity logs
     */
    public function export(Request $request)
    {
        $request->validate([
            'format' => 'required|in:csv,json',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ]);

        // TODO: Implement activity log export
        ActivityLog::log('activity_logs_export_requested', null, [
            'format' => $request->format,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'requested_by' => auth()->user()->name
        ]);

        return back()->with('info', 'Export functionality will be implemented soon.');
    }

    /**
     * Helper method to get action badge color (for reference)
     */
    public function getActionBadgeColor(string $action): string
    {
        return get_action_badge_color($action);
    }
}