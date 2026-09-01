<?php

namespace App\Models;

use App\Models\Concerns\ClearsSiteCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GrowthPlanItem extends Model
{
    use ClearsSiteCache, HasFactory;

    protected $fillable = ['growth_plan_id', 'title', 'description', 'sort_order', 'is_enabled'];

    protected function casts(): array
    {
        return ['is_enabled' => 'boolean'];
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(GrowthPlan::class, 'growth_plan_id');
    }
}
