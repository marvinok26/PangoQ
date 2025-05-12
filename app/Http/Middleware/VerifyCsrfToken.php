<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Support\Facades\Log;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Only exclude third-party callbacks that can't provide CSRF tokens
        'auth/*/callback'
    ];
    
    /**
     * Add custom CSRF cookie and header name configuration
     */
    protected function tokensMatch($request)
    {
        $token = $this->getTokenFromRequest($request);
        $sessionToken = $request->session()->token();
        
        // Log token data in debug mode only
        if (config('app.debug')) {
            Log::debug('CSRF check', [
                'has_token' => $request->session()->has('_token'),
                'session_id' => $request->session()->getId(),
                'request_token' => $token,
                'session_token' => $sessionToken,
                'match' => hash_equals($sessionToken, $token)
            ]);
        }
        
        return is_string($request->session()->token()) &&
               is_string($token) &&
               hash_equals($request->session()->token(), $token);
    }
}