<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckRedisConnection
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
        // Only run check if Redis is the configured session driver
        if (config('session.driver') === 'redis') {
            try {
                // Attempt to ping Redis
                \Illuminate\Support\Facades\Redis::connection()->ping();
            } catch (\Exception $e) {
                // If Redis connection fails, log the error and switch to file sessions
                Log::warning('Redis connection failed, switching to file session driver: ' . $e->getMessage());
                config(['session.driver' => 'file']);
            }
        }
        
        return $next($request);
    }
}