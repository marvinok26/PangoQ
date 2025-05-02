<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckRedisConnection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Test Redis connection
            Redis::ping();
        } catch (\Exception $e) {
            // Log the error
            Log::error('Redis connection failed: ' . $e->getMessage());
            
            // Use file cache driver as fallback
            config(['cache.default' => 'file']);
            config(['session.driver' => 'file']);
            config(['queue.default' => 'database']);
        }

        return $next($request);
    }
}