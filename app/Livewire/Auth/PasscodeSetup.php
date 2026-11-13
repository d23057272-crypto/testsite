<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PasscodeSetup extends Component
{
    public int $step = 1;
    public string $passcode = '';
    public string $confirmPasscode = '';
    public ?string $error = null;
    public bool $success = false;

    protected array $rules = [
        'passcode' => 'required|string|size:6|regex:/^\d{6}$/',
        'confirmPasscode' => 'required|string|size:6|regex:/^\d{6}$/',
    ];

    protected array $messages = [
        'passcode.size' => 'Passcode must be exactly 6 digits.',
        'passcode.regex' => 'Passcode must contain only numbers.',
        'confirmPasscode.size' => 'Passcode must be exactly 6 digits.',
        'confirmPasscode.regex' => 'Passcode must contain only numbers.',
    ];

    public function updated($property): void
    {
        if ($property === 'passcode' || $property === 'confirmPasscode') {
            $this->resetErrorBag();
            $this->error = null;
        }
    }

    public function verifyAndSave(): void
    {
        $this->error = null;

        if ($this->step === 1) {
            try {
                $this->validateOnly('passcode');
                $this->step = 2;
                $this->confirmPasscode = '';
                $this->dispatch('passcode-step-changed', step: 2);
            } catch (\Illuminate\Validation\ValidationException $e) {
                $this->error = collect($e->validator->errors()->all())->first();
                $this->dispatch('passcode-error', message: $this->error);
            }
            return;
        }

        if ($this->step === 2) {
            try {
                $this->validateOnly('confirmPasscode');

                if ($this->passcode !== $this->confirmPasscode) {
                    $this->error = 'Passcodes do not match. Please try again.';
                    $this->step = 1;
                    $this->passcode = '';
                    $this->confirmPasscode = '';
                    $this->dispatch('passcode-mismatch');
                    return;
                }

                $this->savePasscode();
            } catch (\Illuminate\Validation\ValidationException $e) {
                $this->error = collect($e->validator->errors()->all())->first();
                $this->dispatch('passcode-error', message: $this->error);
            }
        }
    }

    protected function savePasscode(): void
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                $this->error = 'Authentication error. Please log in again.';
                $this->dispatch('passcode-error', message: $this->error);
                return;
            }

            $user->passcode = $this->passcode;
            $user->save();

            // DO NOT set passcode_verified — force verification on next page
            Session::put('passcode_setup_complete', true);
            
            $this->success = true;
            $this->step = 3;
            $this->dispatch('passcode-saved');

        } catch (\Exception $e) {
            \Log::error('Passcode setup failed: ' . $e->getMessage());
            $this->error = 'An error occurred while saving your passcode. Please try again.';
            $this->step = 1;
            $this->passcode = '';
            $this->confirmPasscode = '';
            $this->dispatch('passcode-error', message: $this->error);
        }
    }

  public function redirectToVerify(): void
{
    Session::forget('passcode_setup_complete');
    $this->redirect(route('passcode.page'), navigate: false);
}

    public function resetSetup(): void
    {
        $this->step = 1;
        $this->passcode = '';
        $this->confirmPasscode = '';
        $this->error = null;
        $this->success = false;
        $this->dispatch('passcode-reset');
    }

    public function render()
    {
        return view('livewire.auth.passcode-setup')
            ->layout('layouts.guest');
    }
}