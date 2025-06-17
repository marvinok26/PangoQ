<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\TripPlanningStore;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    protected $tripPlanningStore;

    public function __construct(TripPlanningStore $tripPlanningStore)
    {
        $this->tripPlanningStore = $tripPlanningStore;
    }

    /**
     * Display the registration view.
     */
    public function create()
    {
        // Check if we have trip planning data to customize the registration message
        $hasTripData = $this->tripPlanningStore->hasUnsavedTripData();
        
        return view('auth.register', [
            'hasTripData' => $hasTripData,
            'tripProgress' => $hasTripData ? $this->tripPlanningStore->getTripProgress() : 0
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Check if we need to restore from Alpine.js localStorage
        $shouldRestore = $request->has('_restore_from_storage') || 
                        $request->header('X-Has-Trip-Data') === 'true';

        // Log registration step for debugging
        Log::info('User registration initiated', [
            'email' => $request->email,
            'has_trip_data' => $this->tripPlanningStore->hasUnsavedTripData(),
            'should_restore' => $shouldRestore
        ]);

        // If request indicates localStorage data should be restored
        if ($shouldRestore && $request->has('trip_data')) {
            $this->tripPlanningStore->restoreTripData($request->input('trip_data', []));
            Log::info('Trip data restored from Alpine.js localStorage during registration');
        }

        // Check if we have trip data
        $hasTripData = $this->tripPlanningStore->hasUnsavedTripData();

        // Mark trip data for saving if we have minimum data
        if ($this->tripPlanningStore->hasMinimumTripData()) {
            $this->tripPlanningStore->markForCreation();

            Log::info('Trip data marked for saving after registration', [
                'progress' => $this->tripPlanningStore->getTripProgress(),
                'current_step' => $this->tripPlanningStore->getCurrentStep()
            ]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Log::info('User registered successfully', [
            'user_id' => $user->id,
            'email' => $user->email,
            'has_trip_data' => $hasTripData
        ]);

        // If we have trip data, we need to preserve it through the login process
        if ($hasTripData) {
            // Store trip data in a way that survives the redirect to login
            Session::put('preserve_trip_data_for_login', true);
            Session::put('registered_user_email', $user->email);
            
            return redirect()->route('login')
                ->with('status', 'Your account has been created successfully! Please log in to continue your trip planning.')
                ->with('message', 'Your trip planning progress will be saved after you log in.')
                ->with('auto_fill_email', $user->email);
        } else {
            return redirect()->route('login')
                ->with('status', 'Your account has been created successfully! Please log in.');
        }
    }
}