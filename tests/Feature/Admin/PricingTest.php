<?php

namespace Tests\Feature\Admin;

use App\Models\Package;
use App\Models\User;
use App\Services\SettingsRepository;
use App\Services\SiteContentService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

final class PricingTest extends TestCase
{
    use LazilyRefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->superAdmin()->create();
        $this->seed(DatabaseSeeder::class);
        SiteContentService::flush();
    }

    public function test_exact_editable_aed_catalog_is_seeded_and_published(): void
    {
        $this->assertSame(31, Package::count());

        $payload = app(SiteContentService::class)->payload();
        $growth = collect($payload['growthPlans']);

        $this->assertCount(31, $payload['packages']);
        $this->assertCount(4, $growth);
        $this->assertSame('Growth Bundles', $payload['packages'][0]['category']);
        $this->assertSame('AED 2,990', $growth->firstWhere('id', 'foundation')['price']);
        $this->assertSame('AED 4,990', $growth->firstWhere('id', 'growth')['price']);
        $this->assertTrue($growth->firstWhere('id', 'growth')['recommended']);
        $this->assertSame('Advertising/media spend is separate.', $growth->firstWhere('id', 'growth')['mediaNote']);
        $this->assertSame('Estimated', $growth->firstWhere('id', 'foundation')['priceLabel']);
        $this->assertSame('Estimated from', $growth->firstWhere('id', 'growth-partner')['priceLabel']);
    }

    public function test_admin_can_create_update_and_add_items_to_a_package(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.packages.store'), [
                'name' => 'Custom Retention',
                'category' => 'Email/Automation',
                'billing_type' => 'monthly',
                'price_presentation' => 'estimated',
                'price' => 2500,
                'currency' => 'AED',
                'billing_period' => 'month',
                'is_published' => '1',
            ])->assertRedirect();

        $package = Package::firstWhere('slug', 'custom-retention');
        $this->assertNotNull($package);

        $this->actingAs($this->admin)
            ->post(route('admin.packages.items.store', $package), ['text' => 'Lifecycle reporting'])
            ->assertRedirect();

        $this->assertDatabaseHas('package_items', [
            'package_id' => $package->id,
            'text' => 'Lifecycle reporting',
        ]);

        $this->actingAs($this->admin)
            ->put(route('admin.packages.update', $package), [
                'name' => 'Custom Retention',
                'slug' => 'custom-retention',
                'category' => 'Email/Automation',
                'billing_type' => 'monthly',
                'price_presentation' => 'from',
                'price' => 2750,
                'currency' => 'AED',
                'billing_period' => 'month',
                'is_published' => '1',
            ])->assertRedirect();

        $this->assertSame('2750.00', $package->fresh()->price);
        $this->assertSame('from', $package->fresh()->price_presentation);
    }

    public function test_founding_offer_is_disabled_by_default_and_calculated_from_stored_prices(): void
    {
        $settings = app(SettingsRepository::class);
        $this->assertFalse(app(SiteContentService::class)->payload()['pricingPromotion']['active']);

        $settings->set('founding_client_enabled', true, 'pricing', 'bool');
        $settings->set('founding_client_discount_percent', 20, 'pricing', 'int');
        SiteContentService::flush();

        $payload = app(SiteContentService::class)->payload();
        $growth = collect($payload['growthPlans'])->firstWhere('id', 'growth');

        $this->assertTrue($payload['pricingPromotion']['active']);
        $this->assertSame('AED 3,992', $growth['price']);
        $this->assertSame('AED 4,990', $growth['originalPrice']);
        $this->assertNull($payload['pricingPromotion']['remaining']);
    }

    public function test_unpublished_package_is_excluded_from_the_public_payload(): void
    {
        Package::firstWhere('slug', 'social-start')->update(['is_published' => false]);

        $ids = collect(app(SiteContentService::class)->payload()['packages'])->pluck('id');

        $this->assertNotContains('social-start', $ids);
    }

    public function test_public_price_labels_are_controlled_by_each_package_record(): void
    {
        Package::firstWhere('slug', 'foundation')->update(['price_presentation' => 'estimated']);
        Package::firstWhere('slug', 'growth')->update(['price_presentation' => 'from']);
        Package::firstWhere('slug', 'scale')->update(['price_presentation' => 'estimated_from']);
        Package::firstWhere('slug', 'growth-partner')->update(['price_presentation' => 'custom']);

        $packages = collect(app(SiteContentService::class)->payload()['packages'])->keyBy('id');

        $this->assertSame('Estimated', $packages['foundation']['priceLabel']);
        $this->assertSame('From', $packages['growth']['priceLabel']);
        $this->assertSame('Estimated from', $packages['scale']['priceLabel']);
        $this->assertSame('Custom', $packages['growth-partner']['priceLabel']);
        $this->assertSame('Custom', $packages['growth-partner']['price']);
    }

    public function test_public_package_payload_is_allow_listed_and_excludes_internal_package_file_data(): void
    {
        $package = Package::firstWhere('slug', 'foundation');
        $package->update([
            'public_name' => 'Foundation Public',
            'internal_code' => 'FOUNDATION-PRIVATE',
            'minimum_fee' => 1777,
            'package_scope' => [
                'platform_count' => 2,
                'recommended_scope' => 'PRIVATE RECOMMENDED SCOPE',
                'workload_notes' => 'PRIVATE WORKLOAD NOTES',
            ],
            'internal_details' => [
                'internal_notes' => 'PRIVATE INTERNAL NOTES',
                'pricing_guidance' => 'PRIVATE PRICING GUIDANCE',
                'sales_notes' => 'PRIVATE SALES NOTES',
            ],
        ]);

        $publicPackage = collect(app(SiteContentService::class)->payload()['packages'])->firstWhere('id', 'foundation');
        $publicJson = json_encode($publicPackage, JSON_THROW_ON_ERROR);

        $this->assertSame([
            'id', 'name', 'category', 'billingType', 'short', 'body', 'price', 'rawPrice',
            'originalPrice', 'pricePresentation', 'priceLabel', 'startingFrom', 'openEnded',
            'period', 'featured', 'recommended', 'badge', 'ctaLabel', 'ctaUrl', 'terms',
            'minimumTerm', 'mediaNote', 'items',
        ], array_keys($publicPackage));
        $this->assertSame('Foundation Public', $publicPackage['name']);
        $this->assertSame('Growth Bundles', $publicPackage['category']);
        $this->assertStringNotContainsString('internal_notes', $publicJson);
        $this->assertStringNotContainsString('PRIVATE INTERNAL NOTES', $publicJson);
        $this->assertStringNotContainsString('minimum_fee', $publicJson);
        $this->assertStringNotContainsString('1777', $publicJson);
        $this->assertStringNotContainsString('pricing_guidance', $publicJson);
        $this->assertStringNotContainsString('PRIVATE PRICING GUIDANCE', $publicJson);
        $this->assertStringNotContainsString('sales_notes', $publicJson);
        $this->assertStringNotContainsString('PRIVATE SALES NOTES', $publicJson);
        $this->assertStringNotContainsString('PRIVATE RECOMMENDED SCOPE', $publicJson);

        $this->get('/pricing')
            ->assertOk()
            ->assertDontSee('PRIVATE INTERNAL NOTES', false)
            ->assertDontSee('PRIVATE PRICING GUIDANCE', false)
            ->assertDontSee('PRIVATE SALES NOTES', false)
            ->assertDontSee('PRIVATE RECOMMENDED SCOPE', false);

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertDontSee('PRIVATE INTERNAL NOTES', false)
            ->assertDontSee('PRIVATE PRICING GUIDANCE', false)
            ->assertDontSee('PRIVATE SALES NOTES', false);
    }

    public function test_unauthenticated_visitors_cannot_access_internal_package_administration(): void
    {
        $package = Package::firstWhere('slug', 'foundation');

        $this->get(route('admin.packages.edit', $package))
            ->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_save_and_review_internal_package_information(): void
    {
        $package = Package::firstWhere('slug', 'foundation');

        $this->actingAs($this->admin)
            ->put(route('admin.packages.update', $package), $this->packagePayload([
                'internal_code' => 'FOUND-001',
                'minimum_fee' => 1750,
                'platform_count' => 3,
                'recommended_scope' => 'Three channels with a focused monthly campaign.',
                'workload_notes' => 'Reserve two production days.',
                'cost_notes' => 'Allow for freelance editing.',
                'pricing_guidance' => 'Increase for multilingual delivery.',
                'custom_quote_notes' => 'Quote separately above three channels.',
                'scope_risk_notes' => 'Approval delays increase workload.',
                'sales_notes' => 'Lead with the measurement plan.',
                'internal_notes' => 'Review after the first quarter.',
            ]))
            ->assertRedirect(route('admin.packages.edit', $package));

        $saved = $package->fresh();

        $this->assertSame('FOUND-001', $saved->internal_code);
        $this->assertSame('1750.00', $saved->minimum_fee);
        $this->assertSame(3, $saved->package_scope['platform_count']);
        $this->assertSame('Three channels with a focused monthly campaign.', $saved->package_scope['recommended_scope']);
        $this->assertSame('Increase for multilingual delivery.', $saved->internal_details['pricing_guidance']);
        $this->assertSame('Lead with the measurement plan.', $saved->internal_details['sales_notes']);

        $this->actingAs($this->admin)
            ->get(route('admin.packages.edit', $package))
            ->assertOk()
            ->assertSee('FOUND-001')
            ->assertSee('Three channels with a focused monthly campaign.')
            ->assertSee('Increase for multilingual delivery.')
            ->assertSee('Lead with the measurement plan.');
    }

    public function test_package_updates_invalidate_the_public_cms_cache(): void
    {
        $package = Package::firstWhere('slug', 'foundation');
        $before = collect(app(SiteContentService::class)->payload()['packages'])->firstWhere('id', 'foundation');

        $this->actingAs($this->admin)
            ->put(route('admin.packages.update', $package), $this->packagePayload([
                'public_name' => 'Foundation Reframed',
            ]))
            ->assertRedirect(route('admin.packages.edit', $package));

        $after = collect(app(SiteContentService::class)->payload()['packages'])->firstWhere('id', 'foundation');

        $this->assertSame('Foundation', $before['name']);
        $this->assertSame('Foundation Reframed', $after['name']);
    }

    public function test_pricing_and_marketing_pages_still_render(): void
    {
        $this->get('/pricing')->assertOk();
        $this->get('/marketing')->assertOk();
    }

    /**
     * @param array<string, mixed> $overrides
     * @return array<string, mixed>
     */
    private function packagePayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Foundation',
            'slug' => 'foundation',
            'category' => 'Growth Bundles',
            'billing_type' => 'monthly',
            'price_presentation' => 'estimated',
            'price' => 2990,
            'currency' => 'AED',
            'billing_period' => 'month',
            'promotion_eligible' => '1',
            'founding_eligible' => '1',
            'is_published' => '1',
        ], $overrides);
    }
}
