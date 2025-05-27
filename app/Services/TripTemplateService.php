<?php

namespace App\Services;

use App\Models\TripTemplate;
use App\Models\TemplateActivity;
use Illuminate\Support\Facades\Storage;

class TripTemplateService
{
    /**
     * Calculate statistics for a trip template
     */
    public function calculateTemplateStats(TripTemplate $tripTemplate): array
    {
        $activities = $tripTemplate->activities;
        
        return [
            'total_activities' => $activities->count(),
            'optional_activities' => $activities->where('is_optional', true)->count(),
            'highlight_activities' => $activities->where('is_highlight', true)->count(),
            'total_activity_cost' => $activities->sum('cost'),
            'optional_activity_cost' => $activities->where('is_optional', true)->sum('cost'),
            'activities_by_day' => $activities->groupBy('day_number')->map->count(),
            'activities_by_time' => [
                'morning' => $activities->where('time_of_day', 'morning')->count(),
                'afternoon' => $activities->where('time_of_day', 'afternoon')->count(),
                'evening' => $activities->where('time_of_day', 'evening')->count(),
            ],
            'cost_breakdown' => [
                'base_price' => $tripTemplate->base_price,
                'included_activities' => $activities->where('is_optional', false)->sum('cost'),
                'optional_activities' => $activities->where('is_optional', true)->sum('cost'),
                'total_with_all_optional' => $tripTemplate->base_price + $activities->sum('cost'),
                'total_without_optional' => $tripTemplate->base_price + $activities->where('is_optional', false)->sum('cost'),
            ]
        ];
    }

    /**
     * Duplicate a trip template with all its activities
     */
    public function duplicateTemplate(TripTemplate $originalTemplate): TripTemplate
    {
        // Create new template
        $newTemplate = $originalTemplate->replicate();
        $newTemplate->title = $originalTemplate->title . ' (Copy)';
        $newTemplate->is_featured = false; // Don't duplicate featured status
        
        // Handle featured image duplication
        if ($originalTemplate->featured_image) {
            $oldImagePath = $originalTemplate->featured_image;
            $extension = pathinfo($oldImagePath, PATHINFO_EXTENSION);
            $newImagePath = 'trip-templates/' . uniqid() . '.' . $extension;
            
            if (Storage::disk('public')->exists($oldImagePath)) {
                Storage::disk('public')->copy($oldImagePath, $newImagePath);
                $newTemplate->featured_image = $newImagePath;
            }
        }
        
        $newTemplate->save();

        // Duplicate all activities
        $originalTemplate->activities->each(function($activity) use ($newTemplate) {
            $this->duplicateActivity($activity, $newTemplate);
        });

        return $newTemplate;
    }

    /**
     * Duplicate a single activity to a template
     */
    public function duplicateActivity(TemplateActivity $originalActivity, TripTemplate $targetTemplate): TemplateActivity
    {
        $newActivity = $originalActivity->replicate();
        $newActivity->trip_template_id = $targetTemplate->id;
        
        // Handle image duplication
        if ($originalActivity->image_url) {
            $oldImagePath = $originalActivity->image_url;
            $extension = pathinfo($oldImagePath, PATHINFO_EXTENSION);
            $newImagePath = 'activities/' . uniqid() . '.' . $extension;
            
            if (Storage::disk('public')->exists($oldImagePath)) {
                Storage::disk('public')->copy($oldImagePath, $newImagePath);
                $newActivity->image_url = $newImagePath;
            }
        }
        
        $newActivity->save();
        
        return $newActivity;
    }

    /**
     * Validate template completeness
     */
    public function validateTemplateCompleteness(TripTemplate $tripTemplate): array
    {
        $issues = [];
        $warnings = [];
        
        // Check if template has activities
        $activitiesCount = $tripTemplate->activities()->count();
        if ($activitiesCount === 0) {
            $issues[] = 'Template has no activities';
        }
        
        // Check if all days have activities
        $daysWithActivities = $tripTemplate->activities()
            ->distinct('day_number')
            ->count();
            
        if ($daysWithActivities < $tripTemplate->duration_days) {
            $emptyDays = $tripTemplate->duration_days - $daysWithActivities;
            $warnings[] = "{$emptyDays} day(s) have no activities scheduled";
        }
        
        // Check for time conflicts
        $timeConflicts = $this->checkTimeConflicts($tripTemplate);
        if (!empty($timeConflicts)) {
            $issues = array_merge($issues, $timeConflicts);
        }
        
        // Check pricing
        if ($tripTemplate->base_price <= 0) {
            $warnings[] = 'Base price is not set or is zero';
        }
        
        // Check for highlights
        $highlightCount = $tripTemplate->activities()->where('is_highlight', true)->count();
        if ($highlightCount === 0) {
            $warnings[] = 'No highlight activities defined';
        }
        
        // Check for optional activities
        $optionalCount = $tripTemplate->activities()->where('is_optional', true)->count();
        if ($optionalCount === 0) {
            $warnings[] = 'No optional activities available for customization';
        }
        
        return [
            'is_complete' => empty($issues),
            'issues' => $issues,
            'warnings' => $warnings,
            'score' => $this->calculateCompletenessScore($tripTemplate, $issues, $warnings)
        ];
    }

