<?php

namespace App\Models;

use App\Models\Concerns\ClearsSiteCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageSection extends Model
{
    use ClearsSiteCache, HasFactory;

    protected $fillable = [
        'page_id', 'section_key', 'label', 'eyebrow', 'heading', 'subheading', 'body',
        'cta_label', 'cta_url', 'secondary_cta_label', 'secondary_cta_url',
        'media', 'settings', 'sort_order', 'is_enabled',
    ];

    protected function casts(): array
    {
        return ['settings' => 'array', 'is_enabled' => 'boolean'];
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function setting(string $key, mixed $default = null): mixed
    {
        return data_get($this->settings ?? [], $key, $default);
    }
}
