<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class RateLimit
{
    public function handle(Request $request, Closure $next)
    {
        $key = sprintf('rate-limit:%s', $request->ip());

        if (RateLimiter::tooManyAttempts($key, 5)) { // Limit to 5 requests
            return response()->json(['message' => 'Too many requests'], 429);
        }

        RateLimiter::hit($key, 60); // Reset attempts every 60 seconds

        return $next($request);
    }
}
