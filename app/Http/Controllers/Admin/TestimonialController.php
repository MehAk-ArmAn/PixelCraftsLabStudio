<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Model;

class TestimonialController extends AdminResourceController
{
    protected function model(): string
    {
        return Testimonial::class;
    }

    protected function routeBase(): string
    {
        return 'testimonials';
    }

    protected function title(): string
    {
        return 'Testimonials';
    }

    protected function intro(): ?string
    {
        return 'Nothing is invented here. Until a real testimonial is published the studio page shows the '
            .'placeholder message configured under Pages → Studio → Reviews.';
    }

    protected function searchable(): array
    {
        return ['client_name', 'company', 'quote'];
    }

    protected function listColumns(): array
    {
        return [
            ['key' => 'client_name', 'label' => 'Client'],
            ['key' => 'company', 'label' => 'Company'],
            ['key' => 'is_published', 'label' => 'Published', 'type' => 'bool'],
            ['key' => 'is_featured', 'label' => 'Featured', 'type' => 'bool'],
        ];
    }

    protected function schema(): array
    {
        return [
            ['name' => 'client_name', 'label' => 'Client name', 'type' => 'text', 'required' => true],
            ['name' => 'company', 'label' => 'Company', 'type' => 'text'],
            ['name' => 'role', 'label' => 'Role', 'type' => 'text'],
            ['name' => 'quote', 'label' => 'Quote', 'type' => 'textarea', 'required' => true, 'rows' => 4],
            ['name' => 'rating', 'label' => 'Rating (1-5)', 'type' => 'number'],
            ['name' => 'source', 'label' => 'Source', 'type' => 'text', 'help' => 'e.g. Google Play, email, LinkedIn.'],
            ['name' => 'source_url', 'label' => 'Source URL', 'type' => 'text'],
            ['name' => 'project_id', 'label' => 'Related project', 'type' => 'select', 'optionsFrom' => 'projects'],
            ['name' => 'avatar', 'label' => 'Avatar / logo', 'type' => 'media'],
            ['name' => 'is_published', 'label' => 'Published', 'type' => 'checkbox'],
            ['name' => 'is_featured', 'label' => 'Featured', 'type' => 'checkbox'],
            ['name' => 'sort_order', 'label' => 'Sort order', 'type' => 'number'],
        ];
    }

    protected function rules(?Model $record = null): array
    {
        return [
            'client_name' => ['required', 'string', 'max:190'],
            'company' => ['nullable', 'string', 'max:190'],
            'role' => ['nullable', 'string', 'max:190'],
            'quote' => ['required', 'string', 'max:2000'],
            'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'source' => ['nullable', 'string', 'max:120'],
            'source_url' => ['nullable', 'url', 'max:255'],
            'project_id' => ['nullable', 'integer', 'exists:projects,id'],
            'avatar' => ['nullable', 'string', 'max:255'],
            'is_published' => ['boolean'],
            'is_featured' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:100000'],
        ];
    }

    protected function formExtras(Model $record): array
    {
        return ['projects' => Project::ordered()->pluck('name', 'id')->all()];
    }

    protected function recordLabel(Model $record): string
    {
        return $record->client_name;
    }
}
