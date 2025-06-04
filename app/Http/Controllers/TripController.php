<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\TripTemplate;
use App\Models\TripInvitation;
use App\Models\Destination;
use App\Models\User;
use App\Mail\TripInvitationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class TripController extends Controller
{
    /**
     * Display a listing of trips
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            // Public trip browsing
            $trips = Trip::where('is_public', true)
                        ->with(['user', 'destinationModel'])
                        ->latest()
                        ->paginate(12);
            
            return view('trips.public-index', compact('trips'));
        }

        // User's trips and invited trips
        $query = Trip::where(function($q) use ($user) {
            $q->where('user_id', $user->id)
              ->orWhereHas('members', function($memberQuery) use ($user) {
                  $memberQuery->where('user_id', $user->id)
                             ->where('invitation_status', 'accepted');
              });
        });

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->has('type') && $request->type !== '') {
            $query->where('trip_type', $request->type);
        }

        // Search by destination or title
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('destination', 'like', "%{$search}%");
            });
        }

        $trips = $query->with(['user', 'destinationModel', 'members', 'savingsWallet'])
                      ->orderBy('start_date', 'desc')
                      ->paginate(12);

        // Get summary statistics
        $stats = [
            'total' => Trip::where('user_id', $user->id)->count(),
            'upcoming' => Trip::where('user_id', $user->id)->upcoming()->count(),
            'completed' => Trip::where('user_id', $user->id)->past()->count(),
            'planning' => Trip::where('user_id', $user->id)->byStatus('planning')->count(),
        ];

        return view('trips.index', compact('trips', 'stats'));
    }

    /**
     * Show the form for creating a new trip
     */
    public function create()
    {
        // Redirect to the trip planning flow
        return redirect()->route('trips.plan');
    }

    /**
     * Store a newly created trip
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'travelers' => 'required|integer|min:1|max:50',
            'budget' => 'nullable|numeric|min:0|max:999999',
            'description' => 'nullable|string|max:1000',
            'is_public' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            $trip = Trip::create([
                'user_id' => Auth::id(),
                'title' => $request->title,
                'description' => $request->description,
                'destination' => $request->destination,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'travelers' => $request->travelers,
                'budget' => $request->budget ?? 0,
                'total_cost' => 0,
                'trip_type' => 'self_planned',
                'status' => 'planning',
                'is_public' => $request->boolean('is_public', false),
            ]);

            // Create default itineraries
            $trip->createDefaultItineraries();

            DB::commit();

            Log::info('Trip created successfully', [
                'trip_id' => $trip->id,
                'user_id' => Auth::id(),
                'destination' => $trip->destination
            ]);

            return redirect()->route('trips.show', $trip)
                           ->with('success', 'Trip created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create trip', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'request_data' => $request->all()
            ]);

            return back()->withInput()
                        ->with('error', 'Failed to create trip. Please try again.');
        }
    }

    /**
     * Display the specified trip
     */
    public function show(Trip $trip)
    {
        // Check if user can view this trip
        if (!$trip->canBeViewedBy(Auth::user())) {
            abort(403, 'You do not have permission to view this trip.');
        }

        $trip->load([
            'user',
            'destinationModel',
            'itineraries.activities',
            'members.user',
            'savingsWallet',
            'tripTemplate'
        ]);

        // Get pending invitations if user is trip owner
        $pendingInvitations = null;
        if (Auth::check() && Auth::id() === $trip->user_id) {
            $pendingInvitations = TripInvitation::where('trip_id', $trip->id)
                                               ->pending()
                                               ->get();
        }

        // Check if current user is a member
        $userMembership = null;
        if (Auth::check()) {
            $userMembership = $trip->members()
                                  ->where('user_id', Auth::id())
                                  ->first();
        }

        return view('trips.show', compact('trip', 'pendingInvitations', 'userMembership'));
    }

    /**
     * Show the form for editing the specified trip
     */
    public function edit(Trip $trip)
    {
        $this->authorize('update', $trip);

        $destinations = Destination::orderBy('name')->get();
        
        return view('trips.edit', compact('trip', 'destinations'));
    }

    /**
     * Update the specified trip
     */
    public function update(Request $request, Trip $trip)
    {
        $this->authorize('update', $trip);

        $request->validate([
            'title' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'travelers' => 'required|integer|min:1|max:50',
            'budget' => 'nullable|numeric|min:0|max:999999',
            'description' => 'nullable|string|max:1000',
            'is_public' => 'boolean',
            'status' => ['nullable', Rule::in(['planning', 'confirmed', 'ongoing', 'completed', 'cancelled'])],
        ]);

        try {
            DB::beginTransaction();

            $oldDates = [
                'start_date' => $trip->start_date,
                'end_date' => $trip->end_date
            ];

            $trip->update([
                'title' => $request->title,
                'description' => $request->description,
                'destination' => $request->destination,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'travelers' => $request->travelers,
                'budget' => $request->budget ?? 0,
                'is_public' => $request->boolean('is_public', false),
                'status' => $request->status ?? $trip->status,
            ]);

            // If dates changed, update itineraries
            if ($oldDates['start_date'] != $trip->start_date || $oldDates['end_date'] != $trip->end_date) {
                $this->updateItinerariesForNewDates($trip, $oldDates);
            }

            DB::commit();

            Log::info('Trip updated successfully', [
                'trip_id' => $trip->id,
                'user_id' => Auth::id(),
                'changes' => $trip->getChanges()
            ]);

            return redirect()->route('trips.show', $trip)
                           ->with('success', 'Trip updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update trip', [
                'trip_id' => $trip->id,
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return back()->withInput()
                        ->with('error', 'Failed to update trip. Please try again.');
        }
    }

    /**
     * Remove the specified trip
     */
    public function destroy(Trip $trip)
    {
        $this->authorize('delete', $trip);

        try {
            DB::beginTransaction();

            // Cancel all pending invitations
            TripInvitation::where('trip_id', $trip->id)
                         ->where('status', 'pending')
                         ->update(['status' => 'cancelled']);

            $tripTitle = $trip->title;
            $trip->delete();

            DB::commit();

            Log::info('Trip deleted successfully', [
                'trip_id' => $trip->id,
                'user_id' => Auth::id(),
                'title' => $tripTitle
            ]);

            return redirect()->route('trips.index')
                           ->with('success', "Trip '{$tripTitle}' has been deleted.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete trip', [
                'trip_id' => $trip->id,
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return back()->with('error', 'Failed to delete trip. Please try again.');
        }
    }

    /**
     * Send invitations for a trip
     */
    public function sendInvitations(Request $request, Trip $trip)
    {
        $this->authorize('update', $trip);

        $request->validate([
            'invitations' => 'required|array|min:1|max:20',
            'invitations.*.email' => 'required|email|max:255',
            'invitations.*.name' => 'nullable|string|max:255',
            'invitations.*.message' => 'nullable|string|max:500',
            'invitations.*.role' => 'nullable|in:member,organizer,viewer'
        ]);

        $sentCount = 0;
        $errors = [];

        try {
            DB::beginTransaction();

            foreach ($request->invitations as $inviteData) {
                try {
                    // Check if invitation already exists for this email
                    $existing = TripInvitation::where('trip_id', $trip->id)
                                            ->where('email', strtolower($inviteData['email']))
                                            ->whereIn('status', ['pending', 'accepted'])
                                            ->first();

                    if ($existing) {
                        $errors[] = "Invitation already exists for {$inviteData['email']}";
                        continue;
                    }

                    // Don't allow inviting the trip owner
                    if (strtolower($inviteData['email']) === strtolower(Auth::user()->email)) {
                        $errors[] = "Cannot invite yourself";
                        continue;
                    }

                    // Create invitation
                    $invitation = TripInvitation::createInvitation(
                        $trip,
                        $inviteData['email'],
                        $inviteData['name'] ?? null,
                        $inviteData['message'] ?? null,
                        $inviteData['role'] ?? 'member',
                        Auth::user()
                    );

                    // Send email
                    Mail::to($invitation->email)->send(new TripInvitationMail($invitation));
                    $sentCount++;

                } catch (\Exception $e) {
                    $errors[] = "Failed to send invitation to {$inviteData['email']}: " . $e->getMessage();
                    Log::error('Failed to send individual invitation', [
                        'trip_id' => $trip->id,
                        'email' => $inviteData['email'],
                        'error' => $e->getMessage()
                    ]);
                }
            }

            DB::commit();

            if ($sentCount > 0) {
                $message = "Successfully sent {$sentCount} invitation(s).";
                if (!empty($errors)) {
                    $message .= ' Some invitations failed: ' . implode(', ', $errors);
                }
                return back()->with('success', $message);
            }

            return back()->with('error', 'No invitations were sent. ' . implode(', ', $errors));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to send invitations', [
                'trip_id' => $trip->id,
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return back()->with('error', 'Failed to send invitations. Please try again.');
        }
    }

    /**
     * Show trip invitations management
     */
    public function invitations(Trip $trip)
    {
        $this->authorize('update', $trip);

        $invitations = TripInvitation::where('trip_id', $trip->id)
                                   ->with(['user', 'invitedBy'])
                                   ->orderBy('created_at', 'desc')
                                   ->get();

        return view('trips.invitations', compact('trip', 'invitations'));
    }

    /**
     * Browse trip templates
     */
    public function browseTemplates(Request $request)
    {
        $query = TripTemplate::with(['destination', 'activities']);

        // Filter by destination
        if ($request->has('destination') && $request->destination !== '') {
            $query->whereHas('destination', function($q) use ($request) {
                $q->where('name', 'like', "%{$request->destination}%");
            });
        }

        // Filter by price range
        if ($request->has('min_price') && $request->min_price !== '') {
            $query->where('base_price', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price !== '') {
            $query->where('base_price', '<=', $request->max_price);
        }

        // Filter by duration
        if ($request->has('duration') && $request->duration !== '') {
            $query->where('duration_days', $request->duration);
        }

        // Filter by difficulty
        if ($request->has('difficulty') && $request->difficulty !== '') {
            $query->where('difficulty_level', $request->difficulty);
        }

        // Sort options
        $sort = $request->get('sort', 'featured');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('base_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('base_price', 'desc');
                break;
            case 'duration':
                $query->orderBy('duration_days', 'asc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('is_featured', 'desc')->orderBy('base_price', 'asc');
        }

        $templates = $query->paginate(12)->withQueryString();

        $destinations = Destination::has('tripTemplates')->orderBy('name')->get();

        return view('trips.templates.index', compact('templates', 'destinations'));
    }

    /**
     * Show a specific trip template
     */
    public function showTemplate(TripTemplate $template)
    {
        $template->load([
            'destination',
            'activities' => function($query) {
                $query->orderBy('day_number')->orderBy('start_time');
            }
        ]);

        // Group activities by day
        $activitiesByDay = $template->activities()
                                  ->where('is_optional', false)
                                  ->orderBy('day_number')
                                  ->orderBy('start_time')
                                  ->get()
                                  ->groupBy('day_number');

        // Get optional activities
        $optionalActivities = $template->activities()
                                     ->where('is_optional', true)
                                     ->orderBy('day_number')
                                     ->orderBy('start_time')
                                     ->get();

        return view('trips.templates.show', compact('template', 'activitiesByDay', 'optionalActivities'));
    }

    /**
     * Create trip from session data (after login/register)
     */
    public function createFromSession()
    {
        if (!Auth::check() || !Session::get('trip_data_not_saved')) {
            return redirect()->route('trips.index');
        }

        try {
            DB::beginTransaction();

            // Create trip from session data
            $trip = $this->createTripFromSessionData();

            // Process invitations from session
            $invites = Session::get('trip_invites', []);
            if (!empty($invites)) {
                $this->processSessionInvitations($trip, $invites);
            }

            // Clear session data
            $this->clearTripPlanningSession();

            DB::commit();

            Log::info('Trip created from session successfully', [
                'trip_id' => $trip->id,
                'user_id' => Auth::id()
            ]);

            Session::put('newly_created_trip_id', $trip->id);
            Session::flash('success', 'Your trip has been created successfully!');

            return redirect()->route('trips.show', $trip);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create trip from session', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'session_data' => Session::all()
            ]);

            return redirect()->route('trips.plan')
                           ->with('error', 'There was an error creating your trip. Please try again.');
        }
    }

    /**
     * Duplicate a trip
     */
    public function duplicate(Trip $trip)
    {
        $this->authorize('view', $trip);

        try {
            DB::beginTransaction();

            $newTrip = $trip->replicate();
            $newTrip->user_id = Auth::id();
            $newTrip->title = $trip->title . ' (Copy)';
            $newTrip->status = 'planning';
            $newTrip->start_date = null;
            $newTrip->end_date = null;
            $newTrip->save();

            // Copy itineraries and activities
            foreach ($trip->itineraries as $itinerary) {
                $newItinerary = $itinerary->replicate();
                $newItinerary->trip_id = $newTrip->id;
                $newItinerary->date = null;
                $newItinerary->save();

                foreach ($itinerary->activities as $activity) {
                    $newActivity = $activity->replicate();
                    $newActivity->itinerary_id = $newItinerary->id;
                    $newActivity->created_by = Auth::id();
                    $newActivity->save();
                }
            }

            DB::commit();

            return redirect()->route('trips.edit', $newTrip)
                           ->with('success', 'Trip duplicated successfully! Please update the dates and details.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to duplicate trip', [
                'original_trip_id' => $trip->id,
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return back()->with('error', 'Failed to duplicate trip. Please try again.');
        }
    }

    // ==================== PRIVATE HELPER METHODS ====================

    /**
     * Create trip from session data
     */
    private function createTripFromSessionData(): Trip
    {
        $tripType = Session::get('selected_trip_type');
        $destination = Session::get('selected_destination');
        $tripDetails = Session::get('trip_details', []);
        $templateId = Session::get('selected_trip_template');

        // Validate required data
        if (!$tripType || !$destination) {
            throw new \Exception('Missing required trip data');
        }

        // Get destination info
        $destinationName = is_array($destination) ? $destination['name'] : $destination;
        $destinationCountry = is_array($destination) ? ($destination['country'] ?? '') : '';

        // Prepare trip data
        $tripData = [
            'user_id' => Auth::id(),
            'title' => $tripDetails['title'] ?? "Trip to {$destinationName}",
            'description' => $tripDetails['description'] ?? '',
            'destination' => $destinationName,
            'destination_country' => $destinationCountry,
            'start_date' => $tripDetails['start_date'] ?? now()->addWeeks(2)->format('Y-m-d'),
            'end_date' => $tripDetails['end_date'] ?? now()->addWeeks(3)->format('Y-m-d'),
            'travelers' => $tripDetails['travelers'] ?? 1,
            'budget' => $tripDetails['budget'] ?? 0,
            'total_cost' => $tripDetails['total_cost'] ?? 0,
            'trip_type' => $tripType,
            'status' => 'planning',
            'is_public' => false,
        ];

        // Handle pre-planned trips
        if ($tripType === 'pre_planned' && $templateId) {
            $template = TripTemplate::find($templateId);
            if ($template) {
                $tripData['trip_template_id'] = $template->id;
                $tripData['total_cost'] = Session::get('trip_total_price', $template->base_price);
                $tripData['budget'] = max($tripData['budget'], $tripData['total_cost']);
                $tripData['description'] = $template->description;
            }
        }

        // Create the trip
        $trip = Trip::create($tripData);

        // Create itineraries and activities
        $this->createTripItineraries($trip, $tripType);

        return $trip;
    }

    /**
     * Create itineraries and activities for the trip
     */
    private function createTripItineraries(Trip $trip, string $tripType)
    {
        $startDate = Carbon::parse($trip->start_date);
        $endDate = Carbon::parse($trip->end_date);
        $totalDays = $startDate->diffInDays($endDate) + 1;

        if ($tripType === 'pre_planned') {
            $this->createPrePlannedItineraries($trip, $totalDays, $startDate);
        } else {
            $this->createSelfPlannedItineraries($trip, $totalDays, $startDate);
        }
    }

    /**
     * Create itineraries for pre-planned trips
     */
    private function createPrePlannedItineraries(Trip $trip, int $totalDays, Carbon $startDate)
    {
        $template = $trip->tripTemplate;
        if (!$template) {
            $trip->createDefaultItineraries();
            return;
        }

        $selectedOptionalActivities = Session::get('selected_optional_activities', []);

        // Group template activities by day
        $activitiesByDay = $template->activities()
                                  ->where('is_optional', false)
                                  ->get()
                                  ->groupBy('day_number');

        // Get optional activities
        $optionalActivities = $template->activities()
                                     ->where('is_optional', true)
                                     ->get()
                                     ->keyBy('id');

        for ($day = 1; $day <= $totalDays; $day++) {
            $date = $startDate->copy()->addDays($day - 1);
            
            $itinerary = $trip->itineraries()->create([
                'title' => 'Day ' . $day,
                'description' => 'Day ' . $day . ' activities in ' . $trip->destination,
                'day_number' => $day,
                'date' => $date,
            ]);

            // Add regular activities for this day
            if (isset($activitiesByDay[$day])) {
                foreach ($activitiesByDay[$day] as $templateActivity) {
                    $itinerary->activities()->create([
                        'title' => $templateActivity->title,
                        'description' => $templateActivity->description,
                        'location' => $templateActivity->location,
                        'start_time' => $templateActivity->start_time,
                        'end_time' => $templateActivity->end_time,
                        'cost' => $templateActivity->cost,
                        'category' => $templateActivity->category,
                        'type' => $templateActivity->category,
                        'is_optional' => false,
                        'is_highlight' => $templateActivity->is_highlight,
                        'created_by' => $trip->user_id,
                    ]);
                }
            }

            // Add selected optional activities for this day
            foreach ($selectedOptionalActivities as $activityId => $activityData) {
                if (isset($optionalActivities[$activityId])) {
                    $optionalActivity = $optionalActivities[$activityId];
                    if ($optionalActivity->day_number == $day) {
                        $itinerary->activities()->create([
                            'title' => $optionalActivity->title,
                            'description' => $optionalActivity->description,
                            'location' => $optionalActivity->location,
                            'start_time' => $optionalActivity->start_time,
                            'end_time' => $optionalActivity->end_time,
                            'cost' => $optionalActivity->cost,
                            'category' => $optionalActivity->category,
                            'type' => $optionalActivity->category,
                            'is_optional' => true,
                            'is_highlight' => $optionalActivity->is_highlight,
                            'created_by' => $trip->user_id,
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Create itineraries for self-planned trips
     */
    private function createSelfPlannedItineraries(Trip $trip, int $totalDays, Carbon $startDate)
    {
        $tripActivities = Session::get('trip_activities', []);

        for ($day = 1; $day <= $totalDays; $day++) {
            $date = $startDate->copy()->addDays($day - 1);
            
            $itinerary = $trip->itineraries()->create([
                'title' => 'Day ' . $day,
                'description' => 'Day ' . $day . ' activities in ' . $trip->destination,
                'day_number' => $day,
                'date' => $date,
            ]);

            // Add activities for this day
            if (isset($tripActivities[$day])) {
                foreach ($tripActivities[$day] as $activity) {
                    $itinerary->activities()->create([
                        'title' => $activity['title'],
                        'description' => $activity['description'] ?? '',
                        'location' => $activity['location'] ?? '',
                        'start_time' => $activity['start_time'] ?? null,
                        'end_time' => $activity['end_time'] ?? null,
                        'cost' => $activity['cost'] ?? 0,
                        'category' => $activity['category'] ?? 'activity',
                        'type' => $activity['category'] ?? 'activity',
                        'is_optional' => false,
                        'is_highlight' => false,
                        'created_by' => $trip->user_id,
                    ]);
                }
            }
        }
    }

    /**
     * Process invitations from session data
     */
    private function processSessionInvitations(Trip $trip, array $invites)
    {
        foreach ($invites as $invite) {
            if (!isset($invite['email']) || !filter_var($invite['email'], FILTER_VALIDATE_EMAIL)) {
                continue;
            }

            try {
                $invitation = TripInvitation::createInvitation(
                    $trip,
                    $invite['email'],
                    $invite['name'] ?? null,
                    $invite['message'] ?? null,
                    'member',
                    Auth::user()
                );

                // Send invitation email
                Mail::to($invitation->email)->send(new TripInvitationMail($invitation));

            } catch (\Exception $e) {
                Log::error("Failed to send invitation to {$invite['email']}", [
                    'trip_id' => $trip->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Update itineraries when trip dates change
     */
    private function updateItinerariesForNewDates(Trip $trip, array $oldDates)
    {
        $newStartDate = Carbon::parse($trip->start_date);
        $newEndDate = Carbon::parse($trip->end_date);
        $newDuration = $newStartDate->diffInDays($newEndDate) + 1;

        $existingItineraries = $trip->itineraries()->orderBy('day_number')->get();

        // Update existing itineraries
        foreach ($existingItineraries as $index => $itinerary) {
            $dayNumber = $index + 1;
            
            if ($dayNumber <= $newDuration) {
                // Update the date for this itinerary
                $newDate = $newStartDate->copy()->addDays($index);
                $itinerary->update([
                    'date' => $newDate,
                    'day_number' => $dayNumber
                ]);
            } else {
                // Remove itineraries beyond the new duration
                $itinerary->delete();
            }
        }

      // Create additional itineraries if needed
        $existingCount = $existingItineraries->count();
        if ($newDuration > $existingCount) {
            for ($day = $existingCount + 1; $day <= $newDuration; $day++) {
                $date = $newStartDate->copy()->addDays($day - 1);
                
                $trip->itineraries()->create([
                    'title' => 'Day ' . $day,
                    'description' => 'Day ' . $day . ' activities in ' . $trip->destination,
                    'day_number' => $day,
                    'date' => $date,
                ]);
            }
        }
    }

    /**
     * Clear trip planning session data
     */
    private function clearTripPlanningSession()
    {
        Session::forget([
            'trip_data_not_saved',
            'selected_trip_type',
            'selected_trip_template',
            'selected_destination',
            'trip_details',
            'trip_activities',
            'trip_invites',
            'trip_base_price',
            'trip_total_price',
            'selected_optional_activities'
        ]);
    }

    // ==================== ADDITIONAL PUBLIC METHODS ====================

    /**
     * Join a public trip
     */
    public function join(Request $request, Trip $trip)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                           ->with('message', 'Please login to join this trip.');
        }

        if (!$trip->is_public) {
            abort(403, 'This trip is not open for public joining.');
        }

        $user = Auth::user();

        // Check if user is already a member
        $existingMembership = $trip->members()
                                  ->where('user_id', $user->id)
                                  ->first();

        if ($existingMembership) {
            return back()->with('info', 'You are already a member of this trip.');
        }

        // Check if trip is full
        if ($trip->members()->count() >= $trip->travelers) {
            return back()->with('error', 'This trip is already full.');
        }

        try {
            // Create membership
            $trip->members()->create([
                'user_id' => $user->id,
                'role' => 'member',
                'invitation_status' => 'accepted'
            ]);

            Log::info('User joined public trip', [
                'user_id' => $user->id,
                'trip_id' => $trip->id
            ]);

            return back()->with('success', 'You have successfully joined this trip!');

        } catch (\Exception $e) {
            Log::error('Failed to join trip', [
                'user_id' => $user->id,
                'trip_id' => $trip->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Failed to join trip. Please try again.');
        }
    }

    /**
     * Leave a trip
     */
    public function leave(Trip $trip)
    {
        if (!Auth::check()) {
            abort(401);
        }

        $user = Auth::user();

        // Trip owner cannot leave their own trip
        if ($trip->user_id === $user->id) {
            return back()->with('error', 'Trip organizer cannot leave their own trip.');
        }

        $membership = $trip->members()->where('user_id', $user->id)->first();

        if (!$membership) {
            return back()->with('error', 'You are not a member of this trip.');
        }

        try {
            $membership->delete();

            Log::info('User left trip', [
                'user_id' => $user->id,
                'trip_id' => $trip->id
            ]);

            return redirect()->route('trips.index')
                           ->with('success', 'You have left the trip.');

        } catch (\Exception $e) {
            Log::error('Failed to leave trip', [
                'user_id' => $user->id,
                'trip_id' => $trip->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Failed to leave trip. Please try again.');
        }
    }

    /**
     * Export trip details
     */
    public function export(Trip $trip, $format = 'pdf')
    {
        $this->authorize('view', $trip);

        $trip->load([
            'user',
            'destinationModel',
            'itineraries.activities',
            'members.user'
        ]);

        switch ($format) {
            case 'pdf':
                return $this->exportToPdf($trip);
            case 'ical':
                return $this->exportToIcal($trip);
            case 'json':
                return $this->exportToJson($trip);
            default:
                abort(400, 'Invalid export format');
        }
    }

    /**
     * Mark trip as favorite
     */
    public function toggleFavorite(Trip $trip)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Authentication required'], 401);
        }

        // This would require a favorites table/relationship
        // For now, just return success
        return response()->json(['success' => true, 'favorited' => true]);
    }

    /**
     * Get trip statistics for dashboard
     */
    public function statistics()
    {
        if (!Auth::check()) {
            abort(401);
        }

        $user = Auth::user();
        
        $stats = [
            'total_trips' => Trip::where('user_id', $user->id)->count(),
            'upcoming_trips' => Trip::where('user_id', $user->id)->upcoming()->count(),
            'completed_trips' => Trip::where('user_id', $user->id)->past()->count(),
            'countries_visited' => Trip::where('user_id', $user->id)
                                     ->whereNotNull('destination_country')
                                     ->distinct('destination_country')
                                     ->count(),
            'total_budget' => Trip::where('user_id', $user->id)->sum('budget'),
            'total_spent' => Trip::where('user_id', $user->id)->sum('total_cost'),
            'invited_trips' => $user->tripMembers()->accepted()->count(),
            'pending_invitations' => TripInvitation::where('email', $user->email)
                                                  ->pending()
                                                  ->count()
        ];

        return response()->json($stats);
    }

    /**
     * Search trips
     */
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2|max:100',
            'type' => 'nullable|in:public,private,all',
            'limit' => 'nullable|integer|min:1|max:50'
        ]);

        $query = $request->q;
        $type = $request->get('type', 'public');
        $limit = $request->get('limit', 10);

        $tripQuery = Trip::where(function($q) use ($query) {
            $q->where('title', 'like', "%{$query}%")
              ->orWhere('destination', 'like', "%{$query}%")
              ->orWhere('description', 'like', "%{$query}%");
        });

        // Apply visibility filters
        if ($type === 'public') {
            $tripQuery->where('is_public', true);
        } elseif ($type === 'private' && Auth::check()) {
            $user = Auth::user();
            $tripQuery->where(function($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhereHas('members', function($memberQuery) use ($user) {
                      $memberQuery->where('user_id', $user->id)
                                 ->where('invitation_status', 'accepted');
                  });
            });
        } elseif ($type === 'all') {
            if (Auth::check()) {
                $user = Auth::user();
                $tripQuery->where(function($q) use ($user) {
                    $q->where('is_public', true)
                      ->orWhere('user_id', $user->id)
                      ->orWhereHas('members', function($memberQuery) use ($user) {
                          $memberQuery->where('user_id', $user->id)
                                     ->where('invitation_status', 'accepted');
                      });
                });
            } else {
                $tripQuery->where('is_public', true);
            }
        }

        $trips = $tripQuery->with(['user', 'destinationModel'])
                          ->orderBy('start_date', 'desc')
                          ->limit($limit)
                          ->get();

        return response()->json([
            'trips' => $trips->map(function($trip) {
                return [
                    'id' => $trip->id,
                    'title' => $trip->title,
                    'destination' => $trip->destination,
                    'start_date' => $trip->start_date?->format('M j, Y'),
                    'end_date' => $trip->end_date?->format('M j, Y'),
                    'duration' => $trip->duration_days,
                    'travelers' => $trip->travelers,
                    'budget' => $trip->formatted_budget,
                    'organizer' => $trip->user->name,
                    'is_public' => $trip->is_public,
                    'url' => route('trips.show', $trip)
                ];
            }),
            'total' => $trips->count()
        ]);
    }

    /**
     * Bulk operations on trips
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,archive,publish,unpublish',
            'trip_ids' => 'required|array|min:1|max:50',
            'trip_ids.*' => 'exists:trips,id'
        ]);

        if (!Auth::check()) {
            abort(401);
        }

        $user = Auth::user();
        $tripIds = $request->trip_ids;
        $action = $request->action;

        // Verify user owns all trips
        $trips = Trip::whereIn('id', $tripIds)
                    ->where('user_id', $user->id)
                    ->get();

        if ($trips->count() !== count($tripIds)) {
            return back()->with('error', 'You can only perform bulk actions on your own trips.');
        }

        try {
            DB::beginTransaction();

            $successCount = 0;

            foreach ($trips as $trip) {
                switch ($action) {
                    case 'delete':
                        $trip->delete();
                        $successCount++;
                        break;
                    case 'publish':
                        $trip->update(['is_public' => true]);
                        $successCount++;
                        break;
                    case 'unpublish':
                        $trip->update(['is_public' => false]);
                        $successCount++;
                        break;
                    case 'archive':
                        $trip->update(['status' => 'archived']);
                        $successCount++;
                        break;
                }
            }

            DB::commit();

            $actionPast = [
                'delete' => 'deleted',
                'publish' => 'published',
                'unpublish' => 'unpublished',
                'archive' => 'archived'
            ][$action];

            return back()->with('success', "{$successCount} trip(s) {$actionPast} successfully.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk action failed', [
                'user_id' => $user->id,
                'action' => $action,
                'trip_ids' => $tripIds,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Bulk action failed. Please try again.');
        }
    }

    // ==================== PRIVATE EXPORT METHODS ====================

    /**
     * Export trip to PDF
     */
    private function exportToPdf(Trip $trip)
    {
        // This would require a PDF library like dompdf or wkhtmltopdf
        // For now, return a simple response
        return response()->json([
            'message' => 'PDF export would be implemented here',
            'trip' => $trip->title
        ]);
    }

    /**
     * Export trip to iCal format
     */
    private function exportToIcal(Trip $trip)
    {
        $ical = "BEGIN:VCALENDAR\r\n";
        $ical .= "VERSION:2.0\r\n";
        $ical .= "PRODID:-//Trip Planner//Trip Export//EN\r\n";
        $ical .= "BEGIN:VEVENT\r\n";
        $ical .= "UID:" . $trip->id . "@tripplanner.com\r\n";
        $ical .= "DTSTART:" . $trip->start_date->format('Ymd') . "\r\n";
        $ical .= "DTEND:" . $trip->end_date->format('Ymd') . "\r\n";
        $ical .= "SUMMARY:" . $trip->title . "\r\n";
        $ical .= "DESCRIPTION:Trip to " . $trip->destination . "\r\n";
        $ical .= "LOCATION:" . $trip->destination . "\r\n";
        $ical .= "END:VEVENT\r\n";
        $ical .= "END:VCALENDAR\r\n";

        return response($ical)
            ->header('Content-Type', 'text/calendar')
            ->header('Content-Disposition', 'attachment; filename="' . $trip->title . '.ics"');
    }

    /**
     * Export trip to JSON
     */
    private function exportToJson(Trip $trip)
    {
        $data = [
            'trip' => [
                'id' => $trip->id,
                'title' => $trip->title,
                'description' => $trip->description,
                'destination' => $trip->destination,
                'start_date' => $trip->start_date?->toDateString(),
                'end_date' => $trip->end_date?->toDateString(),
                'travelers' => $trip->travelers,
                'budget' => $trip->budget,
                'total_cost' => $trip->total_cost,
                'status' => $trip->status,
                'created_at' => $trip->created_at?->toISOString()
            ],
            'itineraries' => $trip->itineraries->map(function($itinerary) {
                return [
                    'day' => $itinerary->day_number,
                    'date' => $itinerary->date?->toDateString(),
                    'title' => $itinerary->title,
                    'activities' => $itinerary->activities->map(function($activity) {
                        return [
                            'title' => $activity->title,
                            'description' => $activity->description,
                            'location' => $activity->location,
                            'start_time' => $activity->start_time,
                            'end_time' => $activity->end_time,
                            'cost' => $activity->cost,
                            'category' => $activity->category,
                            'is_optional' => $activity->is_optional
                        ];
                    })
                ];
            }),
            'members' => $trip->members->map(function($member) {
                return [
                    'name' => $member->user?->name ?? 'Guest',
                    'email' => $member->invitation_email,
                    'role' => $member->role,
                    'status' => $member->invitation_status
                ];
            })
        ];

        return response()->json($data)
                         ->header('Content-Disposition', 'attachment; filename="' . $trip->title . '.json"');
    }

    // ==================== API METHODS ====================

    /**
     * API endpoint for trip data
     */
    public function apiShow(Trip $trip)
    {
        if (!$trip->canBeViewedBy(Auth::user())) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'trip' => $trip->load([
                'user:id,name',
                'destinationModel:id,name,country',
                'itineraries.activities',
                'members.user:id,name'
            ])
        ]);
    }

    /**
     * API endpoint for updating trip
     */
    public function apiUpdate(Request $request, Trip $trip)
    {
        $this->authorize('update', $trip);

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string|max:1000',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after:start_date',
            'budget' => 'sometimes|nullable|numeric|min:0',
            'is_public' => 'sometimes|boolean',
            'status' => 'sometimes|in:planning,confirmed,ongoing,completed,cancelled'
        ]);

        try {
            $trip->update($request->only([
                'title', 'description', 'start_date', 'end_date', 
                'budget', 'is_public', 'status'
            ]));

            return response()->json([
                'message' => 'Trip updated successfully',
                'trip' => $trip->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update trip',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}