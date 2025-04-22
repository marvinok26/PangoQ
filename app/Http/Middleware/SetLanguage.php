<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLanguage
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if language is set in session
        if ($request->session()->has('locale')) {
            App::setLocale($request->session()->get('locale'));
        } elseif ($request->hasHeader('Accept-Language')) {
            // Use browser language if available
            $locale = substr($request->header('Accept-Language'), 0, 2);
            if (in_array($locale, config('app.available_locales', ['en']))) {
                App::setLocale($locale);
                $request->session()->put('locale', $locale);
            }
        }

        return $next($request);
    }
}