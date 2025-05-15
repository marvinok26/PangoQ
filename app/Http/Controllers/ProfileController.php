<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the profile.
     */
    public function edit(): View
    {
        $user = Auth::user();
        return view('livewire.pages.profile.edit', compact('user'));
    }

    /**
     * Update the user's profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        // Validate request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:20',
            'profile_photo' => 'nullable|image|max:1024',
        ]);
        
        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $validated['profile_photo'] = $path;
        }
        
        // Update user
        $user->update($validated);
        
        return redirect()->route('profile.edit')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Show security settings page.
     */
    public function security(): View
    {
        return view('livewire.pages.profile.security');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        
        // Validate request
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        // Update password
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);
        
        return redirect()->route('profile.security')
            ->with('success', 'Password updated successfully!');
    }

    /**
     * Show notifications settings page.
     */
    public function notifications(): View
    {
        return view('livewire.pages.profile.notifications');
    }

    /**
     * Update notification preferences.
     */
    public function updateNotifications(Request $request)
    {
        $user = Auth::user();
        
        // Validate request
        $validated = $request->validate([
            'email_notifications' => 'nullable|boolean',
            'trip_updates' => 'nullable|boolean',
            'payment_notifications' => 'nullable|boolean',
            'marketing_emails' => 'nullable|boolean',
        ]);
        
        // TODO: Update user notification preferences based on your database structure
        
        return redirect()->route('profile.notifications')
            ->with('success', 'Notification preferences updated successfully!');
    }

    /**
     * Remove the user's account.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password',
        ]);
        
        $user = Auth::user();
        
        Auth::logout();
        
        $user->delete();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}