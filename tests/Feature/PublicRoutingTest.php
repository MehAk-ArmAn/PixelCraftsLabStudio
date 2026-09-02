<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Services\SettingsRepository;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PublicRoutingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_each_public_url_injects_its_server_selected_route(): void
    {
        foreach ([
            '/' => 'home',
            '/work' => 'work',
            '/services' => 'services',
            '/marketing' => 'marketing',
            '/pricing' => 'pricing',
            '/studio' => 'studio',
            '/lab' => 'lab',
            '/contact' => 'contact',
        ] as $url => $route) {
            $this->get($url)
                ->assertOk()
                ->assertSee('"route":"'.$route.'"', false)
                ->assertSee('"canonicalUrl":"'.($url === '/' ? '\/' : str_replace('/', '\/', $url)).'"', false);
        }
    }

    public function test_published_project_routes_by_slug_and_injects_the_project_context(): void
    {
        $project = Project::factory()->create(['slug' => 'route-aware-project']);

        $this->get(route('projects.show', $project))
            ->assertOk()
            ->assertSee('"route":"project"', false)
            ->assertSee('"projectSlug":"route-aware-project"', false)
            ->assertSee('<link rel="canonical" href="'.route('projects.show', $project).'"', false);
    }

    public function test_unknown_and_unpublished_project_slugs_return_not_found(): void
    {
        $draft = Project::factory()->draft()->create(['slug' => 'private-draft']);

        $this->get('/work/not-a-project')->assertNotFound();
        $this->get(route('projects.show', $draft))->assertNotFound();
    }

    public function test_disabled_lab_route_returns_not_found(): void
    {
        app(SettingsRepository::class)->set('lab_page_enabled', false, 'features', 'bool');

        $this->get('/lab')->assertNotFound();
    }
}
