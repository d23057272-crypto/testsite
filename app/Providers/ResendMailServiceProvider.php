<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResendApiTransport;

class ResendMailServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Register custom Resend transport for Laravel 11
        Mail::extend('resend_api', function () {
            return new ResendApiTransport(
                env('RESEND_KEY'),
                env('MAIL_FROM_ADDRESS', 'support@pursekey.pro'),
                env('MAIL_FROM_NAME', 'PurseKey Wallet')
            );
        });
    }
}