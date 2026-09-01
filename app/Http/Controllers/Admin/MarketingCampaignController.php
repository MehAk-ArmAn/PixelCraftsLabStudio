<?php

namespace App\Http\Controllers\Admin;

use App\Models\MarketingCampaign;
use App\Models\MarketingChannel;
use App\Models\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MarketingCampaignController extends AdminResourceController
{
    protected function model(): string
    {
        return MarketingCampaign::class;
    }

    protected function routeBase(): string
    {
        return 'campaigns';
    }

    protected function title(): string
    {
        return 'Campaigns';
    }

    protected function singular(): string
    {
        return 'Campaign';
    }

    protected function intro(): ?string
    {
        return 'A record of marketing work for showcasing and internal tracking. This is not an ad-buying tool — '
            .'campaigns are planned and bought in the platforms themselves.';
    }

    protected function searchable(): array
    {
        return ['name', 'client_name', 'campaign_type'];
    }

    protected function listColumns(): array
    {
        return [
            ['key' => 'name', 'label' => 'Campaign'],
            ['key' => 'client_name', 'label' => 'Client'],
            ['key' => 'status', 'label' => 'Status', 'type' => 'badge'],
            ['key' => 'is_published', 'label' => 'Published', 'type' => 'bool'],
        ];
    }

    protected function schema(): array
    {
        return [
            ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true, 'section' => 'Campaign'],
            ['name' => 'slug', 'label' => 'Slug', 'type' => 'text', 'section' => 'Campaign'],
            ['name' => 'project_id', 'label' => 'Related project', 'type' => 'select', 'optionsFrom' => 'projects', 'section' => 'Campaign'],
            ['name' => 'client_name', 'label' => 'Client', 'type' => 'text', 'section' => 'Campaign'],
            ['name' => 'campaign_type', 'label' => 'Type', 'type' => 'text', 'section' => 'Campaign', 'datalist' => ['Launch', 'Always-on social', 'Paid social', 'Search', 'Content', 'Email', 'Awareness']],
            ['name' => 'goal', 'label' => 'Goal', 'type' => 'text', 'section' => 'Campaign'],
            ['name' => 'channel_ids', 'label' => 'Channels', 'type' => 'checkboxes', 'optionsFrom' => 'channels', 'section' => 'Campaign'],
            ['name' => 'starts_on', 'label' => 'Starts', 'type' => 'date', 'section' => 'Campaign'],
            ['name' => 'ends_on', 'label' => 'Ends', 'type' => 'date', 'section' => 'Campaign'],
            ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'section' => 'Campaign', 'options' => [
                'planning' => 'Planning', 'active' => 'Active', 'completed' => 'Completed',
                'paused' => 'Paused', 'archived' => 'Archived',
            ]],

            ['name' => 'summary', 'label' => 'Summary', 'type' => 'textarea', 'rows' => 3, 'section' => 'Detail'],
            ['name' => 'strategy', 'label' => 'Strategy', 'type' => 'textarea', 'rows' => 4, 'section' => 'Detail'],
            ['name' => 'creative_approach', 'label' => 'Creative approach', 'type' => 'textarea', 'rows' => 4, 'section' => 'Detail'],
            ['name' => 'results', 'label' => 'Results', 'type' => 'textarea', 'rows' => 4, 'section' => 'Detail', 'help' => 'Only real, approved outcomes.'],

            ['name' => 'is_published', 'label' => 'Published', 'type' => 'checkbox', 'section' => 'Publishing'],
            ['name' => 'is_featured', 'label' => 'Featured', 'type' => 'checkbox', 'section' => 'Publishing'],
            ['name' => 'sort_order', 'label' => 'Sort order', 'type' => 'number', 'section' => 'Publishing'],
        ];
    }

    protected function rules(?Model $record = null): array
    {
        return [
            'name' => ['required', 'string', 'max:190'],
            'slug' => ['nullable', 'string', 'max:190', 'alpha_dash', Rule::unique('marketing_campaigns', 'slug')->ignore($record?->getKey())],
            'project_id' => ['nullable', 'integer', 'exists:projects,id'],
            'client_name' => ['nullable', 'string', 'max:190'],
            'campaign_type' => ['nullable', 'string', 'max:120'],
            'goal' => ['nullable', 'string', 'max:255'],
            'channel_ids' => ['nullable', 'array'],
            'channel_ids.*' => ['integer', 'exists:marketing_channels,id'],
            'starts_on' => ['nullable', 'date'],
            'ends_on' => ['nullable', 'date', 'after_or_equal:starts_on'],
            'status' => ['required', Rule::in(MarketingCampaign::STATUSES)],
            'summary' => ['nullable', 'string', 'max:2000'],
            'strategy' => ['nullable', 'string', 'max:5000'],
            'creative_approach' => ['nullable', 'string', 'max:5000'],
            'results' => ['nullable', 'string', 'max:5000'],
            'is_published' => ['boolean'],
            'is_featured' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:100000'],
        ];
    }

    protected function transform(array $data, Model $record): array
    {
        unset($data['channel_ids']);

        $data['slug'] = filled($data['slug'] ?? null)
            ? Str::slug($data['slug'])
            : ($record->slug ?: Str::slug($data['name']));

        return $data;
    }

    protected function afterSave(Model $record, Request $request, bool $created): void
    {
        $record->channels()->sync($request->input('channel_ids', []));
    }

    protected function formExtras(Model $record): array
    {
        return [
            'projects' => Project::ordered()->pluck('name', 'id')->all(),
            'channels' => MarketingChannel::ordered()->pluck('name', 'id')->all(),
            'selectedChannels' => $record->exists ? $record->channels->pluck('id')->all() : [],
        ];
    }
}
