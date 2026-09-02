<?php

namespace Tests\Feature;

use App\Models\Project;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class SitemapTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_contains_real_routes_and_only_published_projects(): void
    {
        $this->seed(DatabaseSeeder::class);
        $live = Project::factory()->create(['slug' => 'sitemap-live']);
        $draft = Project::factory()->draft()->create(['slug' => 'sitemap-draft']);

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee(route('pricing.index'), false)
            ->assertSee(route('projects.show', $live), false)
            ->assertDontSee(route('projects.show', $draft), false);
    }
}
