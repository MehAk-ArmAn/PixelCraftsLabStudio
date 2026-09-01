<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Services\ActivityLogger;
use App\Services\SettingsRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    /** Groups rendered as separate admin screens. */
    public const GROUPS = [
        'studio' => 'Studio',
        'contact' => 'Contact',
        'footer' => 'Footer',
        'features' => 'Features',
        'seo' => 'SEO',
    ];

    public function __construct(
        private readonly SettingsRepository $settings,
        private readonly ActivityLogger $logger,
    ) {}

    public function edit(string $group = 'studio'): View
    {
        $this->authorize('viewAny', SiteSetting::class);

        abort_unless(array_key_exists($group, self::GROUPS), 404);

        return view('admin.settings.edit', [
            'group' => $group,
            'groups' => self::GROUPS,
            'settings' => SiteSetting::query()
                ->where('group', $group)
                ->orderBy('sort_order')
                ->orderBy('key')
                ->get(),
        ]);
    }

    public function update(Request $request, string $group): RedirectResponse
    {
        $this->authorize('update', SiteSetting::class);

        abort_unless(array_key_exists($group, self::GROUPS), 404);

        $records = SiteSetting::query()->where('group', $group)->get();
        $before = $records->pluck('value', 'key')->all();

        $rules = [];

        foreach ($records as $setting) {
            $rules['values.'.$setting->key] = match ($setting->type) {
                'bool' => ['nullable'],
                'int' => ['nullable', 'integer'],
                'text' => ['nullable', 'string', 'max:8000'],
                default => ['nullable', 'string', 'max:1000'],
            };
        }

        $request->validate($rules);
        $values = $request->input('values', []);

        foreach ($records as $setting) {
            $raw = $values[$setting->key] ?? null;

            $setting->value = $setting->type === 'bool'
                ? ($request->boolean('values.'.$setting->key) ? '1' : '0')
                : (string) ($raw ?? '');

            $setting->save();
        }

        $this->settings->flush();

        $after = SiteSetting::query()->where('group', $group)->pluck('value', 'key')->all();
        $changed = array_keys(array_diff_assoc($after, $before));

        $this->logger->log(
            'updated',
            null,
            'Site settings ('.self::GROUPS[$group].') updated.',
            $changed === [] ? null : [
                'before' => array_intersect_key($before, array_flip($changed)),
                'after' => array_intersect_key($after, array_flip($changed)),
            ],
        );

        return back()->with('status', self::GROUPS[$group].' settings saved.');
    }
}
