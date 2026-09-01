<?php

namespace App\Models;

use App\Models\Concerns\ClearsSiteCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class GrowthPlan extends Model
{
    use ClearsSiteCache, HasFactory;

    protected $fillable = [
        'name', 'slug', 'short_description', 'full_description', 'ideal_for', 'duration',
        'price_text', 'starting_price', 'billing_period', 'currency', 'highlight_text',
        'cta_label', 'cta_url', 'accent', 'is_featured', 'is_published', 'sort_order',
        'seo_title', 'seo_description',
    ];

    protected function casts(): array
    {
        return ['is_featured' => 'boolean', 'is_published' => 'boolean'];
    }

    public function items(): HasMany
    {
        return $this->hasMany(GrowthPlanItem::class)->orderBy('sort_order')->orderBy('id');
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

    /** Never invents a number — falls back to whatever the admin typed, else "Custom". */
    public function priceDisplay(): string
    {
        if (filled($this->price_text)) {
            return (string) $this->price_text;
        }

        if (filled($this->starting_price)) {
            return trim(($this->currency ?? '').$this->starting_price.' '.($this->billing_period ?? ''));
        }

        return 'Custom';
    }
}
