<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class VerifyPasscode
{
    protected int $timeout = 600; // 10 minutes

    public function handle(Request $request, Closure $next): Response
    {
        // Not authenticated — let auth middleware handle it
        if (!auth()->check()) {
            return $next($request);
        }

        $user = auth()->user();
        $routeName = $request->route()->getName();

        // Admin bypass
        if ($user->is_admin) {
            return $next($request);
        }

        // Whitelist: these routes don't need passcode checks
        $allowedRoutes = ['passcode.setup', 'passcode.page', 'logout', 'login', 'register'];
        if (in_array($routeName, $allowedRoutes)) {
            return $next($request);
        }

        // 1. No passcode set → force to setup
        if (!$user->hasPasscodeSetup()) {
            return redirect()->route('passcode.setup')
                ->with('warning', 'Please set up your passcode to continue.');
        }

        // 2. Check if passcode was verified recently
        $lastVerified = session('passcode_verified_at');
        $isVerified = session('passcode_verified') 
            && $lastVerified 
            && Carbon::parse($lastVerified)->addSeconds($this->timeout)->isFuture();

        if (!$isVerified) {
            session()->forget(['passcode_verified_at', 'passcode_verified']);

            return redirect()->route('passcode.page', [
                'redirectTo' => $request->getRequestUri(),
            ]);
        }

        return $next($request);
    }
}