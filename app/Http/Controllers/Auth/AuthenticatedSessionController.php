<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\UserActivityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();
        
        $user = Auth::user();
        
        // Check if user is verified
        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice')
                ->with('warning', 'Please verify your email address to access your account.');
        }

        // If user is admin, check 2FA
        if ($user->is_admin) {
            // If 2FA is enabled but not yet setup
            if (!$user->two_factor_secret) {
                return redirect()->route('admin.2fa.setup')
                    ->with('warning', 'Please set up Two-Factor Authentication to continue.');
            }
            
            // If 2FA is enabled and setup but not verified for this session
            if ($user->has2faEnabled() && !session('2fa_verified')) {
                return redirect()->route('admin.2fa.verify');
            }

            session()->flash('status', 'Admin Logged in Successfully.');
            return redirect()->route('admin.dashboard');
        }

        // Regular user flow
        try {
            $user->createCryptoAssets();
            
            // Log the login activity
            UserActivityService::log('login', $user, [
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'login_time' => now()->format('Y-m-d H:i:s'),
                'is_mobile' => $request->header('User-Agent')
            ]);
        } catch (\Exception $e) {
            Log::error('Error during login process for user: ' . $user->id, [
                'error' => $e->getMessage()
            ]);
        }

        return redirect()->route('dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();
        
        if ($user) {
            // Log the logout activity
            try {
                UserActivityService::log('logout', $user, [
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'logout_time' => now()->format('Y-m-d H:i:s')
                ]);
            } catch (\Exception $e) {
                Log::error('Error logging logout for user: ' . $user->id, [
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        Auth::guard('web')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Clear 2FA session status
        session()->forget('2fa_verified');
        
        return redirect('/');
    }
}