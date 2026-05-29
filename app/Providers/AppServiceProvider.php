<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS URLs when behind a TLS-terminating proxy (ngrok, cloudflare, etc.)
        // Detected via X-Forwarded-Proto header (already trusted in bootstrap/app.php).
        if ($this->app->environment('production') || request()->isSecure()) {
            URL::forceScheme('https');
        }
    }
}
