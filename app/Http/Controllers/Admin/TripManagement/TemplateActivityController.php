<?php

namespace App\Http\Controllers\Admin\TripManagement;

use App\Http\Controllers\Controller;
use App\Models\TripTemplate;
use App\Models\TemplateActivity;
use App\Models\ActivityLog;
use App\Http\Requests\Admin\TripManagement\StoreTemplateActivityRequest;
use App\Http\Requests\Admin\TripManagement\UpdateTemplateActivityRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class TemplateActivityController extends Controller
{
    /**
     * Show form for creating new activity
     */
    public function create(TripTemplate $tripTemplate): View
    {
        // Get existing activities for reference
        $existingActivities = $tripTemplate->activities()
            ->orderBy('day_number')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_number');

        return view('admin.template-activities.create', compact('tripTemplate', 'existingActivities'));
    }

    /**
     * Store newly created activity
     */
    public function store(StoreTemplateActivityRequest $request, TripTemplate $tripTemplate): RedirectResponse
    {
        $validated = $request->validated();
        $validated['trip_template_id'] = $tripTemplate->id;

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image_url'] = $request->file('image')->store('activities', 'public');
        }

        $activity = TemplateActivity::create($validated);

        // Log the activity
        ActivityLog::log('template_activity_created', $activity, [
            'activity_title' => $activity->title,
            'template_title' => $tripTemplate->title,
            'day_number' => $activity->day_number,
            'created_by' => auth()->user()->name
        ]);

        return redirect()
            ->route('admin.trip-templates.show', $tripTemplate)
            ->with('success', 'Activity added successfully!');
    }

    /**
     * Show form for editing activity
     */
    public function edit(TripTemplate $tripTemplate, TemplateActivity $activity): View
    {
        // Ensure the activity belongs to this template
        if ($activity->trip_template_id !== $tripTemplate->id) {
            abort(404);
        }

        // Get existing activities for reference (excluding current one)
        $existingActivities = $tripTemplate->activities()
            ->where('id', '!=', $activity->id)
            ->orderBy('day_number')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_number');

        return view('admin.template-activities.edit', compact('tripTemplate', 'activity', 'existingActivities'));
    }

    /**
     * Update specified activity
     */
    public function update(UpdateTemplateActivityRequest $request, TripTemplate $tripTemplate, TemplateActivity $activity): RedirectResponse
    {
        // Ensure the activity belongs to this template
        if ($activity->trip_template_id !== $tripTemplate->id) {
            abort(404);
        }

        $validated = $request->validated();

        // Store original data for logging
        $originalData = $activity->toArray();

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($activity->image_url) {
                Storage::disk('public')->delete($activity->image_url);
            }
            $validated['image_url'] = $request->file('image')->store('activities', 'public');
        }

        $activity->update($validated);

        // Log the activity
        ActivityLog::log('template_activity_updated', $activity, [
            'activity_title' => $activity->title,
            'template_title' => $tripTemplate->title,
            'updated_fields' => array_keys(array_diff_assoc($validated, $originalData)),
            'updated_by' => auth()->user()->name
        ], $originalData);

        return redirect()
            ->route('admin.trip-templates.show', $tripTemplate)
            ->with('success', 'Activity updated successfully!');
    }

    /**
     * Remove specified activity
     */
    public function destroy(TripTemplate $tripTemplate, TemplateActivity $activity): RedirectResponse
    {
        // Ensure the activity belongs to this template
        if ($activity->trip_template_id !== $tripTemplate->id) {
            abort(404);
        }

        // Store data for logging before deletion
        $activityData = $activity->toArray();

        // Delete image if exists
        if ($activity->image_url) {
            Storage::disk('public')->delete($activity->image_url);
        }

        $activity->delete();

        // Log the activity
        ActivityLog::log('template_activity_deleted', null, [
            'activity_title' => $activityData['title'],
            'template_title' => $tripTemplate->title,
            'day_number' => $activityData['day_number'],
            'deleted_by' => auth()->user()->name
        ], $activityData);

        return redirect()
            ->route('admin.trip-templates.show', $tripTemplate)
            ->with('success', 'Activity deleted successfully!');
    }

    /**
     * Duplicate activity to another day
     */
    public function duplicate(TripTemplate $tripTemplate, TemplateActivity $activity, Request $request): RedirectResponse
    {
        // Ensure the activity belongs to this template
        if ($activity->trip_template_id !== $tripTemplate->id) {
            abort(404);
        }

        $request->validate([
            'target_day' => 'required|integer|min:1|max:' . $tripTemplate->duration_days,
            'new_start_time' => 'required|date_format:H:i',
            'new_end_time' => 'required|date_format:H:i|after:new_start_time',
        ]);

        // Check for time conflicts on the target day
        $conflicts = $tripTemplate->activities()
            ->where('day_number', $request->target_day)
            ->where(function($query) use ($request) {
                $query->where(function($q) use ($request) {
                    // New activity starts during existing activity
                    $q->where('start_time', '<=', $request->new_start_time)
                      ->where('end_time', '>', $request->new_start_time);
                })->orWhere(function($q) use ($request) {
                    // New activity ends during existing activity
                    $q->where('start_time', '<', $request->new_end_time)
                      ->where('end_time', '>=', $request->new_end_time);
                })->orWhere(function($q) use ($request) {
                    // New activity completely encompasses existing activity
                    $q->where('start_time', '>=', $request->new_start_time)
                      ->where('end_time', '<=', $request->new_end_time);
                });
            })
            ->exists();

        if ($conflicts) {
            return back()->with('error', 'Time slot conflicts with existing activities on day ' . $request->target_day);
        }

        // Create duplicate activity
        $newActivity = $activity->replicate();
        $newActivity->day_number = $request->target_day;
        $newActivity->start_time = $request->new_start_time;
        $newActivity->end_time = $request->new_end_time;
        $newActivity->title = $activity->title . ' (Copy)';
        $newActivity->save();

        // Log the activity
        ActivityLog::log('template_activity_duplicated', $newActivity, [
            'original_activity' => $activity->title,
            'new_activity' => $newActivity->title,
            'from_day' => $activity->day_number,
            'to_day' => $request->target_day,
            'duplicated_by' => auth()->user()->name
        ]);

        return redirect()
            ->route('admin.trip-templates.show', $tripTemplate)
            ->with('success', 'Activity duplicated successfully to day ' . $request->target_day . '!');
    }

    /**
     * Bulk actions for activities
     */
    public function bulkAction(TripTemplate $tripTemplate, Request $request): RedirectResponse
    {
        $request->validate([
            'action' => 'required|in:move_day,toggle_optional,delete',
            'activity_ids' => 'required|array',
            'activity_ids.*' => 'exists:template_activities,id',
            'target_day' => 'nullable|integer|min:1|max:' . $tripTemplate->duration_days,
        ]);

        // Verify all activities belong to this template
        $activities = TemplateActivity::whereIn('id', $request->activity_ids)
            ->where('trip_template_id', $tripTemplate->id)
            ->get();

        if ($activities->count() !== count($request->activity_ids)) {
            return back()->with('error', 'Some activities do not belong to this template.');
        }

        DB::beginTransaction();
        try {
            switch ($request->action) {
                case 'move_day':
                    if (!$request->target_day) {
                        return back()->with('error', 'Target day is required for move operation.');
                    }
                    
                    $activities->each(function($activity) use ($request) {
                        $activity->update(['day_number' => $request->target_day]);
                    });
                    
                    $message = count($request->activity_ids) . ' activities moved to day ' . $request->target_day;
                    break;

                case 'toggle_optional':
                    $activities->each(function($activity) {
                        $activity->update(['is_optional' => !$activity->is_optional]);
                    });
                    
                    $message = 'Optional status toggled for ' . count($request->activity_ids) . ' activities';
                    break;

                case 'delete':
                    // Delete associated images
                    $activities->each(function($activity) {
                        if ($activity->image_url) {
                            Storage::disk('public')->delete($activity->image_url);
                        }
                    });
                    
                    $activities->each->delete();
                    
                    $message = count($request->activity_ids) . ' activities deleted successfully';
                    break;
            }

            // Log the bulk action
            ActivityLog::log('template_activities_bulk_action', null, [
                'action' => $request->action,
                'template_title' => $tripTemplate->title,
                'activity_ids' => $request->activity_ids,
                'count' => count($request->activity_ids),
                'target_day' => $request->target_day,
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