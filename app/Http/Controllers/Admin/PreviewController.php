<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SiteContentService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Authenticated render of the public site including unpublished content.
 * Never reachable without an active admin session.
 */
class PreviewController extends Controller
{
    public function __construct(private readonly SiteContentService $content) {}

    public function __invoke(Request $request): Response
    {
        abort_unless($request->user()?->canManageContent(), 403);

        $content = $this->content->payload(true);

        return response()->view('work.index', [
            'content' => $content,
            'settings' => $content['settings'] ?? [],
            'flags' => $content['flags'] ?? [],
            'navigation' => $content['nav'] ?? [],
            'projects' => collect($content['projects'] ?? []),
            'project' => null,
            'nextProject' => null,
            'pageCopy' => data_get($content, 'copy.work', []),
            'routeKey' => 'work',
            'seo' => [
                'title' => 'Draft preview — PixelCraftsLab',
                'description' => 'Authenticated content preview.',
                'robotsIndex' => false,
                'canonical' => route('work.index'),
            ],
            'preview' => true,
        ], 200, [
            'Cache-Control' => 'no-store, private',
            'X-Robots-Tag' => 'noindex, nofollow',
        ]);
    }
}
