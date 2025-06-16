<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Redirect the user to the OAuth Provider.
     *
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToProvider($provider)
    {
        // If we're in the middle of trip planning, save session data to special key
        if (
            session()->has('selected_trip_type') || 
            session()->has('selected_destination') || 
            session()->has('trip_details')
        ) {
            $tripDataKeys = [
                'selected_trip_type',
                'selected_destination',
                'selected_trip_template',
                'trip_details',
                'trip_activities',
                'trip_invites',
                'selected_optional_activities',
                'trip_total_price',
                'trip_current_step'
            ];
            
            $tripData = [];
            foreach ($tripDataKeys as $key) {
                if (session()->has($key)) {
                    $tripData[$key] = session($key);
                }
            }
            
            if (!empty($tripData)) {
                // Flag that we have trip data to save after authentication
                $tripData['trip_data_not_saved'] = true;
                
                // Store in a special session key that won't be lost during social auth
                session(['social_auth_trip_data' => $tripData]);
                Log::info('Stored trip data before social auth redirect', ['keys' => array_keys($tripData)]);
            }
        }
        
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from provider.
     *
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleProviderCallback($provider)
    {
        try {
            $providerUser = Socialite::driver($provider)->user();
            
            // Check if the user already exists in our database
            $user = User::where('email', $providerUser->getEmail())->first();
            
            // If user doesn't exist, create a new one
            if (!$user) {
                $user = User::create([
                    'name' => $providerUser->getName(),
                    'email' => $providerUser->getEmail(),
                    'password' => Hash::make(substr(md5(rand()), 0, 16)), // Random password
                    'auth_provider' => $provider,
                    'auth_provider_id' => $providerUser->getId(),
                    'profile_photo_path' => $providerUser->getAvatar(),
                    'email_verified_at' => now() // Social accounts are considered verified
                ]);
                
                Log::info('New user created via social auth', [
                    'user_id' => $user->id,
                    'provider' => $provider,
                    'has_trip_data' => session()->has('social_auth_trip_data')
                ]);
            } 
            // If user exists but not linked to this provider, update provider info
            else if (!$user->auth_provider_id || $user->auth_provider_id !== $providerUser->getId()) {
                $user->update([
                    'auth_provider' => $provider,
                    'auth_provider_id' => $providerUser->getId(),
                ]);
                
                // Update profile photo if not set
                if (!$user->profile_photo_path) {
                    $user->update(['profile_photo_path' => $providerUser->getAvatar()]);
                }
                
                Log::info('Existing user linked to social provider', [
                    'user_id' => $user->id,
                    'provider' => $provider
                ]);
            }
            
            // Login the user
            Auth::login($user);
            
            // If social_auth_trip_data session exists, restore it
            if (session()->has('social_auth_trip_data')) {
                $tripData = session('social_auth_trip_data');
                
                foreach ($tripData as $key => $value) {
                    session([$key => $value]);
                }
                
                session()->forget('social_auth_trip_data');
                Log::info('Restored trip data in social auth callback', [
                    'user_id' => $user->id,
                    'restored_keys' => array_keys($tripData)
                ]);
                
                // Set flash message to inform user their data was preserved
                session()->flash('success', 'Welcome! Your trip planning progress has been preserved.');
            }
            
            // Determine redirect URL
            $redirectUrl = session()->pull('url.intended', RouteServiceProvider::HOME);
            
            // If user has trip data, redirect to trip planning page
            if (session()->has('selected_trip_type')) {
                $redirectUrl = route('trips.plan');
            }
            
            return redirect($redirectUrl);
            
        } catch (\Exception $e) {
            Log::error('Social authentication error: ' . $e->getMessage(), [
                'provider' => $provider,
                'error' => $e->getMessage(),
                'has_trip_data' => session()->has('social_auth_trip_data')
            ]);
            
            // If we have trip data, preserve it even on error
            if (session()->has('social_auth_trip_data')) {
                $tripData = session('social_auth_trip_data');
                foreach ($tripData as $key => $value) {
                    session([$key => $value]);
                }
                session()->forget('social_auth_trip_data');
                
                return redirect()->route('trips.plan')
                    ->with('error', 'Authentication failed, but your trip planning progress has been preserved. Please try logging in again.');
            }
            
            return redirect()->route('login')->with('error', 'Authentication failed. Please try again.');
        }
    }
}