<?php

namespace App\Models;

use App\Models\Concerns\ClearsSiteCache;
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
        'is_published' => true,
    ];

    public const BILLING_TYPES = ['one_time', 'monthly', 'project', 'custom'];

    public const CATEGORIES = [
        'Growth Bundles', 'Social Media', 'Content', 'Paid Media', 'SEO',
        'WhatsApp', 'Email/Automation', 'Launch', 'Strategy', 'Other',
    ];

    protected $fillable = [
        'name', 'slug', 'category', 'billing_type', 'price', 'currency', 'billing_period',
        'is_starting_from', 'short_description', 'full_description', 'is_featured',
        'is_recommended', 'badge', 'cta_label', 'cta_url', 'sort_order', 'is_published',
        'original_price', 'promotional_price', 'promotion_label', 'terms',
        'media_spend_separated', 'minimum_term', 'seo_title', 'seo_description',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'original_price' => 'decimal:2',
            'promotional_price' => 'decimal:2',
            'is_starting_from' => 'boolean',
            'is_featured' => 'boolean',
            'is_recommended' => 'boolean',
            'is_published' => 'boolean',
            'media_spend_separated' => 'boolean',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(PackageItem::class)->orderBy('sort_order')->orderBy('id');
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
