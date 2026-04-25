<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Share settings with all views
        View::composer('*', function ($view) {
            $settings = cache()->remember('site_settings', 3600, function () {
                return Setting::first() ?? new Setting();
            });

            $view->with('settings', $settings);
        });
    }
}
