<?php

namespace App\Http\Controllers\Admin;

use App\Models\MarketingChannel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MarketingChannelController extends AdminResourceController
{
    protected function model(): string
    {
        return MarketingChannel::class;
    }

    protected function routeBase(): string
    {
        return 'channels';
    }

    protected function title(): string
    {
        return 'Marketing Channels';
    }

    protected function singular(): string
    {
        return 'Channel';
    }

    protected function intro(): ?string
    {
        return 'Channels can be attached to marketing services, growth plans, campaigns and case studies.';
    }

    protected function searchable(): array
    {
        return ['name', 'label'];
    }

    protected function listColumns(): array
    {
        return [
            ['key' => 'name', 'label' => 'Channel'],
            ['key' => 'label', 'label' => 'Label'],
            ['key' => 'is_enabled', 'label' => 'Enabled', 'type' => 'bool'],
        ];
    }

    protected function schema(): array
    {
        return [
            ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true],
            ['name' => 'slug', 'label' => 'Slug', 'type' => 'text'],
            ['name' => 'label', 'label' => 'Display label', 'type' => 'text'],
            ['name' => 'description', 'label' => 'Description', 'type' => 'textarea', 'rows' => 3],
            ['name' => 'accent', 'label' => 'Accent colour', 'type' => 'color'],
            ['name' => 'is_enabled', 'label' => 'Enabled', 'type' => 'checkbox'],
            ['name' => 'sort_order', 'label' => 'Sort order', 'type' => 'number'],
        ];
    }

    protected function rules(?Model $record = null): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:120', 'alpha_dash', Rule::unique('marketing_channels', 'slug')->ignore($record?->getKey())],
            'label' => ['nullable', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:1000'],
            'accent' => ['nullable', 'string', 'max:16'],
            'is_enabled' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:100000'],
        ];
    }

    protected function transform(array $data, Model $record): array
    {
        $data['slug'] = filled($data['slug'] ?? null)
            ? Str::slug($data['slug'])
            : ($record->slug ?: Str::slug($data['name']));

        return $data;
    }
}
