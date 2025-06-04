<?php

namespace App\Policies;

use App\Models\TripInvitation;
use App\Models\User;

class TripInvitationPolicy
{
    /**
     * Determine if the user can view the invitation
     */
    public function view(?User $user, TripInvitation $invitation): bool
    {
        return true; // Anyone with the token can view
    }

    /**
     * Determine if the user can accept the invitation
     */
    public function accept(?User $user, TripInvitation $invitation): bool
    {
        if (!$invitation->can_be_accepted) {
            return false;
        }

        // If no user is logged in, allow (they'll be redirected to register/login)
        if (!$user) {
            return true;
        }

        // User's email must match invitation email
        return strtolower($user->email) === strtolower($invitation->email);
    }

    /**
     * Determine if the user can manage (resend/cancel) the invitation
     */
    public function manage(User $user, TripInvitation $invitation): bool
    {
        // Only trip organizer or invitation sender can manage
        return $user->id === $invitation->trip->user_id || 
               $user->id === $invitation->invited_by;
    }
}