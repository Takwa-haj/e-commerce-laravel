<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;
use App\Observers\OrderItemObserver;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Config;

use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
         $this->app->singleton('barcode', function ($app) {
        return new \App\Services\BarcodeService();
    });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
         // Dynamically set mail configuration from settings
        if (Schema::hasTable('settings')) {
            $settings = Setting::first();

            if ($settings) {
                Config::set('mail.from.address', $settings->site_email ?? env('MAIL_FROM_ADDRESS'));
                Config::set('mail.from.name', $settings->site_name ?? env('MAIL_FROM_NAME'));
            }
        }
       // OrderItem::observe(OrderItemObserver::class);
        // Share settings with all views
        View::composer('*', function ($view) {
            $settings = Setting::first();
            $view->with('settings', $settings);
        });

        // You can also create a separate setting for admin if needed
        View::composer('admin.*', function ($view) {
            $adminSettings = Setting::first();
            $view->with('adminSettings', $adminSettings);
        });
    }
}
