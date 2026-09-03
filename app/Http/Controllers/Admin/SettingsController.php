<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Services\ActivityLogger;
use App\Services\SettingsRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SettingsController extends Controller
{
    /** Groups rendered as separate admin screens. */
    public const GROUPS = [
        'home' => 'Home · Landing Experience',
        'studio' => 'Studio',
        'contact' => 'Contact',
        'footer' => 'Footer',
        'features' => 'Features',
        'pricing' => 'Pricing & promotions',
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
                ->withExists('revisions')
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
            $rules['values.'.$setting->key] = match ($setting->key) {
                'home_intro_mode' => ['nullable', Rule::in(['forge', 'minimal'])],
                'home_intro_duration' => ['nullable', 'integer', 'min:900', 'max:6000'],
                'home_intro_intensity' => ['nullable', 'numeric', 'min:0', 'max:1.6'],
                'home_intro_accent_preset' => ['nullable', Rule::in(['violet-orange', 'violet', 'orange', 'ink'])],
                'home_intro_interaction_preset' => ['nullable', Rule::in(['pointer-parallax', 'static'])],
                'home_intro_background_preset' => ['nullable', Rule::in(['paper-grid', 'quiet'])],
                'home_intro_transition_preset' => ['nullable', Rule::in(['scatter', 'fade'])],
                'founding_client_discount_percent' => ['nullable', 'integer', 'min:0', 'max:100'],
                'founding_client_duration_months' => ['nullable', 'integer', 'min:0', 'max:120'],
                'founding_client_limit', 'founding_client_claimed_count' => ['nullable', 'integer', 'min:0', 'max:1000000'],
                'founding_client_starts_on', 'founding_client_ends_on' => ['nullable', 'date_format:Y-m-d'],
                default => match ($setting->type) {
                    'bool' => ['nullable'],
                    'int' => ['nullable', 'integer'],
                    'text' => ['nullable', 'string', 'max:8000'],
                    default => ['nullable', 'string', 'max:1000'],
                },
            };
        }

        $request->validate($rules);
        $values = $request->input('values', []);

        foreach ($records as $setting) {
            $raw = $values[$setting->key] ?? null;

            $value = $setting->type === 'bool'
                ? ($request->boolean('values.'.$setting->key) ? '1' : '0')
                : (string) ($raw ?? '');

            if ((string) $setting->value !== $value) {
                $setting->recordRevision(null, 'Before settings update');
                $setting->value = $value;
                $setting->save();
            }
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

    public function restore(SiteSetting $setting): RedirectResponse
    {
        $this->authorize('update', $setting);

        $revision = $setting->revisions()->first();

        if (! $revision) {
            return back()->withErrors(['revision' => 'No earlier value is stored for this setting.']);
        }

        $setting->forceFill([
            'value' => $revision->payload['value'] ?? '',
        ])->save();
        $revision->delete();
        $this->settings->flush();
        $this->logger->log('restored', $setting, 'Setting “'.$setting->key.'” restored to its previous value.');

        return back()->with('status', 'Previous setting value restored.');
    }
}
