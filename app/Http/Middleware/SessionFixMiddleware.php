<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SessionFixMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Make sure we have a valid session ID
        if (!$request->session()->isStarted()) {
            $request->session()->start();
        }
        
        // Debug session info in local environment
        if (app()->environment('local') && config('app.debug')) {
            Log::debug('Session info', [
                'has_session' => $request->hasSession(),
                'session_id' => $request->session()->getId(),
                'has_token' => $request->session()->has('_token'),
                'csrf_token' => $request->session()->token(),
                'cookies' => $request->cookies->all(),
            ]);
        }
        
        $response = $next($request);
        
        // Ensure session cookie is present in response
        if (!$response->headers->has('Set-Cookie') && $request->session()->isStarted()) {
            $cookie = Cookie::make(
                config('session.cookie'), 
                $request->session()->getId(), 
                config('session.lifetime'),
                config('session.path'), 
                config('session.domain'),
                config('session.secure'), 
                config('session.http_only'),
                false, 
                config('session.same_site')
            );
            
            $response->headers->setCookie($cookie);
        }
        
        return $response;
    }
}