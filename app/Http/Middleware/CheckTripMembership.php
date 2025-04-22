<?php

namespace App\Http\Middleware;

use App\Models\Trip;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckTripMembership
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get the trip from the route parameters
        $trip = $request->route('trip');
        
        if (!$trip instanceof Trip) {
            abort(404, 'Trip not found');
        }
        
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $userId = Auth::id();
        
        // Check if user is trip creator or member
        if ($trip->creator_id !== $userId && !$trip->isMember($userId)) {
            abort(403, 'You do not have access to this trip');
        }
        
        return $next($request);
    }
}