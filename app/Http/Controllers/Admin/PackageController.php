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
        return ['name', 'category', 'short_description'];
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
            ['name' => 'is_starting_from', 'label' => 'Show “From”', 'type' => 'checkbox', 'section' => 'Pricing'],
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
        ];
    }

    protected function rules(?Model $record = null): array
    {
        return [
            'name' => ['required', 'string', 'max:190'],
            'slug' => ['nullable', 'string', 'max:190', 'alpha_dash', Rule::unique('packages', 'slug')->ignore($record?->getKey())],
            'category' => ['required', Rule::in(Package::CATEGORIES)],
            'billing_type' => ['required', Rule::in(Package::BILLING_TYPES)],
            'price' => ['nullable', 'numeric', 'min:0', 'max:9999999999.99'],
            'currency' => ['required', 'string', 'max:8'],
            'billing_period' => ['nullable', 'string', 'max:64'],
            'is_starting_from' => ['boolean'],
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
            'promotion_label' => ['nullable', 'string', 'max:190'],
            'terms' => ['nullable', 'string', 'max:5000'],
            'media_spend_separated' => ['boolean'],
            'minimum_term' => ['nullable', 'string', 'max:120'],
            'seo_title' => ['nullable', 'string', 'max:190'],
            'seo_description' => ['nullable', 'string', 'max:400'],
        ];
    }

    protected function transform(array $data, Model $record): array
    {
        $data['slug'] = filled($data['slug'] ?? null)
            ? Str::slug($data['slug'])
            : ($record->slug ?: Str::slug($data['name']));

        return $data;
    }

    protected function formExtras(Model $record): array
    {
        return ['packageItems' => $record->exists ? $record->items : collect()];
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
}
