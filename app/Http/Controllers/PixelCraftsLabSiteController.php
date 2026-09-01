<?php

namespace App\Http\Controllers;

use App\Services\SettingsRepository;
use App\Services\SiteRenderer;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;

final class PixelCraftsLabSiteController extends Controller
{
    public function __construct(
        private readonly SiteRenderer $renderer,
        private readonly SettingsRepository $settings,
    ) {}

    public function __invoke(): Response
    {
        abort_unless(File::exists($this->renderer->sourcePath()), 500, 'PixelCraftsLab Design source is missing.');

        abort_unless(
            $this->settings->bool('site_enabled', true),
            503,
            $this->settings->string('site_disabled_message', 'The site is temporarily unavailable.'),
        );

        // DESIGN LOCK:
        // Claude Design uses {{ ... }} expressions for its own runtime, so the
        // document is never compiled through Blade. SiteRenderer only prepends
        // <head> metadata and the window.PCL_CMS payload.
        return response($this->renderer->render(), 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Cache-Control' => 'no-cache, private',
        ]);
    }
}
