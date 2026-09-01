<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MediaUploadRequest;
use App\Models\Media;
use App\Services\ActivityLogger;
use App\Services\MediaLibraryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MediaController extends Controller
{
    public function __construct(
        private readonly MediaLibraryService $library,
        private readonly ActivityLogger $logger,
    ) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Media::class);

        return view('admin.media.index', [
            'media' => $this->query($request)->paginate(36)->withQueryString(),
            'q' => $request->query('q', ''),
            'type' => $request->query('type', ''),
        ]);
    }

    /** JSON feed for the media picker embedded in every content form. */
    public function browse(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Media::class);

        $items = $this->query($request)->limit(120)->get()->map(fn (Media $m) => [
            'id' => $m->id,
            'reference' => $m->reference(),
            'url' => $m->url(),
            'title' => $m->title ?: $m->original_name,
            'alt' => $m->alt_text,
            'mime' => $m->mime_type,
            'legacy' => $m->is_legacy,
        ]);

        return response()->json(['items' => $items]);
    }

    public function store(MediaUploadRequest $request): RedirectResponse
    {
        $this->authorize('create', Media::class);

        $folder = $request->input('folder', 'library');
        $created = 0;

        foreach ($request->file('files', []) as $file) {
            $media = $this->library->store($file, $folder);
            $this->logger->log('uploaded', $media, 'Media "'.$media->title.'" uploaded.');
            $created++;
        }

        return back()->with('status', $created.' file'.($created === 1 ? '' : 's').' uploaded.');
    }

    public function update(Request $request, Media $medium): RedirectResponse
    {
        $this->authorize('update', $medium);

        $medium->update($request->validate([
            'title' => ['nullable', 'string', 'max:190'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'caption' => ['nullable', 'string', 'max:255'],
        ]));

        $this->logger->logSaved('updated', $medium, 'Media "'.$medium->title.'" updated.');

        return back()->with('status', 'Media details saved.');
    }

    public function replace(MediaUploadRequest $request, Media $medium): RedirectResponse
    {
        $this->authorize('update', $medium);

        $files = $request->file('files', []);

        if ($files === []) {
            return back()->withErrors(['files' => 'Choose a replacement file.']);
        }

        $this->library->replace($medium, $files[0]);
        $this->logger->log('replaced', $medium, 'Media "'.$medium->title.'" replaced.');

        return back()->with('status', 'File replaced.');
    }

    public function destroy(Media $medium): RedirectResponse
    {
        $this->authorize('delete', $medium);

        if ($usage = $this->usage($medium)) {
            return back()->withErrors([
                'media' => 'Still in use by: '.implode(', ', $usage).'. Detach it first.',
            ]);
        }

        $title = $medium->title;
        $this->library->delete($medium);
        $this->logger->log('deleted', null, 'Media "'.$title.'" deleted.');

        return redirect()->route('admin.media.index')->with('status', 'Media deleted.');
    }

    public function importLegacy(): RedirectResponse
    {
        $this->authorize('create', Media::class);

        $count = $this->library->importLegacyAssets();

        return back()->with('status', $count.' existing public asset'.($count === 1 ? '' : 's').' registered.');
    }

    private function query(Request $request)
    {
        $query = Media::query()->latest('id');

        if ($term = trim((string) $request->query('q', ''))) {
            $query->where(fn ($q) => $q
                ->where('title', 'like', "%{$term}%")
                ->orWhere('original_name', 'like', "%{$term}%")
                ->orWhere('path', 'like', "%{$term}%"));
        }

        if ($request->query('type') === 'image') {
            $query->where('mime_type', 'like', 'image/%');
        } elseif ($request->query('type') === 'video') {
            $query->where('mime_type', 'like', 'video/%');
        } elseif ($request->query('type') === 'legacy') {
            $query->where('is_legacy', true);
        }

        return $query;
    }

    /** @return list<string> */
    private function usage(Media $media): array
    {
        $ref = $media->reference();

        $checks = [
            'Projects' => \App\Models\Project::where('primary_image', $ref)->orWhere('og_image', $ref)->exists(),
            'Team' => \App\Models\TeamMember::where('photo', $ref)->exists(),
            'Services' => \App\Models\Service::where('icon', $ref)->exists(),
            'Testimonials' => \App\Models\Testimonial::where('avatar', $ref)->exists(),
            'Page sections' => \App\Models\PageSection::where('media', $ref)->exists(),
            'Site settings' => \App\Models\SiteSetting::where('value', $ref)->exists(),
        ];

        return array_keys(array_filter($checks));
    }
}
