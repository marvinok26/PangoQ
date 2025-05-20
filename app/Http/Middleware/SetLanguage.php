<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class SetLanguage
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
        if (Auth::check() && Auth::user()->preferred_language) {
            app()->setLocale(Auth::user()->preferred_language);
        } else {
            // Set default language from session if available
            $locale = Session::get('locale', config('app.locale'));
            app()->setLocale($locale);
        }
        
        return $next($request);
    }
}