<?php

namespace App\Http\Controllers\Admin;

use App\Models\SocialLink;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SocialLinkController extends AdminResourceController
{
    protected function model(): string
    {
        return SocialLink::class;
    }

    protected function routeBase(): string
    {
        return 'socials';
    }

    protected function title(): string
    {
        return 'Social Links';
    }

    protected function singular(): string
    {
        return 'Social link';
    }

    protected function searchable(): array
    {
        return ['platform', 'url', 'label'];
    }

    protected function listColumns(): array
    {
        return [
            ['key' => 'platform', 'label' => 'Platform'],
            ['key' => 'url', 'label' => 'URL'],
            ['key' => 'is_enabled', 'label' => 'Enabled', 'type' => 'bool'],
        ];
    }

    protected function schema(): array
    {
        return [
            ['name' => 'platform', 'label' => 'Platform', 'type' => 'text', 'required' => true],
            ['name' => 'slug', 'label' => 'Slug', 'type' => 'text'],
            ['name' => 'url', 'label' => 'URL', 'type' => 'text', 'required' => true],
            ['name' => 'label', 'label' => 'Display label', 'type' => 'text'],
            ['name' => 'is_enabled', 'label' => 'Enabled', 'type' => 'checkbox'],
            ['name' => 'sort_order', 'label' => 'Sort order', 'type' => 'number'],
        ];
    }

    protected function rules(?Model $record = null): array
    {
        return [
            'platform' => ['required', 'string', 'max:64'],
            'slug' => ['nullable', 'string', 'max:64', 'alpha_dash', Rule::unique('social_links', 'slug')->ignore($record?->getKey())],
            'url' => ['required', 'url', 'max:255'],
            'label' => ['nullable', 'string', 'max:120'],
            'is_enabled' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:100000'],
        ];
    }

    protected function transform(array $data, Model $record): array
    {
        $data['slug'] = filled($data['slug'] ?? null)
            ? Str::slug($data['slug'])
            : ($record->slug ?: Str::slug($data['platform']));

        return $data;
    }
}
