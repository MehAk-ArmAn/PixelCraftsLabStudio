<?php

namespace Tests\Feature\Admin;

use App\Models\Media;
use App\Models\Project;
use App\Models\User;
use App\Support\MediaResolver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class MediaTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->superAdmin()->create();
        Storage::fake('public');
    }

    public function test_an_image_can_be_uploaded(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.media.store'), [
                'files' => [UploadedFile::fake()->image('hero.png', 640, 480)],
                'folder' => 'projects',
            ])->assertRedirect();

        $media = Media::first();
        $this->assertNotNull($media);
        $this->assertStringStartsWith('projects/', $media->path);
        Storage::disk('public')->assertExists($media->path);
    }

    public function test_disallowed_file_types_are_rejected(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.media.store'), [
                'files' => [UploadedFile::fake()->create('payload.php', 12, 'application/x-php')],
            ])->assertSessionHasErrors('files.0');

        $this->assertSame(0, Media::count());
    }

    public function test_active_svg_files_cannot_be_uploaded(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.media.store'), [
                'files' => [UploadedFile::fake()->createWithContent('active.svg', '<svg><script>alert(1)</script></svg>')],
            ])->assertSessionHasErrors('files.0');

        $this->assertSame(0, Media::count());
    }

    public function test_oversized_files_are_rejected(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.media.store'), [
                'files' => [UploadedFile::fake()->create('huge.png', 30000, 'image/png')],
            ])->assertSessionHasErrors('files.0');
    }

    public function test_media_in_use_cannot_be_deleted(): void
    {
        $this->actingAs($this->admin)->post(route('admin.media.store'), [
            'files' => [UploadedFile::fake()->image('used.png')],
        ]);

        $media = Media::first();
        Project::factory()->create(['primary_image' => $media->reference()]);

        $this->actingAs($this->admin)
            ->delete(route('admin.media.destroy', $media))
            ->assertSessionHasErrors('media');

        $this->assertSame(1, Media::count());
    }

    public function test_the_picker_feed_returns_json(): void
    {
        $this->actingAs($this->admin)->post(route('admin.media.store'), [
            'files' => [UploadedFile::fake()->image('pick.png')],
        ]);

        $this->actingAs($this->admin)
            ->getJson(route('admin.media.browse'))
            ->assertOk()
            ->assertJsonStructure(['items' => [['id', 'reference', 'url', 'title']]]);
    }

    public function test_legacy_public_assets_still_resolve(): void
    {
        $this->assertSame('/assets/pcl-logo.png', MediaResolver::url('assets/pcl-logo.png'));
        $this->assertSame('', MediaResolver::url('imgs/portfolio/not-here.png'));
        $this->assertSame('', MediaResolver::url(null));
        $this->assertSame('https://cdn.example/x.png', MediaResolver::url('https://cdn.example/x.png'));
    }

    public function test_uploads_resolve_through_the_storage_disk(): void
    {
        $this->actingAs($this->admin)->post(route('admin.media.store'), [
            'files' => [UploadedFile::fake()->image('resolve.png')],
        ]);

        $media = Media::first();
        $this->assertStringContainsString('/storage/', $media->url());
    }

    public function test_existing_public_assets_can_be_registered(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.media.import-legacy'))
            ->assertRedirect();

        $this->assertGreaterThan(0, Media::where('is_legacy', true)->count());
    }
}
