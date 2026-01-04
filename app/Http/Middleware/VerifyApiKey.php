<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyApiKey
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
        $apiKey = $request->header('X-API-Key');
        $expectedKey = env('SEO_API_KEY');

        if (!$expectedKey) {
            return response()->json([
                'success' => false,
                'message' => 'API key not configured'
            ], 500);
        }

        if (!$apiKey || $apiKey !== $expectedKey) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or missing API key'
            ], 401);
        }

        return $next($request);
    }
}

