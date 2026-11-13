<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA as G2fa;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class TwoFactorController extends Controller
{
   protected function generateQrCode($url)
    {
        $renderer = new ImageRenderer(
            new RendererStyle(200),
            new SvgImageBackEnd()
        );
        
        $writer = new Writer($renderer);
        return $writer->writeString($url);
    }

    public function setup()
    {
        $user = auth()->user();
        $is2faEnabled = $user->has2faEnabled();
        
        // Only generate new secrets if 2FA is not set up
        if (!$user->two_factor_secret && !$is2faEnabled) {
            $user->generateTwoFactorSecret();
            $user->generateRecoveryCodes();
        }

        $qrCodeUrl = $user->getTwoFactorQrCodeUrl();
        $qrCode = $this->generateQrCode($qrCodeUrl);
        
        return view('admin.2fa.setup', [
            'qrCode' => $qrCode,
            'secret' => $user->two_factor_secret,
            'recoveryCodes' => $user->getRecoveryCodes(),
            'is2faEnabled' => $is2faEnabled
        ]);
    }

    public function disable(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $user = auth()->user();

        if ($user->validateTwoFactorCode($request->code)) {
            $user->two_factor_secret = null;
            $user->two_factor_recovery_codes = null;
            $user->two_factor_confirmed_at = null;
            $user->save();
            
            session()->forget('2fa_verified');

            return redirect()->route('admin.2fa.setup')
                ->with('status', '2FA has been disabled.');
        }

        return back()->withErrors(['code' => 'The provided code was invalid.']);
    }

    public function confirm(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $user = auth()->user();

        if ($user->validateTwoFactorCode($request->code)) {
            $user->two_factor_confirmed_at = now();
            $user->save();
            
            session(['2fa_verified' => true]);

            return redirect()->route('admin.2fa.setup')
                ->with('status', '2FA has been enabled.');
        }

        return back()->withErrors(['code' => 'The provided code was invalid.']);
    }

    public function verify()
    {
        return view('admin.2fa.verify');
    }

    public function validateCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $user = auth()->user();

        if ($user->validateTwoFactorCode($request->code)) {
            session(['2fa_verified' => true]);
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors(['code' => 'The provided code was invalid.']);
    }

    public function useRecoveryCode(Request $request)
    {
        $request->validate([
            'recovery_code' => 'required|string',
        ]);

        $user = auth()->user();
        $recoveryCodes = collect($user->getRecoveryCodes());

        if ($recoveryCodes->contains($request->recovery_code)) {
            // Remove the used recovery code
            $user->two_factor_recovery_codes = json_encode($recoveryCodes->reject(function ($code) use ($request) {
                return $code === $request->recovery_code;
            })->values()->all());
            $user->save();

            session(['2fa_verified' => true]);
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors(['recovery_code' => 'The provided recovery code was invalid.']);
    }
}