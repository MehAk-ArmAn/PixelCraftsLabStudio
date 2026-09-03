<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Project;
use App\Services\SettingsRepository;
use App\Services\SiteContentService;
use App\Support\MediaResolver;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class PixelCraftsLabSiteController extends Controller
{
    public function __construct(
        private readonly SiteContentService $content,
        private readonly SettingsRepository $settings,
    ) {}

    public function __invoke(Request $request, ?Project $project = null): Response
    {
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
        $content = $this->content->payload();
        $projects = collect($content['projects'] ?? []);
        $projectData = $route === 'project' ? $projects->firstWhere('id', $project?->slug) : null;
        $nextProject = null;

        if ($projectData && $projects->isNotEmpty()) {
            $projectIndex = $projects->search(fn (array $item): bool => $item['id'] === $projectData['id']);
            $nextProject = $projects->get(((int) $projectIndex + 1) % $projects->count());
        }

        $view = match ($route) {
            'work' => 'work.index',
            'project' => 'work.show',
            'services' => 'services.index',
            'marketing' => 'marketing.index',
            'pricing' => 'pricing.index',
            'studio' => 'studio',
            'lab' => 'lab',
            'contact' => 'contact',
            default => 'home',
        };

        return response()->view($view, [
            'content' => $content,
            'settings' => $content['settings'] ?? [],
            'flags' => $content['flags'] ?? [],
            'navigation' => $content['nav'] ?? [],
            'projects' => $projects,
            'project' => $projectData,
            'nextProject' => $nextProject,
            'pageCopy' => data_get($content, 'copy.'.$pageKey, []),
            'routeKey' => $route,
            'seo' => $seo,
        ], 200, [
            'Cache-Control' => 'no-cache, private',
        ]);
    }
}
