<?php

namespace App\Models;

use App\Models\Concerns\ClearsSiteCache;
use App\Support\MediaResolver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Testimonial extends Model
{
    use ClearsSiteCache, HasFactory;

    protected $fillable = [
        'client_name', 'company', 'role', 'quote', 'rating', 'source', 'source_url',
        'project_id', 'avatar', 'is_featured', 'is_published', 'sort_order',
    ];

    protected function casts(): array
    {
        return ['is_featured' => 'boolean', 'is_published' => 'boolean', 'rating' => 'integer'];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public function avatarUrl(): string
    {
        return MediaResolver::url($this->avatar);
    }
}
