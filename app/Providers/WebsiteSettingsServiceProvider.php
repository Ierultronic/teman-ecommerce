<?php

namespace App\Providers;

use App\Models\WebsiteSettings;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class WebsiteSettingsServiceProvider extends ServiceProvider
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
        // Share website settings with all views
        View::composer('*', function ($view) {
            try {
                $settings = WebsiteSettings::current();
                $view->with('websiteSettings', $settings);
            } catch (\Exception $e) {
                // Fallback to default settings if database is not ready
                $view->with('websiteSettings', (object) [
                    'shop_name' => 'TEMAN',
                    'logo_url' => asset('images/logo.png'),
                    'favicon_url' => asset('images/logo.png'),
                    'description' => 'Your trusted e-commerce platform',
                ]);
            }
        });
    }
}
