<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Redirect to the given provider's authentication page
     *
     * @param string $provider
     * @return mixed
     */
    public function authProviderRedirect($provider)
    {
        try {
            if (in_array($provider, ['google', 'facebook'])) {
                // Store trip data flag in the session before social redirection
                if (session('trip_data_not_saved')) {
                    // Save trip planning data in a specific key that won't be lost in redirections
                    // Some providers like Facebook can lose session data during OAuth
                    $tripData = [
                        'trip_data_not_saved' => session('trip_data_not_saved'),
                        'selected_trip_type' => session('selected_trip_type'),
                        'selected_destination' => session('selected_destination'),
                        'selected_trip_template' => session('selected_trip_template'),
                        'trip_details' => session('trip_details'),
                        'trip_activities' => session('trip_activities'),
                        'trip_invites' => session('trip_invites'),
                    ];
                    
                    // Store trip data in a persistent session variable
                    session(['social_auth_trip_data' => $tripData]);
                }
                
                return Socialite::driver($provider)->redirect();
            }
            return redirect()->route('login')->with('error', 'Invalid authentication provider.');
        } catch (Exception $e) {
            Log::error('Social auth redirect error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Could not connect to ' . ucfirst($provider) . '. Please try again.');
        }
    }

    /**
     * Handle the callback from social authentication providers
     *
     * @param string $provider
     * @return RedirectResponse
     */
    public function socialAuthentication($provider)
    {
        try {
            if (!in_array($provider, ['google', 'facebook'])) {
                return redirect()->route('login')->with('error', 'Invalid authentication provider.');
            }
            
            $socialUser = Socialite::driver($provider)->user();
            
            // Find user by provider ID first
            $user = User::where('auth_provider_id', $socialUser->id)
                        ->where('auth_provider', $provider)
                        ->first();
            
            // If user not found by provider ID, try to find by email
            if (!$user) {
                $user = User::where('email', $socialUser->email)->first();
                
                // If user exists with the same email, update their provider details
                if ($user) {
                    $user->update([
                        'auth_provider' => $provider,
                        'auth_provider_id' => $socialUser->id,
                    ]);
                } else {
                    // Create a new user
                    $user = User::create([
                        'name' => $socialUser->name,
                        'email' => $socialUser->email,
                        'password' => Hash::make(Str::random(24)), // Generate a secure random password
                        'auth_provider_id' => $socialUser->id,
                        'auth_provider' => $provider,
                        'email_verified_at' => now(), // Social logins are considered verified
                    ]);
                    
                    // Save profile photo if available
                    if ($socialUser->avatar) {
                        $user->update([
                            'profile_photo_path' => $socialUser->avatar
                        ]);
                    }
                }
            }
            
            // Log the user in
            Auth::login($user);
            
            // Restore trip planning data from social auth session if it exists
            if (session()->has('social_auth_trip_data')) {
                $tripData = session('social_auth_trip_data');
                
                // Restore all trip session variables
                foreach ($tripData as $key => $value) {
                    session([$key => $value]);
                }
                
                // Clear the temporary storage
                session()->forget('social_auth_trip_data');
                
                // If trip data was saved, redirect to trips page
                if (session('trip_data_not_saved')) {
                    return redirect()->route('dashboard')->with('success', 'Successfully logged in! Your trip plan has been saved.');
                }
            }
            
            return redirect()->route('dashboard')->with('success', 'Successfully logged in!');
            
        } catch (Exception $e) {
            Log::error('Social authentication error: ' . $e->getMessage());
            return redirect()->route('login')
                ->with('error', 'Authentication failed. Please try again or use email login.');
        }
    }
}