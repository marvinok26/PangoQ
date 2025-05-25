<?php

namespace App\Policies;

use App\Models\Trip;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TripPolicy
{
    /**
     * Determine whether the user can view the trip.
     */
    public function view(User $user, Trip $trip): Response
    {
        // Log for debugging
        logger()->info('Trip view authorization check', [
            'user_id' => $user->id,
            'trip_id' => $trip->id,
            'trip_creator_id' => $trip->creator_id,
            'is_creator' => $user->id === $trip->creator_id
        ]);

        // User can view if they are the creator
        if ($user->id === $trip->creator_id) {
            return Response::allow();
        }

        // User can view if they are an accepted member
        $isMember = $trip->members()
            ->where('user_id', $user->id)
            ->where('invitation_status', 'accepted')
            ->exists();
            
        return $isMember 
            ? Response::allow()
            : Response::deny('You are not authorized to view this trip.');
    }

    /**
     * Determine whether the user can update the trip.
     */
    public function update(User $user, Trip $trip): Response
    {
        // User can update if they are the creator
        if ($user->id === $trip->creator_id) {
            return Response::allow();
        }

        // User can update if they are an organizer member
        $isOrganizer = $trip->members()
            ->where('user_id', $user->id)
            ->where('role', 'organizer')
            ->where('invitation_status', 'accepted')
            ->exists();
            
        return $isOrganizer 
            ? Response::allow()
            : Response::deny('You are not authorized to update this trip.');
    }

    /**
     * Determine whether the user can delete the trip.
     */
    public function delete(User $user, Trip $trip): Response
    {
        // Only the creator can delete the trip
        return $user->id === $trip->creator_id
            ? Response::allow()
            : Response::deny('Only the trip creator can delete this trip.');
    }

    /**
     * Determine whether the user can invite others to the trip.
     */
    public function invite(User $user, Trip $trip): Response
    {
        // Creator can always invite
        if ($user->id === $trip->creator_id) {
            return Response::allow();
        }

        // Organizers can invite
        $isOrganizer = $trip->members()
            ->where('user_id', $user->id)
            ->where('role', 'organizer')
            ->where('invitation_status', 'accepted')
            ->exists();
            
        return $isOrganizer 
            ? Response::allow()
            : Response::deny('Only trip organizers can send invitations.');
    }
}