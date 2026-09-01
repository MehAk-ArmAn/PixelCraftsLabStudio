<?php

namespace Tests\Feature;

use Tests\TestCase;

final class PixelCraftsLabSiteTest extends TestCase
{
    public function test_homepage_serves_locked_design(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('PixelCraftsLab', false)
            ->assertSee('Bring your idea', false);
    }

    public function test_runtime_file_exists(): void
    {
        $this->assertFileExists(public_path('support.js'));
    }
}
