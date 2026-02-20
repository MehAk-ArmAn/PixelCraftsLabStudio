<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
        // makes the following variables available in all .blade.php files
        View::share('adminEmail', 'pixelcraftslabstudio@gmail.com');
        View::share('location', 'UK');
        View::share('name', 'PixelCraftsLabStudio');
        View::share('phone', '+971 56 701 8403');
        View::share('instagram', '#');
        View::share('linkedIn', '#');
        View::share('twitter', '#');
    }
}
