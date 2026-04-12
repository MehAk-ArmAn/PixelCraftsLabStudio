<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NavItem extends Model
{
    protected $fillable = [
        'label',
        'url',
        'sort_order',
        'is_active',
        'is_button',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_button' => 'boolean',
    ];
}
