<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Project;
use App\Services\SettingsRepository;
use App\Services\SiteRenderer;
use App\Support\MediaResolver;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;

final class PixelCraftsLabSiteController extends Controller
{
    public function __construct(
        private readonly SiteRenderer $renderer,
        private readonly SettingsRepository $settings,
    ) {}

    public function __invoke(Request $request, ?Project $project = null): Response
    {
        abort_unless(File::exists($this->renderer->sourcePath()), 500, 'PixelCraftsLab Design source is missing.');

        abort_unless(
            $this->settings->bool('site_enabled', true),
            503,
            $this->settings->string('site_disabled_message', 'The site is temporarily unavailable.'),
        );

        $route = (string) ($request->route('siteRoute') ?: 'home');
        $pageKey = match ($route) {
            'marketing' => 'growth',
            'pricing' => Page::query()->where('key', 'pricing')->exists() ? 'pricing' : 'growth',
            'project' => 'project',
            default => $route,
        };

        $page = Page::query()->where('key', $pageKey)->first();
        abort_if($page && ! $page->is_published, 404);
        abort_if($route === 'lab' && ! $this->settings->bool('lab_page_enabled', true), 404);
        abort_if($route === 'marketing' && ! $this->settings->bool('growth_page_enabled', true), 404);
        abort_if($route === 'pricing' && ! $this->settings->bool('growth_page_enabled', true), 404);
        abort_if($route === 'project' && (! $project || ! $project->isLive()), 404);

        $canonical = $route === 'project'
            ? route('projects.show', $project)
            : route($request->route()?->getName() ?: 'home');

        $seo = [
            'title' => $route === 'project' ? ($project->seo_title ?: $project->name.' — PixelCraftsLab Studio') : $page?->seo_title,
            'description' => $route === 'project' ? ($project->seo_description ?: $project->short_description) : $page?->seo_description,
            'ogTitle' => $route === 'project' ? ($project->seo_title ?: $project->name) : $page?->og_title,
            'ogDescription' => $route === 'project' ? ($project->seo_description ?: $project->short_description) : $page?->og_description,
            'ogImage' => MediaResolver::url($route === 'project' ? $project->og_image : $page?->og_image),
            'robotsIndex' => $route === 'project' ? true : ($page?->robots_index ?? true),
            'canonical' => $page?->canonical_url ?: $canonical,
        ];

        // DESIGN LOCK:
        // Claude Design uses {{ ... }} expressions for its own runtime, so the
        // document is never compiled through Blade. SiteRenderer only prepends
        // <head> metadata and the window.PCL_CMS payload.
        return response($this->renderer->render(context: [
            'route' => $route,
            'projectSlug' => $project?->slug,
            'canonicalUrl' => parse_url($canonical, PHP_URL_PATH) ?: '/',
            'seo' => $seo,
        ]), 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Cache-Control' => 'no-cache, private',
        ]);
    }
}
