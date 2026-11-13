<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use App\Models\User;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'uuid' => ['required', 'string'],
        ]);


        $user = User::where('uuid', $request->uuid)->first();

        if (!$user) {
            return back()->withErrors(['uuid' => __('We can\'t find a user with that UUID.')]);
        }


        try {
            $status = Password::sendResetLink(
                ['email' => $user->email]
            );
            

            return $status == Password::RESET_LINK_SENT
                ? back()->with('status', __($status))
                : back()->withInput($request->only('uuid'))
                    ->withErrors(['uuid' => __($status)]);
        } catch (\Exception $e) {
            return back()->withErrors(['uuid' => 'Failed to send password reset link.']);
        }
    }
}