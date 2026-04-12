<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'site_name',
        'brand_tagline',
        'logo_path',
        'favicon_path',
        'admin_email',
        'phone',
        'location',
        'instagram',
        'linked_in',
        'x',
        'tiktok',
        'pinterest',
        'youtube',
        'facebook',
        'whatsapp',
        'footer_text',
    ];
}
