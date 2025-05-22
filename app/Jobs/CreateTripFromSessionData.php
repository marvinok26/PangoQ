<?php

namespace App\Jobs;

use App\Models\Trip;
use App\Models\TripMember;
use App\Models\Itinerary;
use App\Models\Activity;
use App\Models\SavingsWallet;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CreateTripFromSessionData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $tripData;

    /**
     * Create a new job instance.
     */
    public function __construct(array $tripData)
    {
        $this->tripData = $tripData;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $user = User::find($this->tripData['user_id']);
            if (!$user) {
                Log::error('User not found for trip creation', ['user_id' => $this->tripData['user_id']]);
                return;
            }

            // Create the trip
            $trip = $this->createTrip($user);
            
            // Create savings wallet
            $this->createSavingsWallet($trip);

            // Create itineraries
            if ($this->tripData['trip_type'] === 'pre_planned' && $this->tripData['template_id']) {
                $this->createItinerariesFromTemplate($trip);
            } elseif ($this->tripData['trip_activities']) {
                $this->createItinerariesFromSessionData($trip);
            } else {
                $this->createEmptyItineraries($trip);
            }

            // Process invites
            if ($this->tripData['trip_invites']) {
                $this->processInvites($trip);
            }

            // Add creator as member
            TripMember::create([
                'trip_id' => $trip->id,
                'user_id' => $user->id,
                'role' => 'organizer',
                'invitation_status' => 'accepted'
            ]);

            Log::info('Trip created successfully in background', [
                'trip_id' => $trip->id,
                'user_id' => $user->id
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating trip from session data', [
                'error' => $e->getMessage(),
                'user_id' => $this->tripData['user_id'] ?? null,
                'trace' => $e->getTraceAsString()
            ]);
            
            // Optionally, you could notify the user of the failure
            // or retry the job
            throw $e;
        }
    }

    /**
     * Create the main trip record
     */
    private function createTrip(User $user): Trip
    {
        $trip = new Trip();
        $trip->creator_id = $user->id;
        $trip->planning_type = $this->tripData['trip_type'];

        // Set template if pre-planned
        if ($this->tripData['trip_type'] === 'pre_planned' && $this->tripData['template_id']) {
            $trip->trip_template_id = $this->tripData['template_id'];
        }

        // Set destination
        if ($this->tripData['destination']) {
            $trip->destination = $this->tripData['destination']['name'];
        }

        // Set trip details
        $tripDetails = $this->tripData['trip_details'] ?? [];
        $trip->title = $tripDetails['title'] ?? ('Trip to ' . ($this->tripData['destination']['name'] ?? 'Unknown'));
        $trip->description = $tripDetails['description'] ?? null;
        $trip->start_date = $tripDetails['start_date'] ?? Carbon::now()->addWeeks(2);
        $trip->end_date = $tripDetails['end_date'] ?? Carbon::now()->addWeeks(3);
        $trip->budget = $tripDetails['budget'] ?? $this->tripData['trip_total_price'] ?? null;
        $trip->total_cost = $tripDetails['total_cost'] ?? $this->tripData['trip_total_price'] ?? $trip->budget;
        $trip->status = 'planning';

        // Save selected optional activities if pre-planned
        if ($this->tripData['selected_optional_activities']) {
            $trip->selected_optional_activities = json_encode($this->tripData['selected_optional_activities']);
        }

        $trip->save();
        return $trip;
    }

    /**
     * Create savings wallet for the trip
     */
    private function createSavingsWallet(Trip $trip): void
    {
        SavingsWallet::create([
            'trip_id' => $trip->id,
            'name' => ['en' => 'Savings for ' . $trip->title],
            'minimum_goal' => $trip->budget ?? 0,
            'current_amount' => 0,
            'target_date' => $trip->start_date,
            'contribution_frequency' => 'weekly',
            'currency' => 'USD',
        ]);
    }

    /**
     * Create itineraries from template
     */
    private function createItinerariesFromTemplate(Trip $trip): void
    {
        if (method_exists($trip, 'createItinerariesFromTemplate')) {
            $trip->createItinerariesFromTemplate();
        }
    }

    /**
     * Create itineraries from session data
     */
    private function createItinerariesFromSessionData(Trip $trip): void
    {
        $startDate = Carbon::parse($trip->start_date);
        $activities = $this->tripData['trip_activities'];

        foreach ($activities as $day => $dayActivities) {
            $date = clone $startDate;
            $date->addDays($day - 1);

            $itinerary = Itinerary::create([
                'trip_id' => $trip->id,
                'title' => "Day $day: " . $trip->destination,
                'description' => "Itinerary for day $day in " . $trip->destination,
                'day_number' => $day,
                'date' => $date,
            ]);

            foreach ($dayActivities as $activityData) {
                Activity::create([
                    'itinerary_id' => $itinerary->id,
                    'title' => $activityData['title'],
                    'description' => $activityData['description'] ?? null,
                    'location' => $activityData['location'] ?? null,
                    'start_time' => $activityData['start_time'] ?? null,
                    'end_time' => $activityData['end_time'] ?? null,
                    'cost' => $activityData['cost'] ?? null,
                    'created_by' => $trip->creator_id,
                    'category' => $activityData['category'] ?? 'activity',
                    'is_optional' => $activityData['is_optional'] ?? false,
                    'is_highlight' => $activityData['is_highlight'] ?? false,
                ]);
            }
        }
    }

    /**
     * Create empty itineraries based on trip duration
     */
    private function createEmptyItineraries(Trip $trip): void
    {
        $startDate = Carbon::parse($trip->start_date);
        $endDate = Carbon::parse($trip->end_date);
        $days = $startDate->diffInDays($endDate) + 1;

        for ($day = 1; $day <= $days; $day++) {
            $date = clone $startDate;
            $date->addDays($day - 1);

            Itinerary::create([
                'trip_id' => $trip->id,
                'title' => "Day $day: " . $trip->destination,
                'description' => "Itinerary for day $day in " . $trip->destination,
                'day_number' => $day,
                'date' => $date,
            ]);
        }
    }

    /**
     * Process trip invites
     */
    private function processInvites(Trip $trip): void
    {
        foreach ($this->tripData['trip_invites'] as $invite) {
            $email = $invite['email'] ?? null;
            if (!$email) continue;

            $user = User::where('email', $email)->first();

            TripMember::create([
                'trip_id' => $trip->id,
                'user_id' => $user ? $user->id : null,
                'invitation_email' => $user ? null : $email,
                'role' => 'member',
                'invitation_status' => 'pending'
            ]);
        }
    }
}