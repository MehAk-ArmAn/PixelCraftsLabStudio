<?php

namespace App\Http\Controllers\Admin;

use App\Models\Package;
use App\Models\PackageItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PackageController extends AdminResourceController
{
    protected function model(): string
    {
        return Package::class;
    }

    protected function routeBase(): string
    {
        return 'packages';
    }

    protected function title(): string
    {
        return 'Pricing Packages';
    }

    protected function singular(): string
    {
        return 'Package';
    }

    protected function intro(): ?string
    {
        return 'Public prices and package contents live here. Agency fees stay separate from client media spend.';
    }

    protected function searchable(): array
    {
        return ['name', 'public_name', 'internal_code', 'category', 'short_description'];
    }

    protected function listColumns(): array
    {
        return [
            ['key' => 'name', 'label' => 'Package'],
            ['key' => 'category', 'label' => 'Category'],
            ['key' => 'price', 'label' => 'Price'],
            ['key' => 'is_recommended', 'label' => 'Recommended', 'type' => 'bool'],
            ['key' => 'is_published', 'label' => 'Published', 'type' => 'bool'],
        ];
    }

    protected function schema(): array
    {
        return [
            ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true, 'section' => 'Package'],
            ['name' => 'public_name', 'label' => 'Public display name', 'type' => 'text', 'section' => 'Package', 'help' => 'Optional client-facing name. Falls back to Name.'],
            ['name' => 'slug', 'label' => 'Slug', 'type' => 'text', 'section' => 'Package'],
            ['name' => 'category', 'label' => 'Category', 'type' => 'select', 'options' => array_combine(Package::CATEGORIES, Package::CATEGORIES), 'required' => true, 'section' => 'Package'],
            ['name' => 'short_description', 'label' => 'Short description', 'type' => 'textarea', 'rows' => 2, 'section' => 'Package'],
            ['name' => 'full_description', 'label' => 'Full description', 'type' => 'textarea', 'rows' => 4, 'section' => 'Package'],
            ['name' => 'billing_type', 'label' => 'Billing type', 'type' => 'select', 'options' => [
                'one_time' => 'One-time', 'monthly' => 'Monthly', 'project' => 'Project', 'custom' => 'Custom',
            ], 'required' => true, 'section' => 'Pricing'],
            ['name' => 'price', 'label' => 'Price', 'type' => 'number', 'section' => 'Pricing'],
            ['name' => 'currency', 'label' => 'Currency', 'type' => 'text', 'section' => 'Pricing'],
            ['name' => 'billing_period', 'label' => 'Billing period', 'type' => 'text', 'section' => 'Pricing', 'help' => 'For example: month, project, or one-time.'],
            ['name' => 'price_presentation', 'label' => 'Public price label', 'type' => 'select', 'options' => [
                'estimated' => 'Estimated', 'from' => 'From', 'estimated_from' => 'Estimated from', 'custom' => 'Custom',
            ], 'required' => true, 'section' => 'Pricing'],
            ['name' => 'original_price', 'label' => 'Original price', 'type' => 'number', 'section' => 'Pricing'],
            ['name' => 'promotional_price', 'label' => 'Promotional price', 'type' => 'number', 'section' => 'Pricing'],
            ['name' => 'promotion_label', 'label' => 'Promotion label', 'type' => 'text', 'section' => 'Pricing'],
            ['name' => 'minimum_term', 'label' => 'Minimum term', 'type' => 'text', 'section' => 'Pricing'],
            ['name' => 'media_spend_separated', 'label' => 'Media/ad spend is separate', 'type' => 'checkbox', 'section' => 'Pricing'],
            ['name' => 'terms', 'label' => 'Terms / exclusions', 'type' => 'textarea', 'rows' => 3, 'section' => 'Pricing'],
            ['name' => 'badge', 'label' => 'Badge', 'type' => 'text', 'section' => 'Publishing'],
            ['name' => 'cta_label', 'label' => 'CTA label', 'type' => 'text', 'section' => 'Publishing'],
            ['name' => 'cta_url', 'label' => 'CTA URL', 'type' => 'text', 'section' => 'Publishing'],
            ['name' => 'is_published', 'label' => 'Published', 'type' => 'checkbox', 'section' => 'Publishing'],
            ['name' => 'is_featured', 'label' => 'Featured', 'type' => 'checkbox', 'section' => 'Publishing'],
            ['name' => 'is_recommended', 'label' => 'Recommended', 'type' => 'checkbox', 'section' => 'Publishing'],
            ['name' => 'sort_order', 'label' => 'Sort order', 'type' => 'number', 'section' => 'Publishing'],
            ['name' => 'seo_title', 'label' => 'SEO title', 'type' => 'text', 'section' => 'SEO'],
            ['name' => 'seo_description', 'label' => 'Meta description', 'type' => 'textarea', 'rows' => 2, 'section' => 'SEO'],
            ['name' => 'internal_code', 'label' => 'Internal package code', 'type' => 'text', 'band' => 'internal_delivery', 'help' => 'Short reference for quotes and invoices.'],
            ['name' => 'platform_count', 'label' => 'Number of platforms', 'type' => 'number', 'band' => 'internal_delivery'],
            ['name' => 'post_count', 'label' => 'Posts per month', 'type' => 'number', 'band' => 'internal_delivery'],
            ['name' => 'video_count', 'label' => 'Reels / videos per month', 'type' => 'number', 'band' => 'internal_delivery'],
            ['name' => 'story_count', 'label' => 'Story sets per month', 'type' => 'number', 'band' => 'internal_delivery'],
            ['name' => 'community_level', 'label' => 'Community management level', 'type' => 'text', 'band' => 'internal_delivery'],
            ['name' => 'seo_inclusion', 'label' => 'SEO inclusion', 'type' => 'textarea', 'rows' => 2, 'band' => 'internal_delivery'],
            ['name' => 'ads_inclusion', 'label' => 'Ads management inclusion', 'type' => 'textarea', 'rows' => 2, 'band' => 'internal_delivery'],
            ['name' => 'campaign_limit', 'label' => 'Campaign limits', 'type' => 'text', 'band' => 'internal_delivery'],
            ['name' => 'media_spend_threshold', 'label' => 'Media-spend threshold', 'type' => 'text', 'band' => 'internal_delivery'],
            ['name' => 'requires_client_footage', 'label' => 'Client-supplied footage required', 'type' => 'checkbox', 'band' => 'internal_delivery'],
            ['name' => 'requires_production', 'label' => 'Production / shooting required', 'type' => 'checkbox', 'band' => 'internal_delivery'],
            ['name' => 'reporting_level', 'label' => 'Reporting level', 'type' => 'text', 'band' => 'internal_delivery'],
            ['name' => 'strategy_calls', 'label' => 'Strategy calls', 'type' => 'text', 'band' => 'internal_delivery'],
            ['name' => 'onboarding_requirement', 'label' => 'Setup / onboarding requirement', 'type' => 'textarea', 'rows' => 2, 'band' => 'internal_delivery'],
            ['name' => 'addons', 'label' => 'Add-ons', 'type' => 'textarea', 'rows' => 2, 'band' => 'internal_delivery'],
            ['name' => 'third_party_costs', 'label' => 'Third-party costs', 'type' => 'textarea', 'rows' => 2, 'band' => 'internal_delivery'],
            ['name' => 'recommended_scope', 'label' => 'Internal recommended scope', 'type' => 'textarea', 'rows' => 3, 'band' => 'internal_delivery'],
            ['name' => 'workload_notes', 'label' => 'Delivery workload notes', 'type' => 'textarea', 'rows' => 3, 'band' => 'internal_delivery'],
            ['name' => 'minimum_fee', 'label' => 'Minimum acceptable fee', 'type' => 'number', 'band' => 'internal_pricing'],
            ['name' => 'cost_notes', 'label' => 'Internal cost considerations', 'type' => 'textarea', 'rows' => 3, 'band' => 'internal_pricing'],
            ['name' => 'pricing_guidance', 'label' => 'Internal pricing guidance', 'type' => 'textarea', 'rows' => 3, 'band' => 'internal_pricing'],
            ['name' => 'discount_eligibility', 'label' => 'Discount eligibility', 'type' => 'textarea', 'rows' => 2, 'band' => 'internal_pricing'],
            ['name' => 'promotion_eligible', 'label' => 'Promotion eligible', 'type' => 'checkbox', 'band' => 'internal_pricing'],
            ['name' => 'founding_eligible', 'label' => 'Founding Client eligible', 'type' => 'checkbox', 'band' => 'internal_pricing'],
            ['name' => 'custom_quote_notes', 'label' => 'Custom quote notes', 'type' => 'textarea', 'rows' => 3, 'band' => 'internal_pricing'],
            ['name' => 'scope_risk_notes', 'label' => 'Scope-risk notes', 'type' => 'textarea', 'rows' => 3, 'band' => 'internal_pricing'],
            ['name' => 'sales_notes', 'label' => 'Admin-only sales notes', 'type' => 'textarea', 'rows' => 3, 'band' => 'internal_sales'],
            ['name' => 'negotiation_notes', 'label' => 'Negotiation notes', 'type' => 'textarea', 'rows' => 3, 'band' => 'internal_sales'],
            ['name' => 'internal_notes', 'label' => 'Internal notes', 'type' => 'textarea', 'rows' => 3, 'band' => 'internal_sales'],
            ['name' => 'scope_warnings', 'label' => 'Internal scope warnings', 'type' => 'textarea', 'rows' => 3, 'band' => 'internal_sales'],
        ];
    }

    protected function rules(?Model $record = null): array
    {
        return [
            'name' => ['required', 'string', 'max:190'],
            'public_name' => ['nullable', 'string', 'max:190'],
            'slug' => ['nullable', 'string', 'max:190', 'alpha_dash', Rule::unique('packages', 'slug')->ignore($record?->getKey())],
            'internal_code' => ['nullable', 'string', 'max:64', Rule::unique('packages', 'internal_code')->ignore($record?->getKey())],
            'category' => ['required', Rule::in(Package::CATEGORIES)],
            'billing_type' => ['required', Rule::in(Package::BILLING_TYPES)],
            'price' => ['nullable', 'numeric', 'min:0', 'max:9999999999.99'],
            'currency' => ['required', 'string', 'max:8'],
            'billing_period' => ['nullable', 'string', 'max:64'],
            'price_presentation' => ['required', Rule::in(Package::PRICE_PRESENTATIONS)],
            'short_description' => ['nullable', 'string', 'max:1000'],
            'full_description' => ['nullable', 'string', 'max:8000'],
            'is_featured' => ['boolean'],
            'is_recommended' => ['boolean'],
            'badge' => ['nullable', 'string', 'max:120'],
            'cta_label' => ['nullable', 'string', 'max:120'],
            'cta_url' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:100000'],
            'is_published' => ['boolean'],
            'original_price' => ['nullable', 'numeric', 'min:0', 'max:9999999999.99'],
            'promotional_price' => ['nullable', 'numeric', 'min:0', 'max:9999999999.99'],
            'minimum_fee' => ['nullable', 'numeric', 'min:0', 'max:9999999999.99'],
            'promotion_label' => ['nullable', 'string', 'max:190'],
            'promotion_eligible' => ['boolean'],
            'founding_eligible' => ['boolean'],
            'terms' => ['nullable', 'string', 'max:5000'],
            'media_spend_separated' => ['boolean'],
            'minimum_term' => ['nullable', 'string', 'max:120'],
            'seo_title' => ['nullable', 'string', 'max:190'],
            'seo_description' => ['nullable', 'string', 'max:400'],
            'platform_count' => ['nullable', 'integer', 'min:0', 'max:1000'],
            'post_count' => ['nullable', 'integer', 'min:0', 'max:1000'],
            'video_count' => ['nullable', 'integer', 'min:0', 'max:1000'],
            'story_count' => ['nullable', 'integer', 'min:0', 'max:1000'],
            'community_level' => ['nullable', 'string', 'max:190'],
            'seo_inclusion' => ['nullable', 'string', 'max:3000'],
            'ads_inclusion' => ['nullable', 'string', 'max:3000'],
            'campaign_limit' => ['nullable', 'string', 'max:190'],
            'media_spend_threshold' => ['nullable', 'string', 'max:190'],
            'requires_client_footage' => ['boolean'],
            'requires_production' => ['boolean'],
            'reporting_level' => ['nullable', 'string', 'max:190'],
            'strategy_calls' => ['nullable', 'string', 'max:190'],
            'onboarding_requirement' => ['nullable', 'string', 'max:3000'],
            'addons' => ['nullable', 'string', 'max:3000'],
            'third_party_costs' => ['nullable', 'string', 'max:3000'],
            'recommended_scope' => ['nullable', 'string', 'max:5000'],
            'workload_notes' => ['nullable', 'string', 'max:5000'],
            'cost_notes' => ['nullable', 'string', 'max:5000'],
            'pricing_guidance' => ['nullable', 'string', 'max:5000'],
            'discount_eligibility' => ['nullable', 'string', 'max:3000'],
            'custom_quote_notes' => ['nullable', 'string', 'max:5000'],
            'scope_risk_notes' => ['nullable', 'string', 'max:5000'],
            'sales_notes' => ['nullable', 'string', 'max:5000'],
            'negotiation_notes' => ['nullable', 'string', 'max:5000'],
            'internal_notes' => ['nullable', 'string', 'max:5000'],
            'scope_warnings' => ['nullable', 'string', 'max:5000'],
        ];
    }

    protected function transform(array $data, Model $record): array
    {
        $data['slug'] = filled($data['slug'] ?? null)
            ? Str::slug($data['slug'])
            : ($record->slug ?: Str::slug($data['name']));

        $data['is_starting_from'] = in_array($data['price_presentation'], ['from', 'estimated_from'], true);
        $data['package_scope'] = $this->structuredValues($data, $record, 'package_scope', $this->packageScopeFields());
        $data['internal_details'] = $this->structuredValues($data, $record, 'internal_details', $this->internalDetailFields());

        return $data;
    }

    protected function formExtras(Model $record): array
    {
        return [
            'packageItems' => $record->exists ? $record->items : collect(),
            'fieldValues' => array_merge($record->package_scope ?? [], $record->internal_details ?? []),
        ];
    }

    public function storeItem(Request $request, Package $package): RedirectResponse
    {
        $this->authorize('update', $package);

        $package->items()->create($this->validatedItem($request) + [
            'is_included' => true,
            'is_highlighted' => false,
        ]);

        $this->logger->log('created', $package, 'Item added to package “'.$package->name.'”.');

        return back()->with('status', 'Package item added.');
    }

    public function updateItem(Request $request, Package $package, PackageItem $item): RedirectResponse
    {
        $this->authorize('update', $package);
        abort_unless($item->package_id === $package->id, 404);

        $item->update($this->validatedItem($request) + [
            'is_included' => $request->boolean('is_included'),
            'is_highlighted' => $request->boolean('is_highlighted'),
        ]);

        return back()->with('status', 'Package item updated.');
    }

    public function destroyItem(Package $package, PackageItem $item): RedirectResponse
    {
        $this->authorize('update', $package);
        abort_unless($item->package_id === $package->id, 404);

        $item->delete();
        $this->logger->log('deleted', $package, 'Item removed from package “'.$package->name.'”.');

        return back()->with('status', 'Package item removed.');
    }

    /** @return array<string, mixed> */
    private function validatedItem(Request $request): array
    {
        return $request->validate([
            'text' => ['required', 'string', 'max:500'],
            'group' => ['nullable', 'string', 'max:120'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:100000'],
        ]);
    }

    /** @param list<string> $fields */
    private function structuredValues(array &$data, Model $record, string $attribute, array $fields): array
    {
        $values = is_array($record->{$attribute}) ? $record->{$attribute} : [];

        foreach ($fields as $field) {
            if (array_key_exists($field, $data)) {
                $values[$field] = $data[$field];
                unset($data[$field]);
            }
        }

        return array_filter($values, fn (mixed $value): bool => $value !== null && $value !== '');
    }

    /** @return list<string> */
    private function packageScopeFields(): array
    {
        return [
            'platform_count', 'post_count', 'video_count', 'story_count', 'community_level',
            'seo_inclusion', 'ads_inclusion', 'campaign_limit', 'media_spend_threshold',
            'requires_client_footage', 'requires_production', 'reporting_level', 'strategy_calls',
            'onboarding_requirement', 'addons', 'third_party_costs', 'recommended_scope', 'workload_notes',
        ];
    }

    /** @return list<string> */
    private function internalDetailFields(): array
    {
        return [
            'cost_notes', 'pricing_guidance', 'discount_eligibility', 'custom_quote_notes',
            'scope_risk_notes', 'sales_notes', 'negotiation_notes', 'internal_notes', 'scope_warnings',
        ];
    }
}
