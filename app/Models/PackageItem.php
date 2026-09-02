<?php

namespace App\Models;

use App\Models\Concerns\ClearsSiteCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageItem extends Model
{
    use ClearsSiteCache, HasFactory;

    protected $fillable = [
        'package_id', 'text', 'group', 'sort_order', 'is_included', 'is_highlighted',
    ];

    protected function casts(): array
    {
        return [
            'is_included' => 'boolean',
            'is_highlighted' => 'boolean',
        ];
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
}
