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
        View::share('instagram', 'https://www.instagram.com/pixel_crafts_lab_studio?igsh=dzQzczA4aDUyaGYz');
        View::share('linkedIn', 'http://linkedin.com/in/pixelcraftslab-studio-46364139b');
        View::share('X', 'https://x.com/PixelCraftsLab');
        View::share('tiktok', 'http://tiktok.com/@pixel_crafts_lab_studio');
        View::share('pinterest', 'https://pin.it/7maousVqc');
        View::share('facebook', 'https://facebook.com/profile.php?id=61584760521477');
        View::share('youtube', 'https://www.youtube.com/@PIXELCRAFTSLABSTUDIO');
        View::share('whatsapp', 'https://www.whatsapp.com/channel/0029Vb7gHBr9Bb5revqEZz1W');
    }
}
