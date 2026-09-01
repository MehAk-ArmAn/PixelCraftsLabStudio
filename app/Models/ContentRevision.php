<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ContentRevision extends Model
{
    protected $fillable = [
        'revisionable_type', 'revisionable_id', 'user_id', 'user_name', 'payload', 'summary',
    ];

    protected function casts(): array
    {
        return ['payload' => 'array'];
    }

    public function revisionable(): MorphTo
    {
        return $this->morphTo();
    }

    /** Keep the history shallow — this is an undo buffer, not version control. */
    public static function prune(string $type, int $id, int $keep = 10): void
    {
        $ids = static::query()
            ->where('revisionable_type', $type)
            ->where('revisionable_id', $id)
            ->orderByDesc('id')
            ->skip($keep)
            ->take(100)
            ->pluck('id');

        if ($ids->isNotEmpty()) {
            static::whereIn('id', $ids)->delete();
        }
    }
}
