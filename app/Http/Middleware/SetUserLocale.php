<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class SetUserLocale
{
    public function handle($request, Closure $next)
    {
        $locale = Auth::user()?->locale;

        if (
            is_string($locale) &&
            array_key_exists($locale, config('app.supported_locales', []))
        ) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}