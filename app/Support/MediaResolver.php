<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Resolves the three kinds of asset reference this project has to live with:
 *
 *  - "storage:projects/foo.png"  → an admin upload on the public disk
 *  - "assets/pcl-logo.png"       → a legacy file already sitting in public/
 *  - "https://…"                 → an absolute URL
 *
 * Anything unresolvable returns "" so the frontend keeps its existing
 * placeholder rendering instead of showing a broken image.
 */
final class MediaResolver
{
    public const STORAGE_PREFIX = 'storage:';

    public static function url(?string $reference): string
    {
        $reference = trim((string) $reference);

        if ($reference === '') {
            return '';
        }

        if (Str::startsWith($reference, ['http://', 'https://', 'data:'])) {
            return $reference;
        }

        if (Str::startsWith($reference, self::STORAGE_PREFIX)) {
            $path = ltrim(Str::after($reference, self::STORAGE_PREFIX), '/');

            return $path === '' ? '' : Storage::disk('public')->url($path);
        }

        $path = ltrim($reference, '/');

        // Legacy references such as "storage/team/x.png" already point at the
        // public storage symlink; keep them working untouched.
        if (Str::startsWith($path, 'storage/')) {
            return self::existsInPublic($path) ? '/'.$path : '';
        }

        return self::existsInPublic($path) ? '/'.$path : '';
    }

    public static function existsInPublic(string $path): bool
    {
        $full = public_path($path);

        return $full !== '' && is_file($full);
    }

    /** True when a reference points at an admin upload rather than a legacy file. */
    public static function isStorageReference(?string $reference): bool
    {
        return Str::startsWith((string) $reference, self::STORAGE_PREFIX);
    }

    public static function storagePath(?string $reference): ?string
    {
        return self::isStorageReference($reference)
            ? ltrim(Str::after((string) $reference, self::STORAGE_PREFIX), '/')
            : null;
    }
}
