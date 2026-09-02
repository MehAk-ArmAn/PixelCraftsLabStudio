<?php

namespace App\Models;

use App\Models\Concerns\ClearsSiteCache;
use App\Models\Concerns\HasRevisions;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use ClearsSiteCache, HasRevisions;

    protected $fillable = ['key', 'group', 'type', 'value', 'label', 'hint', 'sort_order'];

    public function typedValue(): mixed
    {
        return match ($this->type) {
            'bool' => filter_var($this->value, FILTER_VALIDATE_BOOLEAN),
            'int' => (int) $this->value,
            'json' => json_decode((string) $this->value, true) ?: [],
            default => (string) ($this->value ?? ''),
        };
    }
}
