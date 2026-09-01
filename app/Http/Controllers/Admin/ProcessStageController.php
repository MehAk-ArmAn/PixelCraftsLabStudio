<?php

namespace App\Http\Controllers\Admin;

use App\Models\ProcessStage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProcessStageController extends AdminResourceController
{
    protected function model(): string
    {
        return ProcessStage::class;
    }

    protected function routeBase(): string
    {
        return 'process';
    }

    protected function title(): string
    {
        return 'Process Stages';
    }

    protected function singular(): string
    {
        return 'Process stage';
    }

    protected function intro(): ?string
    {
        return 'The build track drives the Services page stage picker. The growth track drives the marketing process on the Growth page.';
    }

    protected function searchable(): array
    {
        return ['name', 'body'];
    }

    protected function listColumns(): array
    {
        return [
            ['key' => 'number', 'label' => 'No'],
            ['key' => 'name', 'label' => 'Stage'],
            ['key' => 'track', 'label' => 'Track'],
            ['key' => 'is_published', 'label' => 'Published', 'type' => 'bool'],
        ];
    }

    protected function schema(): array
    {
        return [
            ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true],
            ['name' => 'slug', 'label' => 'Slug', 'type' => 'text'],
            ['name' => 'number', 'label' => 'Number', 'type' => 'text', 'help' => 'e.g. 01'],
            ['name' => 'track', 'label' => 'Track', 'type' => 'select', 'options' => [
                ProcessStage::TRACK_BUILD => 'Build (studio delivery)',
                ProcessStage::TRACK_GROWTH => 'Growth (marketing)',
            ]],
            ['name' => 'body', 'label' => 'Description', 'type' => 'textarea', 'rows' => 5],
            ['name' => 'accent', 'label' => 'Accent colour', 'type' => 'color'],
            ['name' => 'is_published', 'label' => 'Published', 'type' => 'checkbox'],
            ['name' => 'sort_order', 'label' => 'Sort order', 'type' => 'number'],
        ];
    }

    protected function rules(?Model $record = null): array
    {
        return [
            'name' => ['required', 'string', 'max:64'],
            'slug' => ['nullable', 'string', 'max:64', 'alpha_dash', Rule::unique('process_stages', 'slug')->ignore($record?->getKey())],
            'number' => ['nullable', 'string', 'max:8'],
            'track' => ['required', Rule::in([ProcessStage::TRACK_BUILD, ProcessStage::TRACK_GROWTH])],
            'body' => ['nullable', 'string', 'max:3000'],
            'accent' => ['nullable', 'string', 'max:16'],
            'is_published' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:100000'],
        ];
    }

    protected function transform(array $data, Model $record): array
    {
        $data['slug'] = filled($data['slug'] ?? null)
            ? Str::slug($data['slug'])
            : ($record->slug ?: Str::slug($data['track'].'-'.$data['name']));

        return $data;
    }
}
