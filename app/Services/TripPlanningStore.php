<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TripPlanningStore
{
    /**
     * Store trip data in session
     */
    public function storeTripData(array $data): void
    {
        foreach ($data as $key => $value) {
            Session::put($key, $value);
        }
        
        // Mark that we have unsaved data
        Session::put('trip_data_not_saved', true);
        
        Log::info('Trip data stored in session', ['keys' => array_keys($data)]);
    }

    /**
     * Get all trip data from session
     */
    public function getTripData(): array
    {
        return [
            'selected_trip_type' => Session::get('selected_trip_type'),
            'selected_destination' => Session::get('selected_destination'),
            'selected_trip_template' => Session::get('selected_trip_template'),
            'trip_details' => Session::get('trip_details'),
            'trip_activities' => Session::get('trip_activities'),
            'trip_invites' => Session::get('trip_invites'),
            'selected_optional_activities' => Session::get('selected_optional_activities'),
            'trip_total_price' => Session::get('trip_total_price'),
            'trip_base_price' => Session::get('trip_base_price'),
            'recent_searches' => Session::get('recent_searches', [])
        ];
    }

    /**
     * Clear trip planning data
     */
    public function clearTripData(): void
    {
        Session::forget([
            'selected_trip_type',
            'selected_destination', 
            'selected_trip_template',
            'trip_details',
            'trip_activities',
            'trip_invites',
            'selected_optional_activities',
            'trip_total_price',
            'trip_base_price',
            'trip_data_not_saved',
            'trip_creation_pending'
        ]);

        // Keep recent_searches for better UX
        
        Log::info('Trip planning data cleared');
    }

    /**
     * Restore trip data from array (used by Alpine.js restoration)
     */
    public function restoreTripData(array $data): void
    {
        if (!Auth::check()) {
            Log::warning('Attempted to restore trip data without authentication');
            return;
        }

        $validKeys = [
            'selected_trip_type',
            'selected_destination',
            'selected_trip_template', 
            'trip_details',
            'trip_activities',
            'trip_invites',
            'selected_optional_activities',
            'trip_total_price',
            'trip_base_price',
            'recent_searches'
        ];

        $restoredCount = 0;

        foreach ($data as $key => $value) {
            if (in_array($key, $validKeys) && !Session::has($key)) {
                Session::put($key, $value);
                $restoredCount++;
            }
        }

        if ($restoredCount > 0) {
            Session::put('trip_data_not_saved', true);
            Log::info('Trip data restored from storage', [
                'restored_keys' => $restoredCount,
                'user_id' => Auth::id()
            ]);
        }
    }

    /**
     * Check if user has unsaved trip data
     */
    public function hasUnsavedTripData(): bool
    {
        return Session::get('trip_data_not_saved', false) || 
               Session::get('trip_creation_pending', false) ||
               $this->hasMinimumTripData();
    }

    /**
     * Check if we have minimum data for trip creation
     */
    public function hasMinimumTripData(): bool
    {
        return Session::has('selected_trip_type') && 
               (Session::has('selected_destination') || Session::has('selected_trip_template'));
    }

    /**
     * Get trip planning progress (0-100)
     */
    public function getTripProgress(): int
    {
        $progress = 0;
        
        // Step 1: Trip type (20%)
        if (Session::has('selected_trip_type')) {
            $progress += 20;
        }
        
        // Step 2: Destination or Template (20%) 
        if (Session::has('selected_destination') || Session::has('selected_trip_template')) {
            $progress += 20;
        }
        
        // Step 3: Trip details (20%)
        if (Session::has('trip_details')) {
            $progress += 20;
        }
        
        // Step 4: Activities/Itinerary (20%)
        if (Session::has('trip_activities') || Session::has('selected_optional_activities')) {
            $progress += 20;
        }
        
        // Step 5: Invites (20%)
        if (Session::has('trip_invites')) {
            $progress += 20;
        }
        
        return $progress;
    }

    /**
     * Get current step based on completed data
     */
    public function getCurrentStep(): int
    {
        $tripType = Session::get('selected_trip_type');
        
        if (!$tripType) {
            return 0; // Trip type selection
        }
        
        if ($tripType === 'pre_planned') {
            // Pre-planned flow: Type -> Template -> Invites -> Review
            if (!Session::has('selected_trip_template')) return 1;
            if (!Session::has('trip_invites')) return 2;
            return 3; // Review
        } else {
            // Self-planned flow: Type -> Destination -> Details -> Itinerary -> Invites -> Review
            if (!Session::has('selected_destination')) return 1;
            if (!Session::has('trip_details')) return 2;
            if (!Session::has('trip_activities')) return 3;
            if (!Session::has('trip_invites')) return 4;
            return 5; // Review
        }
    }

    /**
     * Mark trip for creation after login
     */
    public function markForCreation(): void
    {
        if ($this->hasMinimumTripData()) {
            Session::put('trip_creation_pending', true);
            Log::info('Trip marked for creation', [
                'has_session' => Auth::check(),
                'progress' => $this->getTripProgress()
            ]);
        }
    }

    /**
     * Get trip data summary for debugging
     */
    public function getDataSummary(): array
    {
        return [
            'has_minimum_data' => $this->hasMinimumTripData(),
            'has_unsaved_data' => $this->hasUnsavedTripData(),
            'progress' => $this->getTripProgress(),
            'current_step' => $this->getCurrentStep(),
            'trip_type' => Session::get('selected_trip_type'),
            'has_destination' => Session::has('selected_destination'),
            'has_template' => Session::has('selected_trip_template'),
            'has_details' => Session::has('trip_details'),
            'has_activities' => Session::has('trip_activities'),
            'has_invites' => Session::has('trip_invites'),
            'creation_pending' => Session::get('trip_creation_pending', false)
        ];
    }
}