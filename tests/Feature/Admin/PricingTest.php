<?php

namespace Tests\Feature\Admin;

use App\Models\Package;
use App\Models\User;
use App\Services\SettingsRepository;
use App\Services\SiteContentService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PricingTest extends TestCase
{
    use RefreshDatabase;

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
        $this->assertSame('From', $growth->firstWhere('id', 'growth-partner')['startingFrom']);
    }

    public function test_admin_can_create_update_and_add_items_to_a_package(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.packages.store'), [
                'name' => 'Custom Retention',
                'category' => 'Email/Automation',
                'billing_type' => 'monthly',
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
                'price' => 2750,
                'currency' => 'AED',
                'billing_period' => 'month',
                'is_published' => '1',
            ])->assertRedirect();

        $this->assertSame('2750.00', $package->fresh()->price);
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
}
