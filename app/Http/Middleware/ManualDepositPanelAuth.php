<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ManualDepositPanelAuth
{
    public function handle(Request $request, Closure $next)
    {
        // If already authenticated for this panel, allow
        if ($request->session()->get('manual_deposit_panel_authenticated') === true) {
            return $next($request);
        }

        // Allow access to the login form and login submission without being authenticated
        if ($request->routeIs('deposit.pending.login.form') || $request->routeIs('deposit.pending.login.submit')) {
            return $next($request);
        }

        // Otherwise, redirect to the password form
        return redirect()->route('deposit.pending.login.form');
    }
}

