<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle($request, Closure $next, $guard = null)
    {

        if (Auth::guard($guard)->check()) {
            // Try to use route name, fallback to direct URL if route doesn't exist
            try {
                return redirect()->route('user.home');
            } catch (\Exception $e) {
                // Fallback to direct URL if route name doesn't exist
                return redirect('/user/dashboard');
            }
        }

        return $next($request);

    }
}
