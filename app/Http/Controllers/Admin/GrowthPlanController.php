<?php

namespace App\Http\Controllers\Admin;

use App\Models\GrowthPlan;
use App\Models\GrowthPlanItem;
use App\Models\MarketingChannel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class GrowthPlanController extends AdminResourceController
{
    protected function model(): string
    {
        return GrowthPlan::class;
    }

    protected function routeBase(): string
    {
        return 'growth-plans';
    }

    protected function title(): string
    {
        return 'Growth Plans';
    }

    protected function singular(): string
    {
        return 'Growth plan';
    }

    protected function intro(): ?string
    {
        return 'Packaged marketing offers. Leave every price field empty to display "Custom" — '
            .'nothing here invents a number for you.';
    }

    protected function searchable(): array
    {
        return ['name', 'short_description', 'ideal_for'];
    }

    protected function listColumns(): array
    {
        return [
            ['key' => 'name', 'label' => 'Plan'],
            ['key' => 'ideal_for', 'label' => 'Ideal for'],
            ['key' => 'is_published', 'label' => 'Published', 'type' => 'bool'],
            ['key' => 'is_featured', 'label' => 'Featured', 'type' => 'bool'],
        ];
    }

    protected function schema(): array
    {
        return [
            ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true, 'section' => 'Plan'],
            ['name' => 'slug', 'label' => 'Slug', 'type' => 'text', 'section' => 'Plan'],
            ['name' => 'short_description', 'label' => 'Short description', 'type' => 'textarea', 'rows' => 2, 'section' => 'Plan'],
            ['name' => 'full_description', 'label' => 'Full description', 'type' => 'textarea', 'rows' => 4, 'section' => 'Plan'],
            ['name' => 'ideal_for', 'label' => 'Ideal for', 'type' => 'text', 'section' => 'Plan'],
            ['name' => 'duration', 'label' => 'Duration', 'type' => 'text', 'section' => 'Plan'],
            ['name' => 'highlight_text', 'label' => 'Highlight', 'type' => 'text', 'section' => 'Plan'],
            ['name' => 'channel_ids', 'label' => 'Channels', 'type' => 'checkboxes', 'optionsFrom' => 'channels', 'section' => 'Plan'],

            ['name' => 'price_text', 'label' => 'Price text', 'type' => 'text', 'section' => 'Pricing', 'help' => 'Free text, e.g. "From £1,200 / month". Wins over the fields below.'],
            ['name' => 'currency', 'label' => 'Currency symbol', 'type' => 'text', 'section' => 'Pricing'],
            ['name' => 'starting_price', 'label' => 'Starting price', 'type' => 'text', 'section' => 'Pricing'],
            ['name' => 'billing_period', 'label' => 'Billing period', 'type' => 'text', 'section' => 'Pricing'],

            ['name' => 'cta_label', 'label' => 'CTA label', 'type' => 'text', 'section' => 'Publishing'],
            ['name' => 'cta_url', 'label' => 'CTA URL', 'type' => 'text', 'section' => 'Publishing'],
            ['name' => 'accent', 'label' => 'Accent colour', 'type' => 'color', 'section' => 'Publishing'],
            ['name' => 'is_published', 'label' => 'Published', 'type' => 'checkbox', 'section' => 'Publishing'],
            ['name' => 'is_featured', 'label' => 'Featured', 'type' => 'checkbox', 'section' => 'Publishing'],
            ['name' => 'sort_order', 'label' => 'Sort order', 'type' => 'number', 'section' => 'Publishing'],

            ['name' => 'seo_title', 'label' => 'SEO title', 'type' => 'text', 'section' => 'SEO'],
            ['name' => 'seo_description', 'label' => 'Meta description', 'type' => 'textarea', 'rows' => 2, 'section' => 'SEO'],
        ];
    }

    protected function rules(?Model $record = null): array
    {
        return [
            'name' => ['required', 'string', 'max:190'],
            'slug' => ['nullable', 'string', 'max:190', 'alpha_dash', Rule::unique('growth_plans', 'slug')->ignore($record?->getKey())],
            'short_description' => ['nullable', 'string', 'max:800'],
            'full_description' => ['nullable', 'string', 'max:5000'],
            'ideal_for' => ['nullable', 'string', 'max:255'],
            'duration' => ['nullable', 'string', 'max:120'],
            'highlight_text' => ['nullable', 'string', 'max:190'],
            'channel_ids' => ['nullable', 'array'],
            'channel_ids.*' => ['integer', 'exists:marketing_channels,id'],
            'price_text' => ['nullable', 'string', 'max:120'],
            'currency' => ['nullable', 'string', 'max:8'],
            'starting_price' => ['nullable', 'string', 'max:64'],
            'billing_period' => ['nullable', 'string', 'max:64'],
            'cta_label' => ['nullable', 'string', 'max:120'],
            'cta_url' => ['nullable', 'string', 'max:255'],
            'accent' => ['nullable', 'string', 'max:16'],
            'is_published' => ['boolean'],
            'is_featured' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:100000'],
            'seo_title' => ['nullable', 'string', 'max:190'],
            'seo_description' => ['nullable', 'string', 'max:400'],
        ];
    }

    protected function transform(array $data, Model $record): array
    {
        unset($data['channel_ids']);

        $data['slug'] = filled($data['slug'] ?? null)
            ? Str::slug($data['slug'])
            : ($record->slug ?: Str::slug($data['name']));

        return $data;
    }

    protected function afterSave(Model $record, Request $request, bool $created): void
    {
        $record->channels()->sync($request->input('channel_ids', []));
    }

    protected function formExtras(Model $record): array
    {
        return [
            'channels' => MarketingChannel::ordered()->pluck('name', 'id')->all(),
            'selectedChannels' => $record->exists ? $record->channels->pluck('id')->all() : [],
            'planItems' => $record->exists ? $record->items : collect(),
        ];
    }

    // ------------------------------------------------------------ plan items

    public function storeItem(Request $request, GrowthPlan $growthPlan): RedirectResponse
    {
        $this->authorize('update', $growthPlan);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:190'],
            'description' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:100000'],
        ]);

        $growthPlan->items()->create($data + ['is_enabled' => true]);

        $this->logger->log('created', $growthPlan, 'Deliverable added to "'.$growthPlan->name.'".');

        return back()->with('status', 'Deliverable added.');
    }

    public function updateItem(Request $request, GrowthPlan $growthPlan, GrowthPlanItem $item): RedirectResponse
    {
        $this->authorize('update', $growthPlan);
        abort_unless($item->growth_plan_id === $growthPlan->id, 404);

        $item->update($request->validate([
            'title' => ['required', 'string', 'max:190'],
            'description' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:100000'],
        ]) + ['is_enabled' => $request->boolean('is_enabled')]);

        return back()->with('status', 'Deliverable updated.');
    }

    public function destroyItem(GrowthPlan $growthPlan, GrowthPlanItem $item): RedirectResponse
    {
        $this->authorize('update', $growthPlan);
        abort_unless($item->growth_plan_id === $growthPlan->id, 404);

        $item->delete();

        $this->logger->log('deleted', $growthPlan, 'Deliverable removed from "'.$growthPlan->name.'".');

        return back()->with('status', 'Deliverable removed.');
    }
}
