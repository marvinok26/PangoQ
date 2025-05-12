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
            
            Auth::login($user);
            return redirect()->route('dashboard')->with('success', 'Successfully logged in!');
            
        } catch (Exception $e) {
            Log::error('Social authentication error: ' . $e->getMessage());
            return redirect()->route('login')
                ->with('error', 'Authentication failed. Please try again or use email login.');
        }
    }
}