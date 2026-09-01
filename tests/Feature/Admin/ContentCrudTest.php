<?php

namespace Tests\Feature\Admin;

use App\Models\GrowthPlan;
use App\Models\Project;
use App\Models\Service;
use App\Models\SocialLink;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ContentCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->superAdmin()->create();
    }

    public function test_project_crud(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.projects.store'), [
                'name' => 'Test Project',
                'category' => 'Web',
                'layout_size' => 'std',
                'status' => Project::STATUS_PUBLISHED,
                'is_published' => '1',
                'short_description' => 'Short',
            ])->assertRedirect();

        $project = Project::firstWhere('name', 'Test Project');
        $this->assertNotNull($project);
        $this->assertSame('test-project', $project->slug);
        $this->assertSame('TP', $project->initials);

        $this->actingAs($this->admin)
            ->put(route('admin.projects.update', $project), [
                'name' => 'Renamed Project',
                'category' => 'Games',
                'layout_size' => 'wide',
                'status' => Project::STATUS_PUBLISHED,
                'is_published' => '1',
            ])->assertRedirect();

        $this->assertSame('Renamed Project', $project->fresh()->name);
        $this->assertSame('Games', $project->fresh()->category);

        $this->actingAs($this->admin)
            ->post(route('admin.projects.duplicate', $project))
            ->assertRedirect();
        $this->assertSame(2, Project::count());

        $this->actingAs($this->admin)
            ->delete(route('admin.projects.destroy', $project))
            ->assertRedirect(route('admin.projects.index'));
        $this->assertNull($project->fresh());
    }

    public function test_project_validation_rejects_bad_input(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.projects.store'), ['name' => '', 'external_url' => 'not-a-url'])
            ->assertSessionHasErrors(['name', 'category', 'external_url', 'status']);
    }

    public function test_service_crud(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.services.store'), ['title' => 'New Capability', 'is_published' => '1'])
            ->assertRedirect();

        $service = Service::firstWhere('title', 'New Capability');
        $this->assertSame(Service::TRACK_BUILD, $service->track);

        $this->actingAs($this->admin)
            ->put(route('admin.services.update', $service), ['title' => 'Edited Capability'])
            ->assertRedirect();
        $this->assertSame('Edited Capability', $service->fresh()->title);

        $this->actingAs($this->admin)->delete(route('admin.services.destroy', $service))->assertRedirect();
        $this->assertSame(0, Service::count());
    }

    public function test_marketing_service_is_created_on_the_growth_track(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.marketing-services.store'), ['title' => 'Social Ads', 'is_published' => '1'])
            ->assertRedirect();

        $this->assertSame(Service::TRACK_GROWTH, Service::firstWhere('title', 'Social Ads')->track);
    }

    public function test_growth_plan_crud_with_deliverables(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.growth-plans.store'), ['name' => 'Starter', 'is_published' => '1'])
            ->assertRedirect();

        $plan = GrowthPlan::firstWhere('name', 'Starter');
        $this->assertSame('Custom', $plan->priceDisplay());

        $this->actingAs($this->admin)
            ->post(route('admin.growth-plans.items.store', $plan), ['title' => 'Marketing audit'])
            ->assertRedirect();
        $this->assertSame(1, $plan->items()->count());

        $item = $plan->items()->first();
        $this->actingAs($this->admin)
            ->delete(route('admin.growth-plans.items.destroy', [$plan, $item]))
            ->assertRedirect();
        $this->assertSame(0, $plan->items()->count());
    }

    public function test_team_crud(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.team.store'), ['name' => 'Ada Lovelace', 'is_published' => '1'])
            ->assertRedirect();

        $member = TeamMember::firstWhere('name', 'Ada Lovelace');
        $this->assertSame('AL', $member->initials);

        $this->actingAs($this->admin)->delete(route('admin.team.destroy', $member))->assertRedirect();
        $this->assertSame(0, TeamMember::count());
    }

    public function test_social_link_crud(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.socials.store'), [
                'platform' => 'Threads',
                'url' => 'https://threads.net/pcl',
                'is_enabled' => '1',
            ])->assertRedirect();

        $this->assertSame(1, SocialLink::count());

        $this->actingAs($this->admin)
            ->post(route('admin.socials.store'), ['platform' => 'Broken', 'url' => 'nope'])
            ->assertSessionHasErrors('url');
    }

    public function test_testimonial_crud(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.testimonials.store'), [
                'client_name' => 'A Client',
                'quote' => 'They shipped it.',
                'is_published' => '1',
            ])->assertRedirect();

        $this->assertSame(1, Testimonial::published()->count());
    }

    public function test_reordering_persists(): void
    {
        $a = Project::factory()->create(['sort_order' => 10]);
        $b = Project::factory()->create(['sort_order' => 20]);

        $this->actingAs($this->admin)
            ->post(route('admin.projects.reorder'), ['order' => [$a->id => 99, $b->id => 5]])
            ->assertRedirect();

        $this->assertSame(99, $a->fresh()->sort_order);
        $this->assertSame(5, $b->fresh()->sort_order);
    }

    public function test_settings_update(): void
    {
        $this->seed(\Database\Seeders\SiteSettingsSeeder::class);

        $this->actingAs($this->admin)
            ->put(route('admin.settings.update', 'contact'), [
                'values' => ['studio_phone' => '+44 1234 567890', 'studio_email' => 'hi@pcl.test'],
            ])->assertRedirect();

        $settings = app(\App\Services\SettingsRepository::class);
        $settings->flush();

        $this->assertSame('+44 1234 567890', $settings->string('studio_phone'));
        $this->assertSame('hi@pcl.test', $settings->string('studio_email'));
    }

    public function test_activity_is_logged(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.services.store'), ['title' => 'Logged Service']);

        $this->assertDatabaseHas('admin_activity_logs', [
            'action' => 'created',
            'user_name' => $this->admin->name,
        ]);
    }
}
