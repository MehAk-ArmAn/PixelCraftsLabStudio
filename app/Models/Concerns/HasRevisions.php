<?php

namespace App\Models\Concerns;

use App\Models\ContentRevision;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;

trait HasRevisions
{
    public function revisions(): MorphMany
    {
        return $this->morphMany(ContentRevision::class, 'revisionable')->latest('id');
    }

    /**
     * Store the attribute state that existed *before* the current save so an
     * admin can roll one step back.
     */
    public function recordRevision(?array $payload = null, ?string $summary = null): void
    {
        $user = Auth::user();

        ContentRevision::create([
            'revisionable_type' => static::class,
            'revisionable_id' => $this->getKey(),
            'user_id' => $user?->id,
            'user_name' => $user?->name,
            'payload' => $payload ?? $this->getOriginal(),
            'summary' => $summary,
        ]);

        ContentRevision::prune(static::class, (int) $this->getKey());
    }
}
