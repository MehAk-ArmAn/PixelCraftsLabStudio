<?php

namespace Tests\Feature\Admin;

use App\Models\Page;
use App\Models\PageSection;
use App\Models\User;
use App\Services\SiteContentService;
use Database\Seeders\PageContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PageContentTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->superAdmin()->create();
        $this->seed(PageContentSeeder::class);
    }

    public function test_editing_a_section_changes_the_public_payload(): void
    {
        $page = Page::firstWhere('key', 'home');
        $section = $page->section('hero');

        $this->actingAs($this->admin)
            ->put(route('admin.pages.sections.update', [$page, $section]), [
                'eyebrow' => $section->eyebrow,
                'heading' => 'A brand new headline',
                'subheading' => $section->subheading,
                'body' => $section->body,
                'is_enabled' => '1',
                'settings' => $section->settings,
            ])->assertRedirect();

        SiteContentService::flush();
        $copy = app(SiteContentService::class)->payload()['copy'];

        $this->assertSame('A brand new headline', $copy['home']['hero']['heading']);
    }

    public function test_a_hidden_section_is_marked_disabled_publicly(): void
    {
        $page = Page::firstWhere('key', 'home');
        $section = $page->section('craft');

        $this->actingAs($this->admin)
            ->post(route('admin.pages.sections.toggle', [$page, $section]))
            ->assertRedirect();

        SiteContentService::flush();
        $copy = app(SiteContentService::class)->payload()['copy'];

        $this->assertArrayNotHasKey('craft', $copy['home']);
    }

    public function test_section_copy_revision_can_be_restored(): void
    {
        $page = Page::firstWhere('key', 'home');
        $section = $page->section('hero');
        $original = $section->heading;

        $this->actingAs($this->admin)
            ->put(route('admin.pages.sections.update', [$page, $section]), [
                'heading' => 'Temporary heading',
                'is_enabled' => '1',
                'settings' => $section->settings,
            ])->assertRedirect();

        $this->actingAs($this->admin)
            ->post(route('admin.pages.sections.restore', [$page, $section]))
            ->assertRedirect();

        $this->assertSame($original, $section->fresh()->heading);
    }

    public function test_page_seo_is_editable_and_revisions_restore(): void
    {
        $page = Page::firstWhere('key', 'growth');
        $original = $page->seo_title;

        $this->actingAs($this->admin)
            ->put(route('admin.pages.update', $page), [
                'title' => $page->title,
                'seo_title' => 'Changed SEO title',
                'is_published' => '1',
                'robots_index' => '1',
            ])->assertRedirect();

        $this->assertSame('Changed SEO title', $page->fresh()->seo_title);

        $this->actingAs($this->admin)->post(route('admin.pages.restore', $page))->assertRedirect();
        $this->assertSame($original, $page->fresh()->seo_title);
    }

    public function test_seeding_twice_does_not_duplicate_content(): void
    {
        $before = PageSection::count();
        $this->seed(PageContentSeeder::class);

        $this->assertSame($before, PageSection::count());
    }
}
