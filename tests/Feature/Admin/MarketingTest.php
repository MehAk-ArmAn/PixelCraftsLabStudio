<?php

namespace Tests\Feature\Admin;

use App\Models\Project;
use App\Models\Service;
use App\Models\User;
use App\Services\SettingsRepository;
use App\Services\SiteContentService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class MarketingTest extends TestCase
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

    public function test_marketing_appears_as_a_service_with_sub_services(): void
    {
        $payload = app(SiteContentService::class)->payload();

        $this->assertCount(1, $payload['marketingServices']);
        $parent = $payload['marketingServices'][0];

        $this->assertSame('Digital Marketing & Growth', $parent['title']);
        $this->assertGreaterThan(30, count($parent['children']));
        $this->assertContains('Social Media Marketing', array_column($parent['children'], 'title'));
        $this->assertContains('SEO', array_column($parent['children'], 'title'));
    }

    public function test_growth_plans_render_from_the_admin_controlled_package_catalog(): void
    {
        $plans = app(SiteContentService::class)->payload()['growthPlans'];

        $this->assertCount(4, $plans);
        foreach ($plans as $plan) {
            $this->assertStringStartsWith('AED ', $plan['price']);
            $this->assertNotEmpty($plan['items']);
        }

        $this->assertTrue(collect($plans)->firstWhere('id', 'growth')['recommended']);
    }

    public function test_a_hidden_marketing_service_does_not_render(): void
    {
        $service = Service::firstWhere('slug', 'social-media-marketing');
        $service->update(['is_published' => false]);

        SiteContentService::flush();
        $children = app(SiteContentService::class)->payload()['marketingServices'][0]['children'];

        $this->assertNotContains('Social Media Marketing', array_column($children, 'title'));
    }

    public function test_reordered_marketing_services_persist(): void
    {
        $service = Service::firstWhere('slug', 'seo');

        $this->actingAs($this->admin)
            ->post(route('admin.marketing-services.reorder'), ['order' => [$service->id => 1]])
            ->assertRedirect();

        $this->assertSame(1, $service->fresh()->sort_order);
    }

    public function test_the_growth_process_track_is_separate_from_the_build_track(): void
    {
        $payload = app(SiteContentService::class)->payload();

        $build = array_column($payload['stages'], 'name');
        $growth = array_column($payload['growthStages'], 'name');

        $this->assertSame(['Imagine', 'Design', 'Engineer', 'Polish', 'Launch', 'Grow'], $build);
        $this->assertSame(['Discover', 'Strategize', 'Create', 'Launch', 'Measure', 'Optimize', 'Grow'], $growth);
    }

    public function test_a_marketing_case_study_publishes_with_metrics(): void
    {
        $project = Project::factory()->marketingCaseStudy()->create([
            'strategy' => 'Content pillars and paid social.',
            'results' => 'Reported monthly.',
        ]);

        $project->metrics()->create([
            'metric_label' => 'Engagement growth',
            'metric_value' => '+38%',
            'metric_context' => 'Instagram, Q1',
        ]);

        SiteContentService::flush();
        $payload = app(SiteContentService::class)->payload();
        $row = collect($payload['projects'])->firstWhere('id', $project->slug);

        $this->assertTrue($row['marketing']);
        $this->assertSame('Content pillars and paid social.', $row['strategy']);
        $this->assertCount(1, $row['metrics']);
        $this->assertSame('+38%', $row['metrics'][0]['value']);
    }

    public function test_marketing_categories_are_available_for_projects(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.projects.store'), [
                'name' => 'A Campaign',
                'category' => 'Campaign',
                'layout_size' => 'std',
                'status' => Project::STATUS_PUBLISHED,
                'is_published' => '1',
                'is_marketing_case_study' => '1',
            ])->assertRedirect();

        $this->assertTrue(Project::firstWhere('name', 'A Campaign')->is_marketing_case_study);
    }

    public function test_marketing_overview_summarises_real_data(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.marketing.overview'))
            ->assertOk()
            ->assertSee('Digital Marketing &amp; Growth', false)
            ->assertSee('Foundation');
    }

    public function test_channels_are_manageable_and_published(): void
    {
        $channels = app(SiteContentService::class)->payload()['channels'];

        $this->assertContains('Instagram', array_column($channels, 'name'));
        $this->assertContains('Google Ads', array_column($channels, 'name'));
        $this->assertContains('WhatsApp', array_column($channels, 'name'));
        $this->assertContains('Snapchat', array_column($channels, 'name'));
    }

    public function test_the_growth_page_can_be_disabled(): void
    {
        app(SettingsRepository::class)->set('growth_page_enabled', false, 'features', 'bool');
        SiteContentService::flush();

        $this->assertFalse(app(SiteContentService::class)->payload()['flags']['growthEnabled']);
    }
}
