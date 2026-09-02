<?php

namespace App\Http\Controllers\Admin;

use App\Models\MarketingChannel;
use App\Models\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProjectController extends AdminResourceController
{
    protected function model(): string
    {
        return Project::class;
    }

    protected function routeBase(): string
    {
        return 'projects';
    }

    protected function title(): string
    {
        return 'Projects';
    }

    protected function searchable(): array
    {
        return ['name', 'slug', 'category', 'short_description'];
    }

    protected function listColumns(): array
    {
        return [
            ['key' => 'name', 'label' => 'Project'],
            ['key' => 'category', 'label' => 'Category'],
            ['key' => 'status', 'label' => 'Status', 'type' => 'badge'],
            ['key' => 'is_featured', 'label' => 'Featured', 'type' => 'bool'],
            ['key' => 'is_marketing_case_study', 'label' => 'Case study', 'type' => 'bool'],
            ['key' => 'is_published', 'label' => 'Live', 'type' => 'bool'],
        ];
    }

    protected function schema(): array
    {
        return [
            ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true, 'section' => 'Basics'],
            ['name' => 'slug', 'label' => 'Slug', 'type' => 'text', 'section' => 'Basics', 'help' => 'Used as the public project id. Leave blank to generate from the name.'],
            ['name' => 'category', 'label' => 'Category', 'type' => 'text', 'section' => 'Basics', 'datalist' => self::CATEGORIES, 'help' => 'Drives the Work page filters — e.g. Web, Apps, Games, Lab, Marketing.'],
            ['name' => 'kind', 'label' => 'Discipline', 'type' => 'text', 'section' => 'Basics'],
            ['name' => 'platform', 'label' => 'Platform', 'type' => 'text', 'section' => 'Basics'],
            ['name' => 'layout_size', 'label' => 'Card size', 'type' => 'select', 'section' => 'Basics', 'options' => ['std' => 'Standard', 'wide' => 'Wide', 'tall' => 'Tall']],
            ['name' => 'external_url', 'label' => 'Live URL', 'type' => 'text', 'section' => 'Basics', 'help' => 'Leave empty to keep the "in development" treatment.'],

            ['name' => 'short_description', 'label' => 'Short description', 'type' => 'textarea', 'section' => 'Content', 'rows' => 2],
            ['name' => 'full_description', 'label' => 'Full description', 'type' => 'textarea', 'section' => 'Content', 'rows' => 4],
            ['name' => 'case_study', 'label' => 'Case study notes', 'type' => 'textarea', 'section' => 'Content', 'rows' => 4, 'help' => 'Shown in the "What we built" block on the project page.'],

            ['name' => 'is_marketing_case_study', 'label' => 'Marketing case study', 'type' => 'checkbox', 'section' => 'Marketing'],
            ['name' => 'client_goal', 'label' => 'Client goal', 'type' => 'text', 'section' => 'Marketing'],
            ['name' => 'challenge', 'label' => 'Problem / challenge', 'type' => 'textarea', 'section' => 'Marketing', 'rows' => 3],
            ['name' => 'audience', 'label' => 'Audience', 'type' => 'textarea', 'section' => 'Marketing', 'rows' => 2],
            ['name' => 'strategy', 'label' => 'Strategy', 'type' => 'textarea', 'section' => 'Marketing', 'rows' => 3],
            ['name' => 'approach', 'label' => 'Content / campaign approach', 'type' => 'textarea', 'section' => 'Marketing', 'rows' => 3],
            ['name' => 'deliverables', 'label' => 'Deliverables', 'type' => 'textarea', 'section' => 'Marketing', 'rows' => 3],
            ['name' => 'results', 'label' => 'Results', 'type' => 'textarea', 'section' => 'Marketing', 'rows' => 3, 'help' => 'Only publish results you can stand behind.'],
            ['name' => 'lessons', 'label' => 'Lessons / next steps', 'type' => 'textarea', 'section' => 'Marketing', 'rows' => 2],
            ['name' => 'campaign_period', 'label' => 'Campaign period', 'type' => 'text', 'section' => 'Marketing'],
            ['name' => 'channel_ids', 'label' => 'Channels', 'type' => 'checkboxes', 'section' => 'Marketing', 'optionsFrom' => 'channels'],

            ['name' => 'primary_image', 'label' => 'Primary image', 'type' => 'media', 'section' => 'Media'],
            ['name' => 'gallery', 'label' => 'Gallery', 'type' => 'media-multi', 'section' => 'Media'],
            ['name' => 'initials', 'label' => 'Initials', 'type' => 'text', 'section' => 'Media', 'help' => 'Used by the card artwork when no image is set.'],
            ['name' => 'primary_tint', 'label' => 'Primary tint', 'type' => 'color', 'section' => 'Media'],
            ['name' => 'secondary_tint', 'label' => 'Secondary tint', 'type' => 'color', 'section' => 'Media'],

            ['name' => 'cta_label', 'label' => 'CTA label', 'type' => 'text', 'section' => 'Publishing'],
            ['name' => 'cta_url', 'label' => 'CTA URL', 'type' => 'text', 'section' => 'Publishing'],
            ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'section' => 'Publishing', 'options' => [
                Project::STATUS_DRAFT => 'Draft',
                Project::STATUS_PUBLISHED => 'Published',
                Project::STATUS_HIDDEN => 'Hidden',
                Project::STATUS_ARCHIVED => 'Archived',
            ]],
            ['name' => 'is_published', 'label' => 'Published', 'type' => 'checkbox', 'section' => 'Publishing'],
            ['name' => 'is_featured', 'label' => 'Featured', 'type' => 'checkbox', 'section' => 'Publishing'],
            ['name' => 'is_archived', 'label' => 'Archived', 'type' => 'checkbox', 'section' => 'Publishing'],
            ['name' => 'sort_order', 'label' => 'Sort order', 'type' => 'number', 'section' => 'Publishing'],
            ['name' => 'published_at', 'label' => 'Published at', 'type' => 'datetime', 'section' => 'Publishing'],

            ['name' => 'seo_title', 'label' => 'SEO title', 'type' => 'text', 'section' => 'SEO'],
            ['name' => 'seo_description', 'label' => 'Meta description', 'type' => 'textarea', 'section' => 'SEO', 'rows' => 2],
            ['name' => 'og_image', 'label' => 'OG image', 'type' => 'media', 'section' => 'SEO'],
        ];
    }

    public const CATEGORIES = [
        'Web', 'Apps', 'Games', 'Lab', 'Marketing', 'Social Media',
        'Campaign', 'Brand Growth', 'SEO', 'Content', 'Launch Campaign', 'Growth Strategy',
    ];

    protected function rules(?Model $record = null): array
    {
        return [
            'name' => ['required', 'string', 'max:190'],
            'slug' => ['nullable', 'string', 'max:190', 'alpha_dash', Rule::unique('projects', 'slug')->ignore($record?->getKey())],
            'category' => ['required', 'string', 'max:64'],
            'kind' => ['nullable', 'string', 'max:120'],
            'platform' => ['nullable', 'string', 'max:120'],
            'layout_size' => ['required', Rule::in(['std', 'wide', 'tall'])],
            'external_url' => ['nullable', 'url', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'full_description' => ['nullable', 'string', 'max:5000'],
            'case_study' => ['nullable', 'string', 'max:5000'],
            'client_goal' => ['nullable', 'string', 'max:255'],
            'challenge' => ['nullable', 'string', 'max:3000'],
            'audience' => ['nullable', 'string', 'max:2000'],
            'strategy' => ['nullable', 'string', 'max:3000'],
            'approach' => ['nullable', 'string', 'max:3000'],
            'deliverables' => ['nullable', 'string', 'max:3000'],
            'results' => ['nullable', 'string', 'max:3000'],
            'lessons' => ['nullable', 'string', 'max:2000'],
            'campaign_period' => ['nullable', 'string', 'max:120'],
            'channel_ids' => ['nullable', 'array'],
            'channel_ids.*' => ['integer', 'exists:marketing_channels,id'],
            'primary_image' => ['nullable', 'string', 'max:255'],
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['string', 'max:255'],
            'initials' => ['nullable', 'string', 'max:8'],
            'primary_tint' => ['nullable', 'string', 'max:16'],
            'secondary_tint' => ['nullable', 'string', 'max:16'],
            'cta_label' => ['nullable', 'string', 'max:120'],
            'cta_url' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(Project::STATUSES)],
            'is_published' => ['boolean'],
            'is_featured' => ['boolean'],
            'is_archived' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:100000'],
            'published_at' => ['nullable', 'date'],
            'seo_title' => ['nullable', 'string', 'max:190'],
            'seo_description' => ['nullable', 'string', 'max:400'],
            'og_image' => ['nullable', 'string', 'max:255'],
            'is_marketing_case_study' => ['boolean'],
        ];
    }

    protected function transform(array $data, Model $record): array
    {
        unset($data['channel_ids']);

        $data['slug'] = filled($data['slug'] ?? null)
            ? Str::slug($data['slug'])
            : ($record->slug ?: Str::slug($data['name']));

        if (blank($data['initials'] ?? null)) {
            $data['initials'] = collect(explode(' ', (string) preg_replace('/[^A-Za-z ]/', '', $data['name'])))
                ->filter()->take(2)->map(fn ($w) => strtoupper($w[0]))->implode('');
        }

        if (($data['is_published'] ?? false) && blank($data['published_at'] ?? null)) {
            $data['published_at'] = now();
        }

        return $data;
    }

    protected function afterSave(Model $record, Request $request, bool $created): void
    {
        $record->channels()->sync($request->input('channel_ids', []));
    }

    protected function formExtras(Model $record): array
    {
        return [
            'channels' => MarketingChannel::ordered()->get()->mapWithKeys(fn ($c) => [$c->id => $c->name])->all(),
            'selectedChannels' => $record->exists ? $record->channels->pluck('id')->all() : [],
            'metrics' => $record->exists ? $record->metrics : collect(),
        ];
    }

    public function duplicate(Project $project): RedirectResponse
    {
        $this->authorize('create', Project::class);

        $copy = $project->replicate(['published_at']);
        $copy->name = $project->name.' (copy)';
        $copy->slug = Str::slug($project->slug.'-copy-'.Str::lower(Str::random(4)));
        $copy->is_published = false;
        $copy->is_featured = false;
        $copy->status = Project::STATUS_DRAFT;
        $copy->save();
        $copy->channels()->sync($project->channels->pluck('id'));

        $this->logger->log('duplicated', $copy, 'Project "'.$project->name.'" duplicated.');

        return redirect()
            ->route('admin.projects.edit', $copy)
            ->with('status', 'Project duplicated as a draft.');
    }

    public function togglePublish(Project $project): RedirectResponse
    {
        $this->authorize('update', $project);

        $publish = ! $project->is_published;

        $project->forceFill([
            'is_published' => $publish,
            'status' => $publish ? Project::STATUS_PUBLISHED : Project::STATUS_DRAFT,
            'published_at' => $publish ? ($project->published_at ?? now()) : $project->published_at,
        ])->save();

        $this->logger->log($publish ? 'published' : 'unpublished', $project, 'Project "'.$project->name.'" '.($publish ? 'published' : 'unpublished').'.');

        return back()->with('status', 'Project '.($publish ? 'published' : 'unpublished').'.');
    }

    public function restore(Project $project): RedirectResponse
    {
        $this->authorize('update', $project);

        $revision = $project->revisions()->first();

        if (! $revision) {
            return back()->withErrors(['revision' => 'No earlier project version is stored.']);
        }

        $project->forceFill(collect($revision->payload)->only($project->getFillable())->all())->save();
        $revision->delete();
        $this->logger->log('restored', $project, 'Project “'.$project->name.'” restored to its previous version.');

        return back()->with('status', 'Previous project version restored.');
    }
}
