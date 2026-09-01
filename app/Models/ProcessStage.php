<?php

namespace App\Models;

use App\Models\Concerns\ClearsSiteCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessStage extends Model
{
    use ClearsSiteCache, HasFactory;

    public const TRACK_BUILD = 'build';
    public const TRACK_GROWTH = 'growth';

    protected $fillable = [
        'name', 'slug', 'number', 'track', 'body', 'accent', 'sort_order', 'is_published',
    ];

    protected function casts(): array
    {
        return ['is_published' => 'boolean'];
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeTrack($query, string $track)
    {
        return $query->where('track', $track);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}
