<?php

namespace App\Http\Controllers;

use App\Models\TripInvitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TripInvitationController extends Controller
{
    /**
     * Show invitation details
     */
    public function show(string $token)
    {
        $invitation = TripInvitation::findByToken($token);
        
        if (!$invitation) {
            abort(404, 'Invitation not found');
        }

        if (!$invitation->can_be_accepted) {
            return view('trip-invitations.expired', compact('invitation'));
        }

        return view('trip-invitations.show', compact('invitation'));
    }

    /**
     * Accept invitation
     */
    public function accept(Request $request, string $token)
    {
        $invitation = TripInvitation::findByToken($token);
        
        if (!$invitation || !$invitation->can_be_accepted) {
            return redirect()->route('trip-invitations.show', $token)
                           ->with('error', 'This invitation is no longer valid.');
        }

        $user = Auth::user();
        
        // If user is not logged in and doesn't have an account, redirect to register
        if (!$user) {
            $existingUser = User::where('email', $invitation->email)->first();
            if (!$existingUser) {
                session(['invitation_token' => $token]);
                return redirect()->route('register')
                               ->with('email', $invitation->email)
                               ->with('name', $invitation->name)
                               ->with('message', 'Please create an account to accept this trip invitation.');
            } else {
                session(['invitation_token' => $token]);
                return redirect()->route('login')
                               ->with('email', $invitation->email)
                               ->with('message', 'Please login to accept this trip invitation.');
            }
        }

        // Check if logged-in user's email matches invitation
        if (strtolower($user->email) !== strtolower($invitation->email)) {
            return redirect()->route('trip-invitations.show', $token)
                           ->with('error', 'This invitation was sent to a different email address.');
        }

        // Accept the invitation
        if ($invitation->accept($user)) {
            return redirect()->route('trips.show', $invitation->trip)
                           ->with('success', 'You have successfully joined the trip!');
        }

        return redirect()->route('trip-invitations.show', $token)
                       ->with('error', 'Unable to accept invitation. Please try again.');
    }

    /**
     * Decline invitation
     */
    public function decline(string $token)
    {
        $invitation = TripInvitation::findByToken($token);
        
        if (!$invitation || !$invitation->can_be_accepted) {
            return redirect()->route('trip-invitations.show', $token)
                           ->with('error', 'This invitation is no longer valid.');
        }

        if ($invitation->decline()) {
            return view('trip-invitations.declined', compact('invitation'));
        }

        return redirect()->route('trip-invitations.show', $token)
                       ->with('error', 'Unable to decline invitation. Please try again.');
    }

    /**
     * Resend invitation (for trip organizers)
     */
    public function resend(TripInvitation $invitation)
    {
        $this->authorize('update', $invitation->trip);

        $invitation->resend();

        return back()->with('success', 'Invitation resent to ' . $invitation->email);
    }


/**
     * Cancel invitation (for trip organizers)
     */
    public function cancel(TripInvitation $invitation)
    {
        $this->authorize('update', $invitation->trip);

        $invitation->delete();

        return back()->with('success', 'Invitation cancelled for ' . $invitation->email);
    }
}