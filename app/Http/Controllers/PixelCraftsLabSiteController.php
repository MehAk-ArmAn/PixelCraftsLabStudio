<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;

final class PixelCraftsLabSiteController extends Controller
{
    public function __invoke(): Response
    {
        $path = resource_path('pixelcraftslab/PixelCraftsLab Site.dc.html');

        abort_unless(File::exists($path), 500, 'PixelCraftsLab Design source is missing.');

        // DESIGN LOCK:
        // Claude Design uses {{ ... }} expressions for its own runtime.
        // Returning the original file raw prevents Blade from interpreting them.
        return response(File::get($path), 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Cache-Control' => 'no-cache',
        ]);
    }
}
