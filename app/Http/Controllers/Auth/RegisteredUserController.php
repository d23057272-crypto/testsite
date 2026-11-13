<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CryptoAsset;
use App\Models\Notification;
use App\Mail\WelcomeEmail;
use App\Models\Setting;
use App\Services\UserActivityService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    public function create(Request $request): View
    {
        $referral_code = $request->ref;
        $referrer = null;
        
        if ($referral_code) {
            $referrer = User::where('referral_code', $referral_code)->first();
        }
        
        return view('auth.register', compact('referral_code', 'referrer'));
    }

    public function verifyReferralCode(Request $request)
    {
        $referrer = User::where('referral_code', $request->referral_code)->first();
        
        return response()->json([
            'valid' => !is_null($referrer),
            'referrer' => $referrer ? $referrer->name : null
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'referral_code' => ['nullable', 'string', 'exists:users,referral_code'],
        ]);

        try {
            // Use UserActivityService's logInTransaction method to handle DB transaction and rollback 
            // if email notification fails
            $user = UserActivityService::logInTransaction(
                'register',
                new User(['email' => $request->email, 'name' => $request->name]),
                function() use ($request) {
                    $referrer = null;
                    if ($request->referral_code) {
                        $referrer = User::where('referral_code', $request->referral_code)->lockForUpdate()->first();
                    }

                    $user = User::create([
                        'name' => $request->name,
                        'email' => $request->email,
                        'password' => Hash::make($request->password),
                        'referral_code' => 'REF' . strtoupper(Str::random(8)),
                        'referred_by' => $referrer ? $referrer->id : null,
                        'referral_count' => 0
                    ]);

                    if ($referrer) {
                        $referrer->referral_count = $referrer->referral_count + 1;
                        $referrer->save();

                        // Handle instant referral bonus if enabled and referrer is KYC verified
                        if (setting('referral_enabled') && 
                            (setting('referral_bonus_type') === 'instant' || 
                            (setting('referral_bonus_type') === 'after_kyc' && $referrer->kyc_status === 'approved'))) {
                            
                            $bonus_amount = setting('referral_bonus_amount', 0);
                            
                            $referrerCryptoAsset = CryptoAsset::firstOrCreate(
                                ['user_id' => $referrer->id]
                            );

                            $referrerCryptoAsset->increment('usdt_trc20_balance', $bonus_amount);

                            // Notify referrer about the bonus
                            Notification::createNotification([
                                'user_id' => $referrer->id,
                                'type' => 'referral_bonus',
                                'title' => 'Referral Bonus Received',
                                'message' => "You have received a referral bonus of $${bonus_amount} USDT for referring {$user->name}",
                                'extra_data' => json_encode([
                                    'amount' => $bonus_amount,
                                    'referred_user' => $user->name
                                ])
                            ]);
                        }
                    }

                    $cryptoAsset = CryptoAsset::create([
                        'user_id' => $user->id
                    ]);
                    $cryptoAsset->generateAddressesIfNull();
                    
                    // 🔧 COMPATIBILITY FIX: Send welcome email with mbstring fallback
                    // Laravel's mail views require mbstring extension. If unavailable, skip gracefully.
                    try {
                        if (extension_loaded('mbstring')) {
                            Mail::to($user->email)->queue(new WelcomeEmail($user));
                        } else {
                            // Fallback: try synchronous send (may still fail without mbstring, but worth trying)
                            Log::warning('mbstring not loaded - attempting synchronous welcome email', [
                                'user_id' => $user->id,
                                'email' => $user->email
                            ]);
                            Mail::to($user->email)->send(new WelcomeEmail($user));
                        }
                    } catch (\Throwable $e) {
                        Log::error('Welcome email failed (mbstring or queue issue)', [
                            'user_id' => $user->id,
                            'email' => $user->email,
                            'error' => $e->getMessage(),
                            'mbstring_loaded' => extension_loaded('mbstring')
                        ]);
                        // Continue registration even if email fails - don't block user onboarding
                    }

                    return $user;
                },
                [
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'referred_by' => $request->referral_code
                ]
            );

            // 🔧 COMPATIBILITY FIX: Fire Registered event with mbstring check
            // The Registered event triggers email verification which uses mbstring
            try {
                if (extension_loaded('mbstring')) {
                    event(new Registered($user));
                } else {
                    Log::warning('Registered event skipped - mbstring not available', [
                        'user_id' => $user->id
                    ]);
                    // Optionally: manually mark email as verified if your app allows it
                    // $user->markEmailAsVerified();
                }
            } catch (\Throwable $e) {
                Log::error('Registered event failed', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
                // Continue - email verification can be completed later
            }

            // ✅ Auto-login user to allow wallet setup
            Auth::login($user);

            // Redirect to wallet option page instead of verification/dashboard
            return redirect()->route('wallet.option')
                ->with('status', 'Please set up your wallet to continue.');

        } catch (\Exception $e) {
            // Log full error details for debugging
            Log::error('Registration failed', [
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'email' => $request->email,
                'mbstring_loaded' => extension_loaded('mbstring')
            ]);
            
            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['registration' => 'Registration failed: ' . $e->getMessage()]);
        }
    }
}