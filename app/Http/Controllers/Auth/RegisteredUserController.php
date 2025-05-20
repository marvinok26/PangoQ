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
            'has_trip_data' => session()->has('trip_data_not_saved')
        ]);

        // If we have trip data in session, mark it for saving after login
        if (
            session()->has('selected_trip_type') || 
            session()->has('selected_destination')
        ) {
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

         return redirect()->route('login')
            ->with('status', 'Your account has been created successfully! Please log in.');
    }
}