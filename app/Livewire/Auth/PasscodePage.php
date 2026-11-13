<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class PasscodePage extends Component
{
    public string $passcode = '';
    public ?string $error = null;
    public ?string $redirectTo = null;

    protected array $rules = [
        'passcode' => 'required|digits:6',
    ];

    public function mount(?string $redirectTo = null): void
    {
        $this->redirectTo = $this->safeRedirectTo($redirectTo);
        
        // If user hasn't set up passcode yet, redirect to setup
        if (!auth()->user()?->passcode) {
            $this->redirect(route('passcode.setup'), navigate: true);
            return;
        }
    }

    public function verify(string $passcode): void
    {
        $this->error = null;
        $this->passcode = $passcode;
        
        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->error = collect($e->validator->errors()->all())->first();
            $this->dispatch('passcode-error', message: $this->error);
            return;
        }

        if ($this->verifyPasscode($this->passcode)) {
            Session::put('passcode_verified', true);
            Session::put('passcode_verified_at', now());
            $this->redirect(
                $this->safeRedirectTo($this->redirectTo),
                navigate: true
            );
            return;
        }

        $this->error = 'Invalid passcode. Please try again.';
        $this->passcode = '';
        $this->dispatch('passcode-error', message: $this->error);
    }

    private function verifyPasscode(string $passcode): bool
    {
        $user = auth()->user();
        return $user && Hash::check($passcode, $user->passcode);
    }

    private function safeRedirectTo(?string $redirectTo): string
    {
        if (
            !is_string($redirectTo) ||
            $redirectTo === '' ||
            str_starts_with($redirectTo, '//') ||
            str_contains($redirectTo, '\\')
        ) {
            return route('dashboard');
        }

        $parts = parse_url($redirectTo);

        if (
            $parts === false ||
            isset($parts['scheme']) ||
            isset($parts['host']) ||
            !isset($parts['path']) ||
            !str_starts_with($parts['path'], '/')
        ) {
            return route('dashboard');
        }

        $passcodePath = parse_url(route('passcode.page'), PHP_URL_PATH);
        $setupPath = parse_url(route('passcode.setup'), PHP_URL_PATH);

        if (in_array($parts['path'], [$passcodePath, $setupPath], true)) {
            return route('dashboard');
        }

        return $parts['path'] . (
            isset($parts['query']) ? '?' . $parts['query'] : ''
        );
    }

    public function render()
    {
        return view('livewire.auth.passcode-page')->layout('layouts.guest');
    }
}