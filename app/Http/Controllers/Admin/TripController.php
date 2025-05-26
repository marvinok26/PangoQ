<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TripController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display trips list
     */
    public function index(Request $request): View
    {
        $query = Trip::with(['creator', 'tripTemplate']);

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

        $trips = $query->latest()->paginate(15);

        return view('admin.trips.index', compact('trips'));
    }

    /**
     * Show trip details
     */
    public function show(Trip $trip): View
    {
        $trip->load(['creator', 'members.user', 'itineraries.activities', 'savingsWallet']);
        
        $activities = ActivityLog::forModel(Trip::class, $trip->id)
            ->with('user')
            ->latest()
            ->take(20)
            ->get();

        return view('admin.trips.show', compact('trip', 'activities'));
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

        return back()->with('success', 'Trip status updated successfully.');
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
}
