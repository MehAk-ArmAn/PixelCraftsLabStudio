<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaLibraryService
{
    public const MAX_KILOBYTES = 20480; // 20 MB

    public const IMAGE_MIMES = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif'];

    /** Existing trusted artwork can be registered without allowing active SVG uploads. */
    public const LEGACY_IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'avif'];

    public const VIDEO_MIMES = ['mp4', 'webm', 'ogg'];

    public function store(UploadedFile $file, string $folder = 'library', array $meta = []): Media
    {
        $folder = trim(preg_replace('/[^a-z0-9\-_\/]/i', '', $folder) ?: 'library', '/');
        $name = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $name = $name !== '' ? $name : 'file';
        $filename = $name.'-'.Str::lower(Str::random(8)).'.'.Str::lower($file->getClientOriginalExtension());

        $path = $file->storeAs($folder, $filename, ['disk' => 'public']);

        [$width, $height] = $this->dimensions($path);

        return Media::create([
            'disk' => 'public',
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'title' => $meta['title'] ?? Str::headline($name),
            'alt_text' => $meta['alt_text'] ?? null,
            'caption' => $meta['caption'] ?? null,
            'mime_type' => $file->getClientMimeType(),
            'size_bytes' => $file->getSize() ?: 0,
            'width' => $width,
            'height' => $height,
            'is_legacy' => false,
            'uploaded_by' => Auth::id(),
        ]);
    }

    public function replace(Media $media, UploadedFile $file): Media
    {
        $folder = trim(dirname($media->path), '.') ?: 'library';
        $fresh = $this->store($file, $folder);
        $oldDisk = $media->disk;
        $oldPath = $media->path;
        $oldIsLegacy = $media->is_legacy;

        try {
            $media->update([
                'path' => $fresh->path,
                'mime_type' => $fresh->mime_type,
                'size_bytes' => $fresh->size_bytes,
                'width' => $fresh->width,
                'height' => $fresh->height,
                'original_name' => $fresh->original_name,
                'is_legacy' => false,
            ]);

            $fresh->delete();
        } catch (\Throwable $exception) {
            Storage::disk($fresh->disk)->delete($fresh->path);
            $fresh->delete();

            throw $exception;
        }

        if (! $oldIsLegacy && Storage::disk($oldDisk)->exists($oldPath)) {
            Storage::disk($oldDisk)->delete($oldPath);
        }

        return $media->refresh();
    }

    public function delete(Media $media): void
    {
        if (! $media->is_legacy && Storage::disk($media->disk)->exists($media->path)) {
            Storage::disk($media->disk)->delete($media->path);
        }

        $media->delete();
    }

    /**
     * Registers files that already live in public/ so admins can pick them in
     * the same media picker as new uploads without moving anything.
     */
    public function importLegacyAssets(array $directories = ['assets', 'uploads']): int
    {
        $created = 0;

        foreach ($directories as $dir) {
            $base = public_path($dir);

            if (! is_dir($base)) {
                continue;
            }

            foreach (File::allFiles($base) as $file) {
                $ext = Str::lower($file->getExtension());

                if (! in_array($ext, array_merge(self::LEGACY_IMAGE_EXTENSIONS, self::VIDEO_MIMES), true)) {
                    continue;
                }

                $relativePath = str_replace(DIRECTORY_SEPARATOR, '/', $file->getRelativePathname());
                $path = trim($dir, '/').'/'.$relativePath;

                if (Media::where('path', $path)->where('is_legacy', true)->exists()) {
                    continue;
                }

                [$width, $height] = $this->legacyDimensions($file->getPathname());

                Media::create([
                    'disk' => 'public',
                    'path' => $path,
                    'original_name' => $file->getFilename(),
                    'title' => Str::headline($file->getFilenameWithoutExtension()),
                    'mime_type' => $this->guessMime($ext),
                    'size_bytes' => $file->getSize(),
                    'width' => $width,
                    'height' => $height,
                    'is_legacy' => true,
                ]);

                $created++;
            }
        }

        return $created;
    }

    /** @return array{0: ?int, 1: ?int} */
    private function dimensions(string $path): array
    {
        try {
            $full = Storage::disk('public')->path($path);
            $size = @getimagesize($full);

            return $size ? [(int) $size[0], (int) $size[1]] : [null, null];
        } catch (\Throwable) {
            return [null, null];
        }
    }

    /** @return array{0: ?int, 1: ?int} */
    private function legacyDimensions(string $path): array
    {
        try {
            $size = @getimagesize($path);

            return $size ? [(int) $size[0], (int) $size[1]] : [null, null];
        } catch (\Throwable) {
            return [null, null];
        }
    }

    private function guessMime(string $ext): string
    {
        return match ($ext) {
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'avif' => 'image/avif',
            'svg' => 'image/svg+xml',
            'mp4' => 'video/mp4',
            'webm' => 'video/webm',
            'ogg' => 'video/ogg',
            default => 'image/jpeg',
        };
    }
}
