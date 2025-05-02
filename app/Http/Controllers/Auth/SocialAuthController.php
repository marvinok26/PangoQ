<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
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
     * Redirect the user to the provider authentication page.
     *
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToProvider(string $provider): RedirectResponse
    {
        // Store the previous URL in the session to redirect back after auth
        if (!str_contains(url()->previous(), 'auth') && 
            !str_contains(url()->previous(), 'login') && 
            !str_contains(url()->previous(), 'register')) {
            Session::put('url.intended', url()->previous());
        }

        try {
            return Socialite::driver($provider)->redirect();
        } catch (\Exception $e) {
            Log::error('Social login redirect failed: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Unable to connect to ' . ucfirst($provider) . '. Please try again later.');
        }
    }

    /**
     * Handle the provider callback and authenticate the user.
     *
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleProviderCallback(string $provider): RedirectResponse
    {
        try {
            // Get user data from provider
            $socialUser = Socialite::driver($provider)->user();
            
            // Find or create user
            $user = User::firstOrCreate(
                ['email' => $socialUser->getEmail()],
                [
                    'name' => $socialUser->getName() ?: 'User',
                    'password' => Hash::make(Str::random(24)),
                    'email_verified_at' => now(), // Social logins are considered verified
                ]
            );
            
            // Login the user
            Auth::login($user, true);
            
            // Get intended URL from session
            $redirectTo = Session::pull('url.intended', route('dashboard'));
            
            return redirect()->to($redirectTo)
                ->with('success', 'Welcome to PangoQ!');
                
        } catch (\Exception $e) {
            Log::error('Social login failed: ' . $e->getMessage());
            return redirect()->route('login')
                ->with('error', 'Authentication with ' . ucfirst($provider) . ' failed. Please try again or use email login.');
        }
    }
}