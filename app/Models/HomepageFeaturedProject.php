<?php

namespace App\Models;

use App\Models\Concerns\ClearsSiteCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HomepageFeaturedProject extends Model
{
    use ClearsSiteCache, HasFactory;

    public const DISPLAY_MODES = [
        'auto' => 'Automatic',
        'platform' => 'Platform stage',
        'cross-platform' => 'Cross-platform stage',
        'ecosystem' => 'Product ecosystem',
        'editorial' => 'Editorial feature',
    ];

    public const MEDIA_MODES = [
        'auto' => 'Automatic priority',
        'hero' => 'Hero image',
        'feature' => 'Feature graphic',
        'icon' => 'Project icon',
        'gallery' => 'Gallery stack',
    ];

    protected $fillable = [
        'project_id', 'slot', 'sort_order', 'is_primary', 'enabled',
        'display_mode', 'media_mode', 'badge_text', 'cta_label', 'settings',
    ];

    protected function casts(): array
    {
        return [
            'slot' => 'integer',
            'sort_order' => 'integer',
            'is_primary' => 'boolean',
            'enabled' => 'boolean',
            'settings' => 'array',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('slot')->orderBy('id');
    }
}
