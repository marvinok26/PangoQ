<?php

namespace App\Http\Controllers\Admin\TripManagement;

use App\Http\Controllers\Controller;
use App\Models\TripTemplate;
use App\Models\Destination;
use App\Models\TemplateActivity;
use App\Models\ActivityLog;
use App\Http\Requests\Admin\TripManagement\StoreTripTemplateRequest;
use App\Http\Requests\Admin\TripManagement\UpdateTripTemplateRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class TripTemplateController extends Controller
{
    /**
     * Display listing of trip templates
     */
    public function index(Request $request): View
    {
        $query = TripTemplate::with(['destination'])
            ->withCount('activities');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('destination', function($destQuery) use ($search) {
                      $destQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('country', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by destination
        if ($request->filled('destination_id')) {
            $query->where('destination_id', $request->destination_id);
        }

        // Filter by trip style
        if ($request->filled('trip_style')) {
            $query->where('trip_style', $request->trip_style);
        }

        // Filter by difficulty level
        if ($request->filled('difficulty_level')) {
            $query->where('difficulty_level', $request->difficulty_level);
        }

        // Filter by featured status
        if ($request->filled('is_featured')) {
            $query->where('is_featured', $request->is_featured === '1');
        }

        $tripTemplates = $query->latest()->paginate(12);

        // Get filter options
        $destinations = Destination::select('id', 'name', 'country')->orderBy('name')->get();
        $tripStyles = TripTemplate::distinct()->pluck('trip_style')->filter()->sort()->values();
        $difficultyLevels = TripTemplate::distinct()->pluck('difficulty_level')->filter()->sort()->values();

        return view('admin.trip-templates.index', compact(
            'tripTemplates', 
            'destinations', 
            'tripStyles', 
            'difficultyLevels'
        ));
    }

    /**
     * Show form for creating new trip template
     */
    public function create(): View
    {
        $destinations = Destination::select('id', 'name', 'country')->orderBy('name')->get();
        
        return view('admin.trip-templates.create', compact('destinations'));
    }

    /**
     * Store newly created trip template
     */
    public function store(StoreTripTemplateRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Handle highlights JSON encoding
        if (isset($validated['highlights'])) {
            $validated['highlights'] = json_encode(array_filter($validated['highlights']));
        }

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('trip-templates', 'public');
        }

        $tripTemplate = TripTemplate::create($validated);

        // Log the activity if ActivityLog model exists
        if (class_exists('App\Models\ActivityLog')) {
            ActivityLog::log('trip_template_created', $tripTemplate, [
                'template_title' => $tripTemplate->title,
                'destination' => $tripTemplate->destination->name,
                'duration' => $tripTemplate->duration_days . ' days',
                'created_by' => auth()->user()->name
            ]);
        }

        return redirect()
            ->route('admin.trip-templates.show', $tripTemplate)
            ->with('success', 'Trip template created successfully! Now add activities to complete the itinerary.');
    }

    /**
     * Display specified trip template
     */
    public function show(TripTemplate $tripTemplate): View
    {
        $tripTemplate->load([
            'destination',
            'activities' => function($query) {
                $query->orderBy('day_number')
                      ->orderBy('start_time');
            }
        ]);

        // Group activities by day
        $activitiesByDay = $tripTemplate->activities->groupBy('day_number');

        // Calculate statistics
        $stats = [
            'total_activities' => $tripTemplate->activities->count(),
            'optional_activities' => $tripTemplate->activities->where('is_optional', true)->count(),
            'highlight_activities' => $tripTemplate->activities->where('is_highlight', true)->count(),
            'total_activity_cost' => $tripTemplate->activities->sum('cost'),
            'optional_activity_cost' => $tripTemplate->activities->where('is_optional', true)->sum('cost'),
        ];

        return view('admin.trip-templates.show', compact('tripTemplate', 'activitiesByDay', 'stats'));
    }

    /**
     * Show form for editing trip template
     */
    public function edit(TripTemplate $tripTemplate): View
    {
        $destinations = Destination::select('id', 'name', 'country')->orderBy('name')->get();
        $tripTemplate->load('destination');
        
        // Decode highlights for editing
        $highlights = $tripTemplate->highlights ? json_decode($tripTemplate->highlights, true) : [];
        
        return view('admin.trip-templates.edit', compact('tripTemplate', 'destinations', 'highlights'));
    }

    /**
     * Update specified trip template
     */
    public function update(UpdateTripTemplateRequest $request, TripTemplate $tripTemplate): RedirectResponse
    {
        $validated = $request->validated();

        // Store original data for logging
        $originalData = $tripTemplate->toArray();

        // Handle highlights JSON encoding
        if (isset($validated['highlights'])) {
            $validated['highlights'] = json_encode(array_filter($validated['highlights']));
        }

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($tripTemplate->featured_image) {
                Storage::disk('public')->delete($tripTemplate->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('trip-templates', 'public');
        }

        $tripTemplate->update($validated);

        // Log the activity if ActivityLog model exists
        if (class_exists('App\Models\ActivityLog')) {
            ActivityLog::log('trip_template_updated', $tripTemplate, [
                'template_title' => $tripTemplate->title,
                'updated_fields' => array_keys(array_diff_assoc($validated, $originalData)),
                'updated_by' => auth()->user()->name
            ], $originalData);
        }

        return redirect()
            ->route('admin.trip-templates.show', $tripTemplate)
            ->with('success', 'Trip template updated successfully!');
    }

    /**
     * Duplicate trip template
     */
    public function duplicate(TripTemplate $tripTemplate): RedirectResponse
    {
        $newTemplate = null;

        DB::transaction(function() use ($tripTemplate, &$newTemplate) {
            // Create new trip template
            $newTemplate = $tripTemplate->replicate();
            $newTemplate->title = $tripTemplate->title . ' (Copy)';
            $newTemplate->is_featured = false;
            $newTemplate->save();

            // Copy all activities
            foreach ($tripTemplate->activities as $activity) {
                $newActivity = $activity->replicate();
                $newActivity->trip_template_id = $newTemplate->id;
                $newActivity->save();
            }

            // Log the activity if ActivityLog model exists
            if (class_exists('App\Models\ActivityLog')) {
                ActivityLog::log('trip_template_duplicated', $newTemplate, [
                    'original_template' => $tripTemplate->title,
                    'new_template' => $newTemplate->title,
                    'activities_copied' => $tripTemplate->activities->count(),
                    'duplicated_by' => auth()->user()->name
                ]);
            }
        });

        return redirect()
            ->route('admin.trip-templates.edit', $newTemplate)
            ->with('success', 'Trip template duplicated successfully! Please review and customize the copy.');
    }

    /**
     * Remove specified trip template
     */
    public function destroy(TripTemplate $tripTemplate): RedirectResponse
    {
        // Store data for logging before deletion
        $templateData = $tripTemplate->toArray();
        $activitiesCount = $tripTemplate->activities()->count();

        DB::transaction(function() use ($tripTemplate) {
            // Delete associated activities first
            $tripTemplate->activities()->delete();

            // Delete featured image if exists
            if ($tripTemplate->featured_image) {
                Storage::disk('public')->delete($tripTemplate->featured_image);
            }

            $tripTemplate->delete();
        });

        // Log the activity if ActivityLog model exists
        if (class_exists('App\Models\ActivityLog')) {
            ActivityLog::log('trip_template_deleted', null, [
                'template_title' => $templateData['title'],
                'destination_id' => $templateData['destination_id'],
                'activities_deleted' => $activitiesCount,
                'deleted_by' => auth()->user()->name
            ], $templateData);
        }

        return redirect()
            ->route('admin.trip-templates.index')
            ->with('success', 'Trip template and all associated activities deleted successfully!');
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured(TripTemplate $tripTemplate): RedirectResponse
    {
        $tripTemplate->update([
            'is_featured' => !$tripTemplate->is_featured
        ]);

        // Log the activity if ActivityLog model exists
        if (class_exists('App\Models\ActivityLog')) {
            ActivityLog::log('trip_template_featured_toggled', $tripTemplate, [
                'template_title' => $tripTemplate->title,
                'is_featured' => $tripTemplate->is_featured,
                'toggled_by' => auth()->user()->name
            ]);
        }

        $status = $tripTemplate->is_featured ? 'featured' : 'unfeatured';
        
        return back()->with('success', "Trip template has been {$status} successfully!");
    }
}