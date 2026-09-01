<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use App\Services\SettingsRepository;
use App\Services\SiteContentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PublicSiteTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_serves_the_locked_design(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('<x-dc>', false)
            ->assertSee('support.js', false)
            ->assertSee('Bring your idea', false);
    }

    public function test_cms_payload_is_injected_before_the_runtime(): void
    {
        $this->seedSite();

        $body = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('window.PCL_CMS', $body);
        $this->assertStringContainsString('"ready":true', $body);
        $this->assertLessThan(
            strpos($body, 'support.js'),
            strpos($body, 'window.PCL_CMS'),
            'The payload must be defined before the Claude runtime boots.',
        );
    }

    public function test_payload_generates_with_the_seeded_content(): void
    {
        $this->seedSite();

        $payload = app(SiteContentService::class)->payload();

        $this->assertTrue($payload['ready']);
        $this->assertCount(13, $payload['projects']);
        $this->assertCount(4, $payload['team']);
        $this->assertCount(8, $payload['socials']);
        $this->assertCount(6, $payload['services']);
        $this->assertCount(3, $payload['growthPlans']);
        $this->assertCount(11, $payload['channels']);
        $this->assertNotEmpty($payload['copy']['home']['hero']['heading']);
        $this->assertSame('Bring your idea', $payload['copy']['home']['hero']['heading']);
    }

    public function test_unpublished_projects_are_hidden_publicly(): void
    {
        $live = Project::factory()->create(['name' => 'Public Project']);
        $draft = Project::factory()->draft()->create(['name' => 'Secret Project']);

        SiteContentService::flush();
        $ids = collect(app(SiteContentService::class)->payload()['projects'])->pluck('id');

        $this->assertContains($live->slug, $ids);
        $this->assertNotContains($draft->slug, $ids);
    }

    public function test_admin_preview_includes_drafts(): void
    {
        $draft = Project::factory()->draft()->create(['name' => 'Secret Project']);
        $admin = User::factory()->superAdmin()->create();

        $body = $this->actingAs($admin)->get(route('admin.preview'))->assertOk()->getContent();

        $this->assertStringContainsString('Secret Project', $body);
        $this->assertStringContainsString('"preview":true', $body);
    }

    public function test_content_cache_is_invalidated_on_save(): void
    {
        $this->seedSite();
        $before = count(app(SiteContentService::class)->payload()['projects']);

        Project::factory()->create();

        $this->assertSame($before + 1, count(app(SiteContentService::class)->payload()['projects']));
    }

    public function test_site_can_be_disabled_from_settings(): void
    {
        $this->seedSite();
        app(SettingsRepository::class)->set('site_enabled', false, 'features', 'bool');

        $this->get('/')->assertStatus(503);
    }

    public function test_runtime_assets_remain_reachable(): void
    {
        $this->assertFileExists(public_path('support.js'));
        $this->assertFileExists(public_path('assets/pcl-logo.png'));
        $this->assertFileExists(public_path('admin.css'));
    }

    public function test_the_original_design_backup_is_untouched(): void
    {
        $backup = resource_path('pixelcraftslab/original/PixelCraftsLab Site.dc.html');

        $this->assertFileExists($backup);
        $this->assertSame(
            '5df0e18f14adca0b0487c87200dea542389d786a3e697a4f5c97af688e4ce019',
            hash_file('sha256', $backup),
        );
    }

    private function seedSite(): void
    {
        $this->seed(\Database\Seeders\DatabaseSeeder::class);
        SiteContentService::flush();
    }
}
