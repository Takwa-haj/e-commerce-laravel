<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\BarcodeGenerator;

class BarcodeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('barcode', function ($app) {
            return new BarcodeGenerator();
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
