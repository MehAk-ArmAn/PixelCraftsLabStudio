<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageFeaturedProject;
use App\Models\Project;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

final class HomepageFeaturedProjectController extends Controller
{
    public function __construct(private readonly ActivityLogger $logger) {}

    public function index(): View
    {
        $this->authorize('viewAny', HomepageFeaturedProject::class);

        return view('admin.home.featured-projects', [
            'projects' => Project::query()
                ->where('is_archived', false)
                ->orderBy('name')
                ->get(),
            'features' => HomepageFeaturedProject::query()
                ->with('project')
                ->ordered()
                ->get()
                ->keyBy('project_id'),
            'displayModes' => HomepageFeaturedProject::DISPLAY_MODES,
            'mediaModes' => HomepageFeaturedProject::MEDIA_MODES,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $this->authorize('update', HomepageFeaturedProject::class);

        $data = $request->validate([
            'primary_project_id' => ['required', 'integer', Rule::exists('projects', 'id')],
            'featured' => ['required', 'array'],
            'featured.*.project_id' => ['required', 'integer', 'distinct', Rule::exists('projects', 'id')],
            'featured.*.selected' => ['nullable', 'boolean'],
            'featured.*.enabled' => ['nullable', 'boolean'],
            'featured.*.sort_order' => ['required', 'integer', 'min:0', 'max:100000'],
            'featured.*.display_mode' => ['required', Rule::in(array_keys(HomepageFeaturedProject::DISPLAY_MODES))],
            'featured.*.media_mode' => ['required', Rule::in(array_keys(HomepageFeaturedProject::MEDIA_MODES))],
            'featured.*.badge_text' => ['nullable', 'string', 'max:120'],
            'featured.*.cta_label' => ['nullable', 'string', 'max:120'],
        ]);

        $selected = collect($data['featured'])
            ->filter(fn (array $row): bool => filter_var($row['selected'] ?? false, FILTER_VALIDATE_BOOLEAN))
            ->sortBy([
                ['sort_order', 'asc'],
                ['project_id', 'asc'],
            ])
            ->values();
        $primaryProjectId = (int) $data['primary_project_id'];
        $primary = $selected->firstWhere('project_id', $primaryProjectId);

        if (! $primary || ! filter_var($primary['enabled'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
            throw ValidationException::withMessages([
                'primary_project_id' => 'The Primary project must be selected and visible.',
            ]);
        }

        $enabledIds = $selected
            ->filter(fn (array $row): bool => filter_var($row['enabled'] ?? false, FILTER_VALIDATE_BOOLEAN))
            ->pluck('project_id')
            ->map(fn ($id): int => (int) $id);
        $liveCount = Project::query()->whereKey($enabledIds)->live()->count();

        if ($liveCount !== $enabledIds->count()) {
            throw ValidationException::withMessages([
                'featured' => 'Visible homepage projects must be published and not archived.',
            ]);
        }

        DB::transaction(function () use ($selected, $primaryProjectId): void {
            HomepageFeaturedProject::query()->lockForUpdate()->get();
            HomepageFeaturedProject::query()->update(['is_primary' => false]);

            $selectedIds = $selected->pluck('project_id')->map(fn ($id): int => (int) $id)->all();
            HomepageFeaturedProject::query()->whereNotIn('project_id', $selectedIds)->delete();

            foreach ($selected as $index => $row) {
                HomepageFeaturedProject::updateOrCreate(
                    ['project_id' => (int) $row['project_id']],
                    [
                        'slot' => $index + 1,
                        'sort_order' => (int) $row['sort_order'],
                        'is_primary' => (int) $row['project_id'] === $primaryProjectId,
                        'enabled' => filter_var($row['enabled'] ?? false, FILTER_VALIDATE_BOOLEAN),
                        'display_mode' => $row['display_mode'],
                        'media_mode' => $row['media_mode'],
                        'badge_text' => filled($row['badge_text'] ?? null) ? trim($row['badge_text']) : null,
                        'cta_label' => filled($row['cta_label'] ?? null) ? trim($row['cta_label']) : null,
                    ],
                );
            }
        });

        $this->logger->log('updated', null, 'Homepage featured projects updated.', [
            'project_ids' => $selected->pluck('project_id')->all(),
            'primary_project_id' => $primaryProjectId,
        ]);

        return back()->with('status', 'Homepage featured projects saved.');
    }
}
