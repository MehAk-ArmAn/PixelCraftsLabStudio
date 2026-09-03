<?php

namespace App\Models;

use App\Models\Concerns\ClearsSiteCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Service extends Model
{
    use ClearsSiteCache, HasFactory;

    public const TRACK_BUILD = 'build';
    public const TRACK_GROWTH = 'growth';

    protected $fillable = [
        'slug', 'title', 'stage', 'track', 'group', 'parent_id', 'tag', 'body', 'long_body',
        'caption', 'icon', 'cta_label', 'cta_url', 'sort_order', 'is_published', 'is_featured', 'show_on_homepage',
        'seo_title', 'seo_description',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'show_on_homepage' => 'boolean',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order')->orderBy('id');
    }

    public function channels(): MorphToMany
    {
        return $this->morphToMany(MarketingChannel::class, 'assignable', 'channel_assignments')
            ->withTimestamps()
            ->orderBy('marketing_channels.sort_order');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeTrack($query, string $track)
    {
        return $query->where('track', $track);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public function isMarketing(): bool
    {
        return $this->track === self::TRACK_GROWTH;
    }
}
