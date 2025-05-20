<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        // Check if we have trip planning data
        $hasTripData = session()->has('selected_trip_type') || 
                     session()->has('selected_destination') || 
                     session()->has('trip_details');
        
        return view('auth.login', ['hasTripData' => $hasTripData]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        // Start timing for performance debugging
        $startTime = microtime(true);
        
        // Log login attempt for debugging
        Log::info('User login initiated', [
            'email' => $request->email,
            'has_trip_data' => session()->has('trip_data_not_saved'),
            'session_id' => session()->getId()
        ]);

        // If we have trip data in session, mark it for saving
        if (
            session()->has('selected_trip_type') || 
            session()->has('selected_destination')
        ) {
            session(['trip_data_not_saved' => true]);

            Log::info('Trip data marked for saving after login', [
                'trip_type' => session('selected_trip_type'),
                'has_destination' => session()->has('selected_destination'),
                'has_trip_details' => session()->has('trip_details')
            ]);
        }

        try {
            // Validate credentials
            $credentials = $request->validate([
                'email' => ['required', 'string', 'email'],
                'password' => ['required', 'string'],
            ]);
            
            if (Auth::attempt($credentials, $request->boolean('remember'))) {
                $request->session()->regenerate();

                // Log successful login
                Log::info('User login successful', [
                    'email' => $request->email,
                    'user_id' => Auth::id(),
                    'time_taken' => microtime(true) - $startTime
                ]);

                // The SaveTripAfterLogin middleware will handle trip data saving
                return redirect()->intended(RouteServiceProvider::HOME);
            }

            // Log failed login
            Log::info('User login failed - invalid credentials', [
                'email' => $request->email,
                'time_taken' => microtime(true) - $startTime
            ]);

            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
            
        } catch (\Exception $e) {
            // Log login error
            Log::error('Login error', [
                'email' => $request->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withErrors([
                'email' => 'An error occurred during login. Please try again.',
            ])->onlyInput('email');
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}