<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();
        
        // Get user's trips (both created and participating)
        $trips = $user->trips()
            ->latest()
            ->get();
            
        // Get upcoming trips
        $upcomingTrips = $trips
            ->where('end_date', '>=', now()->toDateString())
            ->take(5);
            
        // Get past trips
        $pastTrips = $trips
            ->where('end_date', '<', now()->toDateString())
            ->take(5);
        
        return view('livewire.pages.dashboard', compact('upcomingTrips', 'pastTrips'));
    }
}