<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share settings with all views
        try {
            if (Schema::hasTable('settings')) {
                $appSettings = Setting::pluck('value', 'key')->all();
                View::share('appSettings', $appSettings);
            }
        } catch (\Exception $e) {
            // Table might not exist yet during migration
            View::share('appSettings', []);
        }
    }
}
