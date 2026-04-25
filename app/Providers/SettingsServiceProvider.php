<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Setting;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Don't access database or views here
        // Just register bindings if needed
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Check if the app is running in console (artisan commands)
        if ($this->app->runningInConsole()) {
            // Don't try to load settings during console commands
            // Just set defaults
            config(['cart.tax' => 0]);
            config(['cart.currency_symbol' => '€']);
            config(['cart.shipping_cost' => 0]);
            return;
        }

        // Only load settings for web requests
        try {
            $settings = Setting::first();

            if ($settings) {
                // Override cart configuration
                config([
                    'cart.tax' => $settings->tax_rate,
                    'cart.currency_symbol' => $settings->currency_symbol,
                    'cart.shipping_cost' => $settings->shipping_cost,
                ]);

                // Share settings with all views
                // This needs to be done after the view service is available
                view()->share('settings', $settings);
            } else {
                // Set defaults if no settings exist
                config([
                    'cart.tax' => 0,
                    'cart.currency_symbol' => '€',
                    'cart.shipping_cost' => 0,
                ]);
                view()->share('settings', null);
            }
        } catch (\Exception $e) {
            // If there's an error (e.g., database not available), set defaults
            config([
                'cart.tax' => 0,
                'cart.currency_symbol' => '€',
                'cart.shipping_cost' => 0,
            ]);
        }
    }
}
