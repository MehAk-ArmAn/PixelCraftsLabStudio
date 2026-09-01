<?php

namespace App\Models;

use App\Models\Concerns\ClearsSiteCache;
use App\Models\Concerns\HasRevisions;
use App\Support\MediaResolver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Project extends Model
{
    use ClearsSiteCache, HasFactory, HasRevisions;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_HIDDEN = 'hidden';
    public const STATUS_ARCHIVED = 'archived';

    public const STATUSES = [
        self::STATUS_DRAFT, self::STATUS_PUBLISHED, self::STATUS_HIDDEN, self::STATUS_ARCHIVED,
    ];

    protected $fillable = [
        'slug', 'name', 'category', 'kind', 'platform', 'layout_size',
        'short_description', 'full_description', 'case_study',
        'client_goal', 'challenge', 'audience', 'strategy', 'approach',
        'deliverables', 'results', 'lessons', 'campaign_period',
        'external_url', 'status', 'is_featured', 'is_marketing_case_study',
        'is_published', 'is_archived', 'sort_order', 'primary_image', 'gallery',
        'initials', 'primary_tint', 'secondary_tint', 'cta_label', 'cta_url',
        'seo_title', 'seo_description', 'og_image', 'published_at',
    ];

    protected function casts(): array
    {
        return [
            'gallery' => 'array',
            'is_featured' => 'boolean',
            'is_marketing_case_study' => 'boolean',
            'is_published' => 'boolean',
            'is_archived' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function metrics(): HasMany
    {
        return $this->hasMany(ProjectMetric::class)->orderBy('sort_order')->orderBy('id');
    }

    public function testimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class);
    }

    public function campaigns(): HasMany
    {
        return $this->hasMany(MarketingCampaign::class);
    }

    public function channels(): MorphToMany
    {
        return $this->morphToMany(MarketingChannel::class, 'assignable', 'channel_assignments')
            ->withTimestamps()
            ->orderBy('marketing_channels.sort_order');
    }

    public function scopeLive($query)
    {
        return $query->where('is_published', true)
            ->where('is_archived', false)
            ->where('status', self::STATUS_PUBLISHED);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public function imageUrl(): string
    {
        return MediaResolver::url($this->primary_image);
    }

    public function galleryUrls(): array
    {
        return collect($this->gallery ?? [])
            ->map(fn ($ref) => MediaResolver::url($ref))
            ->filter()
            ->values()
            ->all();
    }

    public function isLive(): bool
    {
        return $this->is_published && ! $this->is_archived && $this->status === self::STATUS_PUBLISHED;
    }

    public function host(): string
    {
        if (! $this->external_url) {
            return 'in development';
        }

        return (string) preg_replace(['#^https?://#', '#/.*$#'], '', $this->external_url);
    }
}
