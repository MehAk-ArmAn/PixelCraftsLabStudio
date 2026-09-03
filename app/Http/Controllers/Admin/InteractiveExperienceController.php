<?php

namespace App\Http\Controllers\Admin;

use App\Models\InteractiveExperience;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

final class InteractiveExperienceController extends AdminResourceController
{
    protected function model(): string
    {
        return InteractiveExperience::class;
    }

    protected function routeBase(): string
    {
        return 'experiences';
    }

    protected function title(): string
    {
        return 'Interactive Experiences';
    }

    protected function intro(): ?string
    {
        return 'Safe visual and interaction presets only. No JavaScript or CSS is stored here.';
    }

    protected function searchable(): array
    {
        return ['name', 'page', 'section_key', 'type'];
    }

    protected function listColumns(): array
    {
        return [
            ['key' => 'name', 'label' => 'Experience'],
            ['key' => 'page', 'label' => 'Page', 'type' => 'badge'],
            ['key' => 'section_key', 'label' => 'Section'],
            ['key' => 'type', 'label' => 'Preset', 'type' => 'badge'],
            ['key' => 'enabled', 'label' => 'Enabled', 'type' => 'bool'],
        ];
    }

    protected function schema(): array
    {
        return [
            ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true, 'section' => 'Placement'],
            ['name' => 'page', 'label' => 'Page', 'type' => 'select', 'required' => true, 'section' => 'Placement', 'options' => InteractiveExperience::PAGES],
            ['name' => 'section_key', 'label' => 'Section key', 'type' => 'text', 'required' => true, 'section' => 'Placement'],
            ['name' => 'type', 'label' => 'Experience preset', 'type' => 'select', 'required' => true, 'section' => 'Placement', 'options' => InteractiveExperience::TYPES],
            ['name' => 'title', 'label' => 'Title', 'type' => 'text', 'section' => 'Content'],
            ['name' => 'body', 'label' => 'Body', 'type' => 'textarea', 'rows' => 3, 'section' => 'Content'],
            ['name' => 'cta_label', 'label' => 'CTA label', 'type' => 'text', 'section' => 'Content'],
            ['name' => 'cta_url', 'label' => 'CTA destination', 'type' => 'text', 'section' => 'Content'],
            ['name' => 'accent_preset', 'label' => 'Accent preset', 'type' => 'select', 'required' => true, 'section' => 'Presentation', 'options' => InteractiveExperience::ACCENTS],
            ['name' => 'intensity', 'label' => 'Intensity (0–1.6)', 'type' => 'number', 'section' => 'Presentation'],
            ['name' => 'enabled', 'label' => 'Enabled', 'type' => 'checkbox', 'section' => 'Publishing'],
            ['name' => 'sort_order', 'label' => 'Sort order', 'type' => 'number', 'section' => 'Publishing'],
        ];
    }

    protected function rules(?Model $record = null): array
    {
        return [
            'name' => ['required', 'string', 'max:190'],
            'page' => ['required', Rule::in(array_keys(InteractiveExperience::PAGES))],
            'section_key' => ['required', 'string', 'max:96', 'alpha_dash'],
            'type' => ['required', Rule::in(array_keys(InteractiveExperience::TYPES))],
            'title' => ['nullable', 'string', 'max:255'],
            'body' => ['nullable', 'string', 'max:3000'],
            'cta_label' => ['nullable', 'string', 'max:120'],
            'cta_url' => ['nullable', 'string', 'max:255'],
            'accent_preset' => ['required', Rule::in(array_keys(InteractiveExperience::ACCENTS))],
            'intensity' => ['nullable', 'numeric', 'min:0', 'max:1.6'],
            'enabled' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:100000'],
        ];
    }
}
