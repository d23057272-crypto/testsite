<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Require2FA
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && $user->isAdmin()) {
            // If 2FA is not set up yet, redirect to setup
            if (!$user->two_factor_secret) {
                return redirect()->route('admin.2fa.setup');
            }

            // If 2FA is not confirmed in the session and setup is complete
            if (!session('2fa_verified') && $user->two_factor_confirmed_at) {
                return redirect()->route('admin.2fa.verify');
            }
        }

        return $next($request);
    }
}