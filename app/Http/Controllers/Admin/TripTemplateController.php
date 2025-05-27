<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TripTemplate;
use App\Models\Destination;
use App\Models\ActivityLog;
use App\Http\Requests\Admin\TripManagement\StoreTripTemplateRequest;
use App\Http\Requests\Admin\TripManagement\UpdateTripTemplateRequest;
use App\Services\TripTemplateService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class TripTemplateController extends Controller
{
    protected $tripTemplateService;

    public function __construct(TripTemplateService $tripTemplateService)
    {
        $this->tripTemplateService = $tripTemplateService;
    }

    /**
     * Display listing of trip templates
     */
    public function index(Request $request): View
    {
        $query = TripTemplate::with(['destination', 'activities']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('trip_style', 'like', "%{$search}%")
                    ->orWhereHas('destination', function ($destQuery) use ($search) {
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

        $tripTemplates = $query->withCount('activities')
            ->latest()
            ->paginate(12);

        // Get filter options
        $destinations = Destination::orderBy('name')->get();
        $tripStyles = TripTemplate::distinct()->pluck('trip_style')->filter()->sort()->values();
        $difficultyLevels = ['easy', 'moderate', 'challenging'];

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
    public function create(Request $request): View
    {
        $destinations = Destination::orderBy('name')->get();

        return view('admin.trip-templates.create', compact('destinations'));
    }

    /**
     * Store newly created trip template
     */
    public function store(StoreTripTemplateRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Handle image upload
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('trip-templates', 'public');
        }

        $tripTemplate = TripTemplate::create($validated);

        // Log the activity
        ActivityLog::log('trip_template_created', $tripTemplate, [
            'template_title' => $tripTemplate->title,
            'destination' => $tripTemplate->destination->name,
            'duration_days' => $tripTemplate->duration_days,
            'base_price' => $tripTemplate->base_price,
            'created_by' => auth()->user()->name
        ]);

        return redirect()
            ->route('admin.trip-templates.show', $tripTemplate)
            ->with('success', 'Trip template created successfully! Now add activities to complete the itinerary.');
    }

    /**
     * Display specified trip template
     */
    public function show(TripTemplate $tripTemplate): View
    {
        $tripTemplate->load(['destination', 'activities' => function ($query) {
            $query->orderBy('day_number')->orderBy('start_time');
        }]);

        $activitiesByDay = $tripTemplate->activities->groupBy('day_number');

        // Calculate statistics
        $stats = $this->tripTemplateService->calculateTemplateStats($tripTemplate);

        return view('admin.trip-templates.show', compact('tripTemplate', 'activitiesByDay', 'stats'));
    }

    /**
     * Show form for editing trip template
     */
    public function edit(TripTemplate $tripTemplate): View
    {
        $destinations = Destination::orderBy('name')->get();

        return view('admin.trip-templates.edit', compact('tripTemplate', 'destinations'));
    }

    /**
     * Update specified trip template
     */
    public function update(UpdateTripTemplateRequest $request, TripTemplate $tripTemplate): RedirectResponse
    {
        $validated = $request->validated();

        // Store original data for logging
        $originalData = $tripTemplate->only([
            'destination_id',
            'title',
            'description',
            'duration_days',
            'base_price',
            'difficulty_level',
            'trip_style',
            'is_featured'
        ]);

        // Handle highlights separately to avoid array conversion issues
        if (isset($validated['highlights']) && is_array($validated['highlights'])) {
            // Filter out empty highlights
            $highlights = array_filter($validated['highlights'], function ($highlight) {
                return !empty(trim($highlight ?? ''));
            });
            // Convert to JSON manually
            $validated['highlights'] = json_encode(array_values($highlights));
        }

        // Handle image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image if exists
            if ($tripTemplate->featured_image) {
                Storage::disk('public')->delete($tripTemplate->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('trip-templates', 'public');
        }

        // Update without using mutators for highlights
        $tripTemplate->fill($validated);
        $tripTemplate->save();

        // Log the activity
        ActivityLog::log('trip_template_updated', $tripTemplate, [
            'template_title' => $tripTemplate->title,
            'updated_fields' => array_keys($validated),
            'updated_by' => auth()->user()->name
        ]);

        return redirect()
            ->route('admin.trip-templates.show', $tripTemplate)
            ->with('success', 'Trip template updated successfully!');
    }

    /**
     * Remove specified trip template
     */
    public function destroy(TripTemplate $tripTemplate): RedirectResponse
    {
        // Store data for logging before deletion
        $templateData = $tripTemplate->toArray();
        $activitiesCount = $tripTemplate->activities()->count();

        DB::beginTransaction();
        try {
            // Delete associated images
            if ($tripTemplate->featured_image) {
                Storage::disk('public')->delete($tripTemplate->featured_image);
            }

            // Delete activity images
            $tripTemplate->activities->each(function ($activity) {
                if ($activity->image_url) {
                    Storage::disk('public')->delete($activity->image_url);
                }
            });

            $tripTemplate->delete();

            // Log the activity
            ActivityLog::log('trip_template_deleted', null, [
                'template_title' => $templateData['title'],
                'destination' => $tripTemplate->destination->name,
                'activities_count' => $activitiesCount,
                'deleted_by' => auth()->user()->name
            ], $templateData);

            DB::commit();

            return redirect()
                ->route('admin.trip-templates.index')
                ->with('success', 'Trip template and all associated activities deleted successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to delete trip template: ' . $e->getMessage());
        }
    }

    /**
     * Duplicate trip template
     */
    public function duplicate(TripTemplate $tripTemplate): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $newTemplate = $this->tripTemplateService->duplicateTemplate($tripTemplate);

            // Log the activity
            ActivityLog::log('trip_template_duplicated', $newTemplate, [
                'original_template' => $tripTemplate->title,
                'new_template' => $newTemplate->title,
                'activities_count' => $newTemplate->activities()->count(),
                'duplicated_by' => auth()->user()->name
            ]);

            DB::commit();

            return redirect()
                ->route('admin.trip-templates.show', $newTemplate)
                ->with('success', 'Trip template duplicated successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to duplicate trip template: ' . $e->getMessage());
        }
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured(TripTemplate $tripTemplate): RedirectResponse
    {
        $oldStatus = $tripTemplate->is_featured;
        $newStatus = !$oldStatus;

        $tripTemplate->update(['is_featured' => $newStatus]);

        // Log the activity
        ActivityLog::log('trip_template_featured_toggled', $tripTemplate, [
            'template_title' => $tripTemplate->title,
            'old_featured_status' => $oldStatus,
            'new_featured_status' => $newStatus,
            'updated_by' => auth()->user()->name
        ]);

        $message = $newStatus ? 'Trip template marked as featured!' : 'Featured status removed from trip template!';

        return back()->with('success', $message);
    }

    /**
     * Bulk actions for trip templates
     */
    public function bulkAction(Request $request): RedirectResponse
    {
        $request->validate([
            'action' => 'required|in:toggle_featured,delete',
            'template_ids' => 'required|array',
            'template_ids.*' => 'exists:trip_templates,id',
        ]);

        $templates = TripTemplate::whereIn('id', $request->template_ids)->get();
        $count = $templates->count();

        DB::beginTransaction();
        try {
            switch ($request->action) {
                case 'toggle_featured':
                    $templates->each(function ($template) {
                        $template->update(['is_featured' => !$template->is_featured]);
                    });
                    $message = "Featured status toggled for {$count} templates.";
                    break;

                case 'delete':
                    // Delete associated images
                    $templates->each(function ($template) {
                        if ($template->featured_image) {
                            Storage::disk('public')->delete($template->featured_image);
                        }
                        $template->activities->each(function ($activity) {
                            if ($activity->image_url) {
                                Storage::disk('public')->delete($activity->image_url);
                            }
                        });
                    });

                    $templates->each->delete();
                    $message = "{$count} templates deleted successfully.";
                    break;
            }

            // Log the bulk action
            ActivityLog::log('trip_templates_bulk_action', null, [
                'action' => $request->action,
                'template_ids' => $request->template_ids,
                'count' => $count,
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
