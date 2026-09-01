<?php

namespace App\Models;

use App\Models\Concerns\ClearsSiteCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class MarketingCampaign extends Model
{
    use ClearsSiteCache, HasFactory;

    public const STATUSES = ['planning', 'active', 'completed', 'paused', 'archived'];

    protected $fillable = [
        'name', 'slug', 'project_id', 'client_name', 'campaign_type', 'goal',
        'starts_on', 'ends_on', 'status', 'summary', 'strategy', 'creative_approach',
        'results', 'is_published', 'is_featured', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'starts_on' => 'date',
            'ends_on' => 'date',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
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

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}
