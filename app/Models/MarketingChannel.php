<?php

namespace App\Models;

use App\Models\Concerns\ClearsSiteCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class MarketingChannel extends Model
{
    use ClearsSiteCache, HasFactory;

    protected $fillable = [
        'name', 'slug', 'label', 'description', 'accent', 'sort_order', 'is_enabled',
    ];

    protected function casts(): array
    {
        return ['is_enabled' => 'boolean'];
    }

    public function services(): MorphToMany
    {
        return $this->morphedByMany(Service::class, 'assignable', 'channel_assignments');
    }

    public function projects(): MorphToMany
    {
        return $this->morphedByMany(Project::class, 'assignable', 'channel_assignments');
    }

    public function growthPlans(): MorphToMany
    {
        return $this->morphedByMany(GrowthPlan::class, 'assignable', 'channel_assignments');
    }

    public function scopeEnabled($query)
    {
        return $query->where('is_enabled', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}
