<?php

namespace App\Models;

use App\Models\Concerns\ClearsSiteCache;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    use ClearsSiteCache, HasFactory;

    /** @var array<string, mixed> */
    protected $attributes = [
        'category' => 'Other',
        'billing_type' => 'custom',
        'currency' => 'AED',
        'price_presentation' => 'estimated',
        'promotion_eligible' => true,
        'founding_eligible' => true,
        'is_published' => true,
    ];

    public const BILLING_TYPES = ['one_time', 'monthly', 'project', 'custom'];

    public const PRICE_PRESENTATIONS = ['estimated', 'from', 'estimated_from', 'custom'];

    public const CATEGORIES = [
        'Growth Bundles', 'Social Media', 'Content', 'Paid Media', 'SEO',
        'WhatsApp', 'Email/Automation', 'Launch', 'Strategy', 'Other',
    ];

    protected $fillable = [
        'name', 'public_name', 'slug', 'internal_code', 'category', 'billing_type', 'price', 'currency', 'billing_period',
        'is_starting_from', 'short_description', 'full_description', 'is_featured',
        'is_recommended', 'badge', 'cta_label', 'cta_url', 'sort_order', 'is_published',
        'price_presentation', 'original_price', 'promotional_price', 'minimum_fee',
        'promotion_label', 'promotion_eligible', 'founding_eligible', 'terms',
        'media_spend_separated', 'minimum_term', 'seo_title', 'seo_description',
        'package_scope', 'internal_details',
    ];

    protected $hidden = [
        'internal_code',
        'minimum_fee',
        'promotion_eligible',
        'founding_eligible',
        'package_scope',
        'internal_details',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'original_price' => 'decimal:2',
            'promotional_price' => 'decimal:2',
            'minimum_fee' => 'decimal:2',
            'is_starting_from' => 'boolean',
            'promotion_eligible' => 'boolean',
            'founding_eligible' => 'boolean',
            'is_featured' => 'boolean',
            'is_recommended' => 'boolean',
            'is_published' => 'boolean',
            'media_spend_separated' => 'boolean',
            'package_scope' => 'array',
            'internal_details' => 'array',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(PackageItem::class)->orderBy('sort_order')->orderBy('id');
    }

    public function displayName(): string
    {
        return (string) ($this->public_name ?: $this->name);
    }

    public function pricePresentationLabel(): string
    {
        if ($this->billing_type === 'custom' || $this->price_presentation === 'custom' || $this->price === null) {
            return 'Custom';
        }

        return match ($this->price_presentation) {
            'from' => 'From',
            'estimated_from' => 'Estimated from',
            default => 'Estimated',
        };
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}
