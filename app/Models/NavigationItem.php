<?php

namespace App\Models;

use App\Models\Concerns\ClearsSiteCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NavigationItem extends Model
{
    use ClearsSiteCache, HasFactory;

    protected $fillable = [
        'label', 'route_key', 'destination', 'number', 'sort_order',
        'is_visible', 'show_desktop', 'show_mobile', 'show_footer',
        'is_external', 'open_new_tab',
    ];

    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
            'show_desktop' => 'boolean',
            'show_mobile' => 'boolean',
            'show_footer' => 'boolean',
            'is_external' => 'boolean',
            'open_new_tab' => 'boolean',
        ];
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}
