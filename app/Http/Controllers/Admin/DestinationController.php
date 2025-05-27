<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class DestinationController extends Controller
{
    /**
     * Display listing of destinations
     */
    public function index(Request $request): View
    {
        $query = Destination::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('country', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by country
        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }

        $destinations = $query->withCount('tripTemplates')
            ->latest()
            ->paginate(12);

        // Get unique countries for filter dropdown
        $countries = Destination::distinct()
            ->pluck('country')
            ->filter()
            ->sort()
            ->values();

        return view('admin.destinations.index', compact('destinations', 'countries'));
    }

    /**
     * Show form for creating new destination
     */
    public function create(): View
    {
        return view('admin.destinations.create');
    }

    /**
     * Store newly created destination
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:destinations,name',
            'country' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image_url'] = $request->file('image')->store('destinations', 'public');
        }

        $destination = Destination::create($validated);

        // Log the activity
        ActivityLog::log('destination_created', $destination, [
            'destination_name' => $destination->name,
            'country' => $destination->country,
            'created_by' => auth()->user()->name
        ]);

        return redirect()
            ->route('admin.destinations.index')
            ->with('success', 'Destination created successfully!');
    }

    /**
     * Display specified destination
     */
    public function show(Destination $destination): View
    {
        $destination->load(['tripTemplates' => function($query) {
            $query->withCount('activities')->latest();
        }]);

        return view('admin.destinations.show', compact('destination'));
    }

    /**
     * Show form for editing destination
     */
    public function edit(Destination $destination): View
    {
        return view('admin.destinations.edit', compact('destination'));
    }

    /**
     * Update specified destination
     */
public function update(Request $request, Destination $destination): RedirectResponse
{
    $validated = $request->validate([
        'name' => 'required|string|max:255|unique:destinations,name,' . $destination->id,
        'country' => 'required|string|max:255',
        'city' => 'required|string|max:255',
        'description' => 'nullable|string|max:1000',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Store original data for logging
    $originalData = $destination->toArray();

    if ($request->hasFile('image')) {
        // Delete old image ONLY if it's an uploaded file (not seeded)
        $oldImageUrl = $destination->getRawOriginal('image_url');
        if ($oldImageUrl && !str_starts_with($oldImageUrl, 'image')) {
            // It's an uploaded file, safe to delete
            Storage::disk('public')->delete($oldImageUrl);
        }
        
        // Store new image
        $validated['image_url'] = $request->file('image')->store('destinations', 'public');
    }

    $destination->update($validated);

    // Log the activity
    ActivityLog::log('destination_updated', $destination, [
        'destination_name' => $destination->name,
        'updated_fields' => array_keys(array_diff_assoc($validated, $originalData)),
        'updated_by' => auth()->user()->name
    ], $originalData);

    return redirect()
        ->route('admin.destinations.index')
        ->with('success', 'Destination updated successfully!');
}

    /**
     * Remove specified destination
     */
    public function destroy(Destination $destination): RedirectResponse
    {
        // Check if destination has trip templates
        if ($destination->tripTemplates()->count() > 0) {
            return redirect()
                ->route('admin.destinations.index')
                ->with('error', 'Cannot delete destination with existing trip templates. Please delete or reassign the templates first.');
        }

        // Store data for logging before deletion
        $destinationData = $destination->toArray();

        // Delete image if exists
        if ($destination->image_url) {
            Storage::disk('public')->delete($destination->image_url);
        }

        $destination->delete();

        // Log the activity
        ActivityLog::log('destination_deleted', null, [
            'destination_name' => $destinationData['name'],
            'country' => $destinationData['country'],
            'deleted_by' => auth()->user()->name
        ], $destinationData);

        return redirect()
            ->route('admin.destinations.index')
            ->with('success', 'Destination deleted successfully!');
    }
}