    /**
     * Check for time conflicts in activities
     */
    private function checkTimeConflicts(TripTemplate $tripTemplate): array
    {
        $conflicts = [];
        
        // Group activities by day
        $activitiesByDay = $tripTemplate->activities()
            ->orderBy('day_number')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_number');
        
        foreach ($activitiesByDay as $day => $activities) {
            for ($i = 0; $i < $activities->count() - 1; $i++) {
                $current = $activities[$i];
                $next = $activities[$i + 1];
                
                // Check if current activity ends after next activity starts
                if ($current->end_time > $next->start_time) {
                    $conflicts[] = "Day {$day}: '{$current->title}' conflicts with '{$next->title}'";
                }
            }
        }
        
        return $conflicts;
    }

    /**
     * Calculate completeness score (0-100)
     */
    private function calculateCompletenessScore(TripTemplate $tripTemplate, array $issues, array $warnings): int
    {
        $score = 100;
        
        // Deduct points for issues (critical problems)
        $score -= count($issues) * 20;
        
        // Deduct points for warnings (minor problems)
        $score -= count($warnings) * 5;
        
        // Bonus points for good practices
        if ($tripTemplate->description && strlen($tripTemplate->description) > 100) {
            $score += 5; // Good description
        }
        
        if (!empty($tripTemplate->highlights_array)) {
            $score += 5; // Has highlights
        }
        
        if ($tripTemplate->featured_image) {
            $score += 5; // Has featured image
        }
        
        $activityImagesCount = $tripTemplate->activities()->whereNotNull('image_url')->count();
        $totalActivities = $tripTemplate->activities()->count();
        
        if ($totalActivities > 0 && ($activityImagesCount / $totalActivities) > 0.5) {
            $score += 10; // More than 50% of activities have images
        }
        
        // Ensure score is between 0 and 100
        return max(0, min(100, $score));
    }

    /**
     * Generate template preview data for user-facing components
     */
    public function generateTemplatePreview(TripTemplate $tripTemplate): array
    {
        $activities = $tripTemplate->activities()
            ->orderBy('day_number')
            ->orderBy('start_time')
            ->get();
        
        $stats = $this->calculateTemplateStats($tripTemplate);
        
        return [
            'id' => $tripTemplate->id,
            'title' => $tripTemplate->title,
            'description' => $tripTemplate->description,
            'destination' => [
                'name' => $tripTemplate->destination->name,
                'country' => $tripTemplate->destination->country,
                'city' => $tripTemplate->destination->city,
                'image_url' => $tripTemplate->destination->image_url 
                    ? asset('storage/' . $tripTemplate->destination->image_url) 
                    : null,
            ],
            'duration_days' => $tripTemplate->duration_days,
            'base_price' => $tripTemplate->base_price,
            'difficulty_level' => $tripTemplate->difficulty_level,
            'trip_style' => $tripTemplate->trip_style,
            'highlights' => $tripTemplate->highlights_array ?? [],
            'featured_image' => $tripTemplate->featured_image 
                ? asset('storage/' . $tripTemplate->featured_image) 
                : null,
            'is_featured' => $tripTemplate->is_featured,
            'statistics' => $stats,
            'activities_by_day' => $activities->groupBy('day_number')->map(function($dayActivities) {
                return $dayActivities->map(function($activity) {
                    return [
                        'id' => $activity->id,
                        'title' => $activity->title,
                        'description' => $activity->description,
                        'location' => $activity->location,
                        'start_time' => $activity->start_time,
                        'end_time' => $activity->end_time,
                        'cost' => $activity->cost,
                        'category' => $activity->category,
                        'time_of_day' => $activity->time_of_day,
                        'is_optional' => $activity->is_optional,
                        'is_highlight' => $activity->is_highlight,
                        'image_url' => $activity->image_url 
                            ? asset('storage/' . $activity->image_url) 
                            : null,
                    ];
                });
            }),
            'optional_activities' => $activities->where('is_optional', true)->map(function($activity) {
                return [
                    'id' => $activity->id,
                    'title' => $activity->title,
                    'cost' => $activity->cost,
                    'day_number' => $activity->day_number,
                ];
            })->values(),
        ];
    }
}