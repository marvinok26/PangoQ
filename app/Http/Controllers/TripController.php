<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\Itinerary;
use App\Models\Activity;
use App\Models\TripMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TripController extends Controller
{
    /**
     * Display a listing of trips.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get trips where the user is the creator or a member
        $trips = Trip::where('creator_id', $user->id)
            ->orWhereHas('members', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('trips.index', compact('trips'));
    }

    /**
     * Show the form for creating a new trip.
     */
    public function create()
    {
        return view('trips.create');
    }

    /**
     * Display the specified trip.
     */
    public function show(Trip $trip)
    {
        // Check if the user is authorized to view the trip
        if (!$trip->isMember(Auth::id()) && !$trip->isOrganizer(Auth::id())) {
            abort(403, 'You do not have permission to view this trip.');
        }
        
        // Load trip relationships
        $trip->load([
            'creator', 
            'members.user', 
            'itineraries' => function($query) {
                $query->orderBy('day_number', 'asc');
            }, 
            'itineraries.activities',
            'savingsWallet'
        ]);
        
        return view('trips.show', compact('trip'));
    }

    /**
     * Finalize a trip and set it to 'active' status.
     */
    public function finalize(Trip $trip)
    {
        // Check if the user is authorized to finalize the trip
        if (!$trip->isOrganizer(Auth::id())) {
            abort(403, 'You do not have permission to finalize this trip.');
        }
        
        // Set trip status to active
        $trip->update(['status' => 'active']);
        
        // Redirect back with success message
        return redirect()->route('trips.show', $trip->id)
            ->with('success', 'Trip has been finalized successfully!');
    }
    
    /**
     * Generate and download PDF itinerary.
     */
    public function downloadPdf(Trip $trip)
    {
        // Check if the user is authorized to download the itinerary
        if (!$trip->isMember(Auth::id()) && !$trip->isOrganizer(Auth::id())) {
            abort(403, 'You do not have permission to download this itinerary.');
        }
        
        // Load trip relationships
        $trip->load([
            'creator', 
            'members.user', 
            'itineraries' => function($query) {
                $query->orderBy('day_number', 'asc');
            }, 
            'itineraries.activities'
        ]);
        
        // TODO: Generate PDF
        
        // For now, just redirect back with a message
        return redirect()->back()->with('info', 'PDF download feature coming soon!');
    }
    
    /**
     * Print-friendly itinerary view.
     */
    public function printView(Trip $trip)
    {
        // Check if the user is authorized to view the itinerary
        if (!$trip->isMember(Auth::id()) && !$trip->isOrganizer(Auth::id())) {
            abort(403, 'You do not have permission to view this itinerary.');
        }
        
        // Load trip relationships
        $trip->load([
            'creator', 
            'members.user', 
            'itineraries' => function($query) {
                $query->orderBy('day_number', 'asc');
            }, 
            'itineraries.activities'
        ]);
        
        return view('trips.print', compact('trip'));
    }
}