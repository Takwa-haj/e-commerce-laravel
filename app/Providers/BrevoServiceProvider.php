<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class BrevoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register Brevo configuration
        $this->mergeConfigFrom(
            __DIR__.'/../../config/brevo.php', 'brevo'
        );

        // You can bind Brevo services here if needed
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish configuration
        $this->publishes([
            __DIR__.'/../../config/brevo.php' => config_path('brevo.php'),
        ], 'config');
    }
}
