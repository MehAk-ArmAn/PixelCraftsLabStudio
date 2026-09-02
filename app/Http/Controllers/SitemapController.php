<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Project;
use App\Services\SettingsRepository;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(SettingsRepository $settings): Response
    {
        $pages = [
            'home' => 'home',
            'work' => 'work.index',
            'services' => 'services.index',
            'growth' => 'marketing.index',
            'pricing' => 'pricing.index',
            'studio' => 'studio',
            'lab' => 'lab',
            'contact' => 'contact',
        ];

        $published = Page::query()->pluck('is_published', 'key');
        $urls = collect($pages)
            ->reject(fn (string $route, string $key) => isset($published[$key]) && ! $published[$key])
            ->reject(fn (string $route, string $key) => $key === 'lab' && ! $settings->bool('lab_page_enabled', true))
            ->reject(fn (string $route, string $key) => in_array($key, ['growth', 'pricing'], true) && ! $settings->bool('growth_page_enabled', true))
            ->map(fn (string $route) => route($route));

        $projectUrls = Project::query()->live()->ordered()->get()
            ->map(fn (Project $project) => route('projects.show', $project));

        $body = $urls->concat($projectUrls)
            ->map(fn (string $url) => '  <url><loc>'.htmlspecialchars($url, ENT_XML1 | ENT_QUOTES, 'UTF-8').'</loc></url>')
            ->implode("\n");

        return response("<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n{$body}\n</urlset>\n", 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }
}
