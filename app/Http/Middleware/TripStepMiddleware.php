<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TripStepMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // If we're in the process of creating a trip
        if ($request->route()->getName() === 'trips.create') {
            // Ensure step is initialized
            if (!session()->has('trip_current_step')) {
                session(['trip_current_step' => 0]);
            }
            
            // Get current step from session
            $step = session('trip_current_step', 0);
            
            // Validate if requirements for current step are met
            if ($step >= 1 && !session()->has('selected_destination')) {
                // Force back to destination selection
                session(['trip_current_step' => 0]);
            } else if ($step >= 2 && !session()->has('trip_details')) {
                // Force back to trip details
                session(['trip_current_step' => 1]);
            }
        }
        
        return $next($request);
    }
}