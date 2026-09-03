<?php

namespace App\Models;

use App\Models\Concerns\ClearsSiteCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InteractiveExperience extends Model
{
    use ClearsSiteCache, HasFactory;

    public const PAGES = [
        'home' => 'Home',
        'work' => 'Work',
        'project' => 'Project detail',
        'services' => 'Services',
        'marketing' => 'Marketing',
        'pricing' => 'Pricing',
        'studio' => 'Studio',
        'lab' => 'Lab',
        'contact' => 'Contact',
    ];

    public const TYPES = [
        'logo_assemble' => 'Logo assemble',
        'pixel_forge' => 'Pixel forge',
        'project_stack' => 'Project stack',
        'growth_network' => 'Growth network',
        'build_path' => 'Build path',
        'signal_field' => 'Signal field',
        'mini_launch' => 'Mini launch',
    ];

    public const ACCENTS = [
        'violet' => 'Violet',
        'orange' => 'Orange',
        'ink' => 'Ink',
        'violet-orange' => 'Violet + orange',
    ];

    protected $fillable = [
        'name', 'page', 'section_key', 'type', 'enabled', 'title', 'body',
        'cta_label', 'cta_url', 'accent_preset', 'intensity', 'sort_order', 'settings',
    ];

    protected function casts(): array
    {
        return [
            'enabled' => 'boolean',
            'intensity' => 'float',
            'sort_order' => 'integer',
            'settings' => 'array',
        ];
    }

    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    }

    public function scopeForPage($query, string $page)
    {
        return $query->where('page', $page);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}
