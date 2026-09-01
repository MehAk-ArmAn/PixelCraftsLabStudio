<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SiteRenderer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Authenticated render of the public site including unpublished content.
 * Never reachable without an active admin session.
 */
class PreviewController extends Controller
{
    public function __construct(private readonly SiteRenderer $renderer) {}

    public function __invoke(Request $request): Response
    {
        abort_unless($request->user()?->canManageContent(), 403);

        return response($this->renderer->render(preview: true), 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Cache-Control' => 'no-store, private',
            'X-Robots-Tag' => 'noindex, nofollow',
        ]);
    }
}
