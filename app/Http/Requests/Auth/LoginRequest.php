<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use App\Notifications\UserLoggedInNotification;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'login' => ['nullable', 'required_without:email', 'string'],
            'email' => ['nullable', 'required_without:login', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Authenticate the request using either UUID or email and password.
     */
    public function authenticate()
    {
        $this->ensureIsNotRateLimited();

        $identifier = $this->input('login') ?: $this->input('email');

        // Determine if the login input is a UUID or email
        $loginField = filter_var($identifier, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'uuid';

        if (!Auth::attempt([
            $loginField => $identifier,
            'password' => $this->input('password'),
        ], $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'login' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());

        
    }

    /**
     * Ensure the login request is not rate limited.
     */
    public function ensureIsNotRateLimited()
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'login' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        $identifier = $this->input('login') ?: $this->input('email');

        return Str::lower((string) $identifier) . '|' . $this->ip();
    }
}
