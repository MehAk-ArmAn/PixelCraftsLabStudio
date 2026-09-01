<?php

namespace App\Services;

use App\Models\AdminActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    /** Attributes never worth writing into an audit row. */
    private const IGNORED = ['updated_at', 'created_at', 'password', 'remember_token'];

    public function log(string $action, ?Model $resource = null, ?string $description = null, ?array $changes = null): void
    {
        $user = Auth::user();

        AdminActivityLog::create([
            'user_id' => $user?->id,
            'user_name' => $user?->name ?? 'system',
            'action' => $action,
            'resource_type' => $resource ? $resource::class : null,
            'resource_id' => $resource?->getKey(),
            'description' => $description,
            'changes' => $changes,
        ]);
    }

    public function logSaved(string $action, Model $resource, ?string $description = null): void
    {
        $this->log($action, $resource, $description, $this->diff($resource));
    }

    /** @return array{before: array<string, mixed>, after: array<string, mixed>}|null */
    public function diff(Model $model): ?array
    {
        $changed = collect($model->getChanges())
            ->except(self::IGNORED)
            ->map(fn ($v) => is_scalar($v) || $v === null ? $v : json_encode($v))
            ->all();

        if ($changed === []) {
            return null;
        }

        $before = collect($model->getOriginal())
            ->only(array_keys($changed))
            ->map(fn ($v) => is_scalar($v) || $v === null ? $v : json_encode($v))
            ->all();

        return ['before' => $before, 'after' => $changed];
    }
}
