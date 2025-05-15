<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SettingsController extends Controller
{
    /**
     * Display general settings.
     */
    public function general(): View
    {
        $user = Auth::user();
        
        // Get available languages
        $languages = [
            'en' => 'English',
            'fr' => 'French',
            'es' => 'Spanish',
            'sw' => 'Swahili'
        ];
        
        // Get available timezones
        $timezones = [
            'UTC' => 'UTC',
            'Africa/Nairobi' => 'Nairobi',
            'America/New_York' => 'New York',
            'Europe/London' => 'London',
            'Asia/Tokyo' => 'Tokyo'
        ];
        
        return view('livewire.pages.settings.general', compact('user', 'languages', 'timezones'));
    }

    /**
     * Update general settings.
     */
    public function updateGeneral(Request $request)
    {
        $user = Auth::user();
        
        // Validate request
        $validated = $request->validate([
            'language' => 'required|string|in:en,fr,es,sw',
            'timezone' => 'required|string',
            'currency' => 'required|string|in:USD,EUR,KES,GBP',
        ]);
        
        // Update session settings
        session(['language' => $validated['language']]);
        session(['timezone' => $validated['timezone']]);
        session(['currency' => $validated['currency']]);
        
        return redirect()->route('settings.general')
            ->with('success', 'General settings updated successfully!');
    }

    /**
     * Display privacy settings.
     */
    public function privacy(): View
    {
        $user = Auth::user();
        return view('livewire.pages.settings.privacy', compact('user'));
    }

    /**
     * Update privacy settings.
     */
    public function updatePrivacy(Request $request)
    {
        $user = Auth::user();
        
        // Validate request
        $validated = $request->validate([
            'profile_visibility' => 'required|string|in:public,friends,private',
            'show_activity' => 'nullable|boolean',
            'show_trips' => 'nullable|boolean',
            'allow_friend_requests' => 'nullable|boolean',
        ]);
        
        // TODO: Update user privacy settings based on your database structure
        
        return redirect()->route('settings.privacy')
            ->with('success', 'Privacy settings updated successfully!');
    }

    /**
     * Display payment settings.
     */
    public function payments(): View
    {
        $user = Auth::user();
        
        // Get user's payment methods
        // For demonstration, using empty array
        $paymentMethods = [];
        
        return view('livewire.pages.settings.payments', compact('user', 'paymentMethods'));
    }

    /**
     * Update payment settings.
     */
    public function updatePayments(Request $request)
    {
        $user = Auth::user();
        
        // Validate request
        $validated = $request->validate([
            'default_payment_method' => 'nullable|string',
            'auto_contribute' => 'nullable|boolean',
        ]);
        
        // TODO: Update user payment settings based on your database structure
        
        return redirect()->route('settings.payments')
            ->with('success', 'Payment settings updated successfully!');
    }
}