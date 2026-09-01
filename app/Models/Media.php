<?php

namespace App\Models;

use App\Models\Concerns\ClearsSiteCache;
use App\Support\MediaResolver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Media extends Model
{
    use ClearsSiteCache, HasFactory;

    protected $table = 'media';

    protected $fillable = [
        'disk', 'path', 'original_name', 'title', 'alt_text', 'caption',
        'mime_type', 'size_bytes', 'width', 'height', 'is_legacy', 'uploaded_by',
    ];

    protected function casts(): array
    {
        return ['is_legacy' => 'boolean', 'size_bytes' => 'integer'];
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /** The value stored on content records (projects.primary_image etc.). */
    public function reference(): string
    {
        return $this->is_legacy ? $this->path : 'storage:'.$this->path;
    }

    public function url(): string
    {
        return MediaResolver::url($this->reference());
    }

    public function isImage(): bool
    {
        return str_starts_with((string) $this->mime_type, 'image/');
    }

    public function isVideo(): bool
    {
        return str_starts_with((string) $this->mime_type, 'video/');
    }

    public function humanSize(): string
    {
        $bytes = (int) $this->size_bytes;

        foreach (['B', 'KB', 'MB', 'GB'] as $unit) {
            if ($bytes < 1024) {
                return round($bytes, $unit === 'B' ? 0 : 1).' '.$unit;
            }
            $bytes /= 1024;
        }

        return round($bytes, 1).' TB';
    }
}
