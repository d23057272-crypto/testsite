<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckEmailVerificationSetting
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If user is not authenticated, continue
        if (!Auth::check()) {
            return $next($request);
        }

        // Check if email verification is required
        $requireVerification = setting('require_email_verification', true);

        // If verification is required and user is not verified
        if ($requireVerification && !Auth::user()->hasVerifiedEmail()) {
            // Redirect to verification notice
            return $request->expectsJson()
                ? abort(403, 'Your email address is not verified.')
                : redirect()->route('verification.notice');
        }

        return $next($request);
    }
}