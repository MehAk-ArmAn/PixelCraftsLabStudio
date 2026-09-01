<?php

namespace App\Services;

use App\Models\SiteSetting;
use App\Support\MediaResolver;
use Illuminate\Support\Facades\Cache;

class SettingsRepository
{
    public const CACHE_KEY = 'pcl.settings.v1';

    /** @var array<string, mixed>|null */
    private ?array $loaded = null;

    /** @return array<string, mixed> */
    public function all(): array
    {
        if ($this->loaded !== null) {
            return $this->loaded;
        }

        $this->loaded = Cache::rememberForever(self::CACHE_KEY, function (): array {
            if (! $this->tableReady()) {
                return [];
            }

            return SiteSetting::query()
                ->get()
                ->mapWithKeys(fn (SiteSetting $s) => [$s->key => $s->typedValue()])
                ->all();
        });

        return $this->loaded;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $value = $this->all()[$key] ?? null;

        if ($value === null || $value === '') {
            return $default;
        }

        return $value;
    }

    public function bool(string $key, bool $default = true): bool
    {
        $all = $this->all();

        return array_key_exists($key, $all) ? (bool) $all[$key] : $default;
    }

    public function string(string $key, string $default = ''): string
    {
        return (string) $this->get($key, $default);
    }

    public function mediaUrl(string $key, string $default = ''): string
    {
        $url = MediaResolver::url((string) $this->get($key, ''));

        return $url !== '' ? $url : $default;
    }

    public function set(string $key, mixed $value, string $group = 'general', string $type = 'string'): SiteSetting
    {
        $stored = match ($type) {
            'bool' => $value ? '1' : '0',
            'json' => json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            default => (string) ($value ?? ''),
        };

        $setting = SiteSetting::updateOrCreate(
            ['key' => $key],
            ['value' => $stored, 'group' => $group, 'type' => $type],
        );

        $this->flush();

        return $setting;
    }

    /** Create only when absent — keeps seeding idempotent. */
    public function ensure(string $key, mixed $value, string $group, string $type, ?string $label = null, ?string $hint = null, int $sort = 0): SiteSetting
    {
        $stored = match ($type) {
            'bool' => $value ? '1' : '0',
            'json' => json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            default => (string) ($value ?? ''),
        };

        $setting = SiteSetting::firstOrNew(['key' => $key]);

        if (! $setting->exists) {
            $setting->value = $stored;
        }

        $setting->fill([
            'group' => $group,
            'type' => $type,
            'label' => $label ?? $setting->label,
            'hint' => $hint ?? $setting->hint,
            'sort_order' => $sort,
        ])->save();

        $this->flush();

        return $setting;
    }

    public function flush(): void
    {
        $this->loaded = null;
        Cache::forget(self::CACHE_KEY);
        SiteContentService::flush();
    }

    private function tableReady(): bool
    {
        try {
            return \Illuminate\Support\Facades\Schema::hasTable('site_settings');
        } catch (\Throwable) {
            return false;
        }
    }
}
