<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create()
    {
        // Check if we have trip planning data to customize the registration message
        $hasTripData = session()->has('selected_trip_type') || 
                       session()->has('selected_destination') || 
                       session()->has('trip_details');
        
        return view('auth.register', ['hasTripData' => $hasTripData]);
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

        // Log registration step for debugging
        Log::info('User registration initiated', [
            'email' => $request->email,
            'has_trip_data' => session()->has('selected_trip_type')
        ]);

        // Check if we have trip data in session
        $hasTripData = session()->has('selected_trip_type') || 
                      session()->has('selected_destination') || 
                      session()->has('trip_details');

        // If we have trip data in session, mark it for saving after login
        if ($hasTripData) {
            session(['trip_data_not_saved' => true]);

            Log::info('Trip data marked for saving after registration', [
                'trip_type' => session('selected_trip_type'),
                'has_destination' => session()->has('selected_destination'),
                'has_trip_details' => session()->has('trip_details')
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

        // Always redirect to login page after registration
        if ($hasTripData) {
            return redirect()->route('login')
                ->with('status', 'Your account has been created successfully! Please log in to continue your trip planning.');
        } else {
            return redirect()->route('login')
                ->with('status', 'Your account has been created successfully! Please log in.');
        }
    }
}