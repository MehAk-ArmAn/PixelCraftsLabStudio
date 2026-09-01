<?php

namespace App\Models;

use App\Models\Concerns\ClearsSiteCache;
use App\Support\MediaResolver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use ClearsSiteCache, HasFactory;

    protected $fillable = [
        'name', 'slug', 'role', 'bio', 'photo', 'initials',
        'primary_tint', 'secondary_tint', 'email', 'profile_url',
        'sort_order', 'is_published', 'is_featured',
    ];

    protected function casts(): array
    {
        return ['is_published' => 'boolean', 'is_featured' => 'boolean'];
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public function photoUrl(): string
    {
        return MediaResolver::url($this->photo);
    }
}
