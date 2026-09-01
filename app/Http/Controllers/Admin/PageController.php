<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageSection;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Page + section copy. Every visible string on the public site that is not a
 * projects/services/team record lives here.
 */
class PageController extends Controller
{
    public function __construct(private readonly ActivityLogger $logger) {}

    public function index(): View
    {
        $this->authorize('viewAny', Page::class);

        return view('admin.pages.index', [
            'pages' => Page::query()->withCount('sections')->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function edit(Page $page): View
    {
        $this->authorize('update', $page);

        return view('admin.pages.edit', [
            'page' => $page->load('sections'),
        ]);
    }

    public function update(Request $request, Page $page): RedirectResponse
    {
        $this->authorize('update', $page);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:190'],
            'is_published' => ['boolean'],
            'seo_title' => ['nullable', 'string', 'max:190'],
            'seo_description' => ['nullable', 'string', 'max:400'],
            'og_title' => ['nullable', 'string', 'max:190'],
            'og_description' => ['nullable', 'string', 'max:400'],
            'og_image' => ['nullable', 'string', 'max:255'],
            'canonical_url' => ['nullable', 'string', 'max:255'],
            'robots_index' => ['boolean'],
        ]);

        $page->recordRevision(null, 'Before page update');

        $page->fill($data + [
            'is_published' => $request->boolean('is_published'),
            'robots_index' => $request->boolean('robots_index'),
        ])->save();

        $this->logger->logSaved('updated', $page, 'Page "'.$page->title.'" updated.');

        return back()->with('status', 'Page saved.');
    }

    public function updateSection(Request $request, Page $page, PageSection $section): RedirectResponse
    {
        $this->authorize('update', $page);
        abort_unless($section->page_id === $page->id, 404);

        $data = $request->validate([
            'eyebrow' => ['nullable', 'string', 'max:255'],
            'heading' => ['nullable', 'string', 'max:1000'],
            'subheading' => ['nullable', 'string', 'max:1000'],
            'body' => ['nullable', 'string', 'max:8000'],
            'cta_label' => ['nullable', 'string', 'max:120'],
            'cta_url' => ['nullable', 'string', 'max:255'],
            'secondary_cta_label' => ['nullable', 'string', 'max:120'],
            'secondary_cta_url' => ['nullable', 'string', 'max:255'],
            'media' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:100000'],
            'settings' => ['nullable', 'array'],
            'settings.*' => ['nullable', 'string', 'max:4000'],
        ]);

        $settings = collect($data['settings'] ?? [])
            ->map(fn ($v) => is_string($v) ? trim($v) : $v)
            ->all();

        $section->fill($data + [
            'settings' => $settings,
            'is_enabled' => $request->boolean('is_enabled'),
        ])->save();

        $this->logger->logSaved('updated', $section, 'Section "'.($section->label ?: $section->section_key).'" on '.$page->title.' updated.');

        return back()->with('status', 'Section saved.');
    }

    public function toggleSection(Page $page, PageSection $section): RedirectResponse
    {
        $this->authorize('update', $page);
        abort_unless($section->page_id === $page->id, 404);

        $section->update(['is_enabled' => ! $section->is_enabled]);

        return back()->with('status', 'Section '.($section->is_enabled ? 'shown' : 'hidden').'.');
    }

    public function restore(Page $page): RedirectResponse
    {
        $this->authorize('update', $page);

        $revision = $page->revisions()->first();

        if (! $revision) {
            return back()->withErrors(['revision' => 'No earlier version stored for this page.']);
        }

        $page->forceFill(collect($revision->payload)->only($page->getFillable())->all())->save();
        $revision->delete();

        $this->logger->log('restored', $page, 'Page "'.$page->title.'" restored to previous version.');

        return back()->with('status', 'Previous version restored.');
    }
}
