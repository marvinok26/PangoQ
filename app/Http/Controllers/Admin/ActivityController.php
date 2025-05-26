<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

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

        // Filter by admin actions only
        if ($request->filled('admin_only')) {
            $query->adminActions();
        }

        $activities = $query->latest()->paginate(20);

        // Get unique actions and model types for filters
        $actions = ActivityLog::distinct()->pluck('action')->filter();
        $modelTypes = ActivityLog::distinct()->pluck('model_type')->filter();

        return view('admin.activities.index', compact('activities', 'actions', 'modelTypes'));
    }

    /**
     * Show activity details
     */
    public function show(ActivityLog $activity): View
    {
        $activity->load('user');
        
        return view('admin.activities.show', compact('activity'));
    }
}