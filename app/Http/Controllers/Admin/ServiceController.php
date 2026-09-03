<?php

namespace App\Http\Controllers\Admin;

use App\Models\MarketingChannel;
use App\Models\ProcessStage;
use App\Models\Service;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ServiceController extends AdminResourceController
{
    /** Overridden by MarketingServiceController. */
    protected function track(): string
    {
        return Service::TRACK_BUILD;
    }

    protected function model(): string
    {
        return Service::class;
    }

    protected function routeBase(): string
    {
        return 'services';
    }

    protected function title(): string
    {
        return 'Services';
    }

    protected function searchable(): array
    {
        return ['title', 'slug', 'stage', 'body'];
    }

    protected function baseQuery(): Builder
    {
        return Service::query()->where('track', $this->track());
    }

    protected function listColumns(): array
    {
        return [
            ['key' => 'title', 'label' => 'Service'],
            ['key' => 'stage', 'label' => 'Stage'],
            ['key' => 'group', 'label' => 'Group'],
            ['key' => 'is_published', 'label' => 'Published', 'type' => 'bool'],
            ['key' => 'is_featured', 'label' => 'Featured', 'type' => 'bool'],
        ];
    }

    protected function schema(): array
    {
        return [
            ['name' => 'title', 'label' => 'Title', 'type' => 'text', 'required' => true, 'section' => 'Content'],
            ['name' => 'slug', 'label' => 'Slug', 'type' => 'text', 'section' => 'Content'],
            ['name' => 'stage', 'label' => 'Process stage', 'type' => 'select', 'section' => 'Content', 'optionsFrom' => 'stages', 'help' => 'Groups the service under a stage on the Services page.'],
            ['name' => 'parent_id', 'label' => 'Parent service', 'type' => 'select', 'section' => 'Content', 'optionsFrom' => 'parents', 'help' => 'Set to make this a sub-service.'],
            ['name' => 'group', 'label' => 'Group', 'type' => 'text', 'section' => 'Content', 'help' => 'Optional grouping label, e.g. "Social", "SEO", "Paid".'],
            ['name' => 'tag', 'label' => 'Card tag', 'type' => 'text', 'section' => 'Content', 'help' => 'Small label above the title, e.g. "01 · Apps".'],
            ['name' => 'body', 'label' => 'Short body', 'type' => 'textarea', 'section' => 'Content', 'rows' => 3],
            ['name' => 'long_body', 'label' => 'Long body', 'type' => 'textarea', 'section' => 'Content', 'rows' => 5],
            ['name' => 'caption', 'label' => 'Caption', 'type' => 'text', 'section' => 'Content', 'help' => 'Small caption under the animated card.'],
            ['name' => 'icon', 'label' => 'Icon / image', 'type' => 'media', 'section' => 'Content'],
            ['name' => 'cta_label', 'label' => 'CTA label', 'type' => 'text', 'section' => 'Content'],
            ['name' => 'cta_url', 'label' => 'CTA destination', 'type' => 'text', 'section' => 'Content', 'help' => 'Use a real path such as /marketing or /contact.'],
            ['name' => 'channel_ids', 'label' => 'Channels', 'type' => 'checkboxes', 'section' => 'Content', 'optionsFrom' => 'channels'],

            ['name' => 'is_published', 'label' => 'Published', 'type' => 'checkbox', 'section' => 'Publishing'],
            ['name' => 'is_featured', 'label' => 'Featured', 'type' => 'checkbox', 'section' => 'Publishing'],
            ['name' => 'show_on_homepage', 'label' => 'Show on homepage', 'type' => 'checkbox', 'section' => 'Publishing'],
            ['name' => 'sort_order', 'label' => 'Sort order', 'type' => 'number', 'section' => 'Publishing'],

            ['name' => 'seo_title', 'label' => 'SEO title', 'type' => 'text', 'section' => 'SEO'],
            ['name' => 'seo_description', 'label' => 'Meta description', 'type' => 'textarea', 'section' => 'SEO', 'rows' => 2],
        ];
    }

    protected function rules(?Model $record = null): array
    {
        return [
            'title' => ['required', 'string', 'max:190'],
            'slug' => ['nullable', 'string', 'max:190', 'alpha_dash', Rule::unique('services', 'slug')->ignore($record?->getKey())],
            'stage' => ['nullable', 'string', 'max:64'],
            'parent_id' => ['nullable', 'integer', 'exists:services,id'],
            'group' => ['nullable', 'string', 'max:64'],
            'tag' => ['nullable', 'string', 'max:120'],
            'body' => ['nullable', 'string', 'max:3000'],
            'long_body' => ['nullable', 'string', 'max:8000'],
            'caption' => ['nullable', 'string', 'max:190'],
            'icon' => ['nullable', 'string', 'max:255'],
            'cta_label' => ['nullable', 'string', 'max:120'],
            'cta_url' => ['nullable', 'string', 'max:255'],
            'channel_ids' => ['nullable', 'array'],
            'channel_ids.*' => ['integer', 'exists:marketing_channels,id'],
            'is_published' => ['boolean'],
            'is_featured' => ['boolean'],
            'show_on_homepage' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:100000'],
            'seo_title' => ['nullable', 'string', 'max:190'],
            'seo_description' => ['nullable', 'string', 'max:400'],
        ];
    }

    protected function transform(array $data, Model $record): array
    {
        unset($data['channel_ids']);

        $data['track'] = $this->track();
        $data['slug'] = filled($data['slug'] ?? null)
            ? Str::slug($data['slug'])
            : ($record->slug ?: Str::slug($data['title']));

        if ((int) ($data['parent_id'] ?? 0) === (int) $record->getKey()) {
            $data['parent_id'] = null;
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
            'stages' => ProcessStage::ordered()->get()->mapWithKeys(fn ($s) => [$s->name => $s->name.' ('.$s->track.')'])->all(),
            'parents' => Service::query()
                ->where('track', $this->track())
                ->when($record->exists, fn ($q) => $q->whereKeyNot($record->getKey()))
                ->ordered()
                ->get()
                ->mapWithKeys(fn ($s) => [$s->id => $s->title])
                ->all(),
            'channels' => MarketingChannel::ordered()->get()->mapWithKeys(fn ($c) => [$c->id => $c->name])->all(),
            'selectedChannels' => $record->exists ? $record->channels->pluck('id')->all() : [],
        ];
    }
}
