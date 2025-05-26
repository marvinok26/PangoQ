<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class TripController extends Controller
{
    /**
     * Display trips list
     */
    public function index(Request $request): View
    {
        $query = Trip::with(['creator', 'tripTemplate', 'reviewer']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('destination', 'like', "%{$search}%")
                  ->orWhereHas('creator', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by admin status
        if ($request->filled('admin_status')) {
            $query->where('admin_status', $request->admin_status);
        }

        // Filter by featured
        if ($request->filled('is_featured')) {
            $query->where('is_featured', $request->is_featured);
        }

        // Filter by planning type
        if ($request->filled('planning_type')) {
            $query->where('planning_type', $request->planning_type);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('start_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('end_date', '<=', $request->date_to);
        }

        // Filter by budget range
        if ($request->filled('min_budget')) {
            $query->where('budget', '>=', $request->min_budget);
        }

        if ($request->filled('max_budget')) {
            $query->where('budget', '<=', $request->max_budget);
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        
        $allowedSortFields = ['created_at', 'title', 'start_date', 'budget', 'report_count'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->latest();
        }

        $trips = $query->paginate(15);

        // Get statistics for current filter
        $stats = [
            'total' => $query->count(),
            'active' => (clone $query)->where('status', 'active')->count(),
            'flagged' => (clone $query)->where('admin_status', 'flagged')->count(),
            'featured' => (clone $query)->where('is_featured', true)->count(),
        ];

        return view('admin.monitoring.trips.index', compact('trips', 'stats'));
    }

    /**
     * Show trip details
     */
    public function show(Trip $trip): View
    {
        $trip->load([
            'creator', 
            'members.user', 
            'itineraries.activities', 
            'savingsWallet',
            'tripTemplate',
            'reviewer'
        ]);
        
        $activities = ActivityLog::forModel(Trip::class, $trip->id)
            ->with('user')
            ->latest()
            ->take(20)
            ->get();

        // Get related statistics
        $stats = [
            'total_members' => $trip->members->count(),
            'total_activities' => $trip->allActivities()->count(),
            'savings_progress' => $trip->savingsWallet ? $trip->savingsWallet->progress_percentage : 0,
            'days_until_trip' => $trip->start_date ? $trip->start_date->diffInDays(now()) : null,
        ];

        return view('admin.monitoring.trips.show', compact('trip', 'activities', 'stats'));
    }

    /**
     * Update trip admin status
     */
    public function updateAdminStatus(Request $request, Trip $trip): RedirectResponse
    {
        $request->validate([
            'admin_status' => 'required|in:approved,under_review,flagged,restricted',
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        $oldStatus = $trip->admin_status;
        
        DB::beginTransaction();
        try {
            $trip->update([
                'admin_status' => $request->admin_status,
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
                'admin_notes' => $request->admin_notes
            ]);

            ActivityLog::log('trip_admin_status_updated', $trip, [
                'old_status' => $oldStatus,
                'new_status' => $request->admin_status,
                'notes' => $request->admin_notes
            ]);

            DB::commit();

            return back()->with('success', 'Trip status updated successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to update trip status: ' . $e->getMessage());
        }
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured(Trip $trip): RedirectResponse
    {
        $newStatus = !$trip->is_featured;
        
        $trip->update(['is_featured' => $newStatus]);

        ActivityLog::log('trip_featured_toggled', $trip, [
            'is_featured' => $newStatus
        ]);

        $message = $newStatus ? 'Trip marked as featured.' : 'Trip removed from featured.';
        return back()->with('success', $message);
    }

    /**
     * Bulk actions for trips
     */
    public function bulkAction(Request $request): RedirectResponse
    {
        $request->validate([
            'action' => 'required|in:approve,flag,feature,unfeature,restrict',
            'trip_ids' => 'required|array',
            'trip_ids.*' => 'exists:trips,id',
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        $trips = Trip::whereIn('id', $request->trip_ids);
        $count = $trips->count();

        DB::beginTransaction();
        try {
            switch ($request->action) {
                case 'approve':
                    $trips->update([
                        'admin_status' => 'approved',
                        'reviewed_by' => auth()->id(),
                        'reviewed_at' => now(),
                        'admin_notes' => $request->admin_notes
                    ]);
                    $message = "{$count} trips approved successfully.";
                    break;

                case 'flag':
                    $trips->update([
                        'admin_status' => 'flagged',
                        'reviewed_by' => auth()->id(),
                        'reviewed_at' => now(),
                        'admin_notes' => $request->admin_notes ?: 'Bulk flagged by admin'
                    ]);
                    $message = "{$count} trips flagged successfully.";
                    break;

                case 'feature':
                    $trips->update(['is_featured' => true]);
                    $message = "{$count} trips marked as featured.";
                    break;

                case 'unfeature':
                    $trips->update(['is_featured' => false]);
                    $message = "{$count} trips removed from featured.";
                    break;

                case 'restrict':
                    $trips->update([
                        'admin_status' => 'restricted',
                        'reviewed_by' => auth()->id(),
                        'reviewed_at' => now(),
                        'admin_notes' => $request->admin_notes ?: 'Bulk restricted by admin'
                    ]);
                    $message = "{$count} trips restricted successfully.";
                    break;
            }

            ActivityLog::log('trips_bulk_action', null, [
                'action' => $request->action,
                'trip_ids' => $request->trip_ids,
                'count' => $count,
                'admin_notes' => $request->admin_notes
            ]);

            DB::commit();

            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Bulk action failed: ' . $e->getMessage());
        }
    }

    /**
     * Export trips
     */
    public function export(Request $request): RedirectResponse
    {
        $request->validate([
            'format' => 'required|in:csv,excel',
            'include_members' => 'boolean',
            'include_activities' => 'boolean'
        ]);

        // TODO: Implement export functionality
        ActivityLog::log('trips_export_requested', null, [
            'format' => $request->format,
            'filters' => $request->except(['format', 'include_members', 'include_activities'])
        ]);

        return back()->with('info', 'Export functionality will be implemented soon.');
    }

    /**
     * Get trip analytics
     */
    public function analytics(): View
    {
        $analytics = [
            'trip_creation_trend' => Trip::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->where('created_at', '>=', now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date')
                ->get(),

            'status_distribution' => Trip::selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->get(),

            'planning_type_distribution' => Trip::selectRaw('planning_type, COUNT(*) as count')
                ->groupBy('planning_type')
                ->get(),

            'top_destinations' => Trip::selectRaw('destination, COUNT(*) as count')
                ->groupBy('destination')
                ->orderByDesc('count')
                ->take(10)
                ->get(),

            'average_budget' => Trip::whereNotNull('budget')->avg('budget'),
            'total_budget' => Trip::sum('budget'),
            'completion_rate' => Trip::where('status', 'completed')->count() / max(Trip::count(), 1) * 100,
        ];

        return view('admin.monitoring.trips.analytics', compact('analytics'));
    }
}