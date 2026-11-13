<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
   public function register()
{
    $this->app->singleton(SettingsService::class, function ($app) {
        return new SettingsService();
    });
}

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
