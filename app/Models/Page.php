<?php

namespace App\Models;

use App\Models\Concerns\ClearsSiteCache;
use App\Models\Concerns\HasRevisions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    use ClearsSiteCache, HasFactory, HasRevisions;

    protected $fillable = [
        'key', 'title', 'slug', 'is_published', 'sort_order',
        'seo_title', 'seo_description', 'og_title', 'og_description',
        'og_image', 'canonical_url', 'robots_index',
    ];

    protected function casts(): array
    {
        return ['is_published' => 'boolean', 'robots_index' => 'boolean'];
    }

    public function sections(): HasMany
    {
        return $this->hasMany(PageSection::class)->orderBy('sort_order')->orderBy('id');
    }

    public function section(string $key): ?PageSection
    {
        return $this->sections->firstWhere('section_key', $key);
    }
}
