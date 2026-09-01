<?php

namespace App\Models\Concerns;

use App\Services\SiteContentService;

trait ClearsSiteCache
{
    public static function bootClearsSiteCache(): void
    {
        $flush = static fn () => SiteContentService::flush();

        static::saved($flush);
        static::deleted($flush);

        if (method_exists(static::class, 'restored')) {
            static::restored($flush);
        }
    }
}
