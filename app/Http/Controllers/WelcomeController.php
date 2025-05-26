<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class WelcomeController extends Controller
{
    public function index()
    {
        // Get popular destinations for the welcome page
        $popularDestinations = Destination::with('tripTemplates')
            ->whereHas('tripTemplates')
            ->take(6)
            ->get();

        return view('welcome', compact('popularDestinations'));
    }

    public function searchDestinations(Request $request)
    {
        $query = $request->get('query', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $destinations = Destination::where('name', 'LIKE', "%{$query}%")
            ->orWhere('city', 'LIKE', "%{$query}%")
            ->orWhere('country', 'LIKE', "%{$query}%")
            ->take(5)
            ->get(['id', 'name', 'city', 'country']);

        return response()->json($destinations);
    }

    public function planTrip(Request $request)
    {
        $request->validate([
            'destination' => 'required|string|max:255',
            'trip_type' => 'required|in:pre_planned,self_planned',
        ]);

        // Store the selected destination and trip type in session
        Session::put([
            'selected_destination' => $request->destination,
            'selected_trip_type' => $request->trip_type,
        ]);

        // Redirect to the appropriate planning flow
        if ($request->trip_type === 'pre_planned') {
            return redirect()->route('trips.pre-planned')->with('destination', $request->destination);
        } else {
            return redirect()->route('trips.plan')->with('destination', $request->destination);
        }
    }
}