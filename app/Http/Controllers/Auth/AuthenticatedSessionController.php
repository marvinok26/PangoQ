<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Services\TripPlanningStore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    protected $tripPlanningStore;

    public function __construct(TripPlanningStore $tripPlanningStore)
    {
        $this->tripPlanningStore = $tripPlanningStore;
    }

    /**
     * Display the login view.
     */
    public function create(): View
    {
        // Check if we have trip planning data
        $hasTripData = $this->tripPlanningStore->hasUnsavedTripData();
        
        return view('auth.login', [
            'hasTripData' => $hasTripData,
            'tripProgress' => $hasTripData ? $this->tripPlanningStore->getTripProgress() : 0
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        // Start timing for performance debugging
        $startTime = microtime(true);
        
        // Check if we need to restore from Alpine.js localStorage OR if coming from registration
        $shouldRestore = $request->has('_restore_from_storage') || 
                        $request->header('X-Has-Trip-Data') === 'true' ||
                        Session::get('preserve_trip_data_for_login', false);
        
        // Log login attempt for debugging
        Log::info('User login initiated', [
            'email' => $request->email,
            'has_trip_data' => $this->tripPlanningStore->hasUnsavedTripData(),
            'should_restore' => $shouldRestore,
            'preserve_trip_data' => Session::get('preserve_trip_data_for_login', false),
            'session_id' => session()->getId()
        ]);

        // If request indicates localStorage data should be restored
        if ($shouldRestore && $request->has('trip_data')) {
            $this->tripPlanningStore->restoreTripData($request->input('trip_data', []));
            Log::info('Trip data restored from Alpine.js localStorage during login');
        }

        // Mark trip data for saving if we have minimum data
        if ($this->tripPlanningStore->hasMinimumTripData()) {
            $this->tripPlanningStore->markForCreation();

            Log::info('Trip data marked for saving after login', [
                'progress' => $this->tripPlanningStore->getTripProgress(),
                'current_step' => $this->tripPlanningStore->getCurrentStep()
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

                // Clear the preserve flag since we're now logged in
                Session::forget(['preserve_trip_data_for_login', 'registered_user_email']);

                // Log successful login
                Log::info('User login successful', [
                    'email' => $request->email,
                    'user_id' => Auth::id(),
                    'has_trip_data' => $this->tripPlanningStore->hasUnsavedTripData(),
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