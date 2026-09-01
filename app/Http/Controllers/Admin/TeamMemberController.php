<?php

namespace App\Http\Controllers\Admin;

use App\Models\TeamMember;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TeamMemberController extends AdminResourceController
{
    protected function model(): string
    {
        return TeamMember::class;
    }

    protected function routeBase(): string
    {
        return 'team';
    }

    protected function title(): string
    {
        return 'Team';
    }

    protected function singular(): string
    {
        return 'Team member';
    }

    protected function searchable(): array
    {
        return ['name', 'role', 'email'];
    }

    protected function listColumns(): array
    {
        return [
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'role', 'label' => 'Role'],
            ['key' => 'is_published', 'label' => 'Published', 'type' => 'bool'],
            ['key' => 'is_featured', 'label' => 'Featured', 'type' => 'bool'],
        ];
    }

    protected function schema(): array
    {
        return [
            ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true, 'section' => 'Profile'],
            ['name' => 'slug', 'label' => 'Slug', 'type' => 'text', 'section' => 'Profile'],
            ['name' => 'role', 'label' => 'Role / title', 'type' => 'text', 'section' => 'Profile'],
            ['name' => 'bio', 'label' => 'Bio', 'type' => 'textarea', 'section' => 'Profile', 'rows' => 4],
            ['name' => 'photo', 'label' => 'Photo', 'type' => 'media', 'section' => 'Profile'],
            ['name' => 'initials', 'label' => 'Initials', 'type' => 'text', 'section' => 'Profile'],
            ['name' => 'primary_tint', 'label' => 'Primary tint', 'type' => 'color', 'section' => 'Profile'],
            ['name' => 'secondary_tint', 'label' => 'Secondary tint', 'type' => 'color', 'section' => 'Profile'],
            ['name' => 'email', 'label' => 'Email', 'type' => 'text', 'section' => 'Profile'],
            ['name' => 'profile_url', 'label' => 'Profile URL', 'type' => 'text', 'section' => 'Profile'],
            ['name' => 'is_published', 'label' => 'Published', 'type' => 'checkbox', 'section' => 'Publishing'],
            ['name' => 'is_featured', 'label' => 'Featured', 'type' => 'checkbox', 'section' => 'Publishing'],
            ['name' => 'sort_order', 'label' => 'Sort order', 'type' => 'number', 'section' => 'Publishing'],
        ];
    }

    protected function rules(?Model $record = null): array
    {
        return [
            'name' => ['required', 'string', 'max:190'],
            'slug' => ['nullable', 'string', 'max:190', 'alpha_dash', Rule::unique('team_members', 'slug')->ignore($record?->getKey())],
            'role' => ['nullable', 'string', 'max:190'],
            'bio' => ['nullable', 'string', 'max:3000'],
            'photo' => ['nullable', 'string', 'max:255'],
            'initials' => ['nullable', 'string', 'max:8'],
            'primary_tint' => ['nullable', 'string', 'max:16'],
            'secondary_tint' => ['nullable', 'string', 'max:16'],
            'email' => ['nullable', 'email', 'max:190'],
            'profile_url' => ['nullable', 'url', 'max:255'],
            'is_published' => ['boolean'],
            'is_featured' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:100000'],
        ];
    }

    protected function transform(array $data, Model $record): array
    {
        $data['slug'] = filled($data['slug'] ?? null)
            ? Str::slug($data['slug'])
            : ($record->slug ?: Str::slug($data['name']));

        if (blank($data['initials'] ?? null)) {
            $data['initials'] = collect(explode(' ', $data['name']))
                ->filter()->map(fn ($w) => strtoupper($w[0]))->take(2)->implode('');
        }

        return $data;
    }
}
