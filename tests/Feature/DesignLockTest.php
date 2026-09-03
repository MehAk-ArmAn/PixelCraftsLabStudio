<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Guards the parts of the Claude Design document that must never drift: the
 * runtime hooks, the animation keyframes, and the fallback data that keeps the
 * site rendering if the database is unavailable.
 */
final class DesignLockTest extends TestCase
{
    private function source(): string
    {
        return file_get_contents(resource_path('pixelcraftslab/PixelCraftsLab Site.dc.html'));
    }

    public function test_the_claude_runtime_hooks_are_intact(): void
    {
        $html = $this->source();

        foreach (['<x-dc>', '</x-dc>', '<helmet>', 'data-dc-script', 'class Component extends DCLogic', 'src="/support.js"'] as $hook) {
            $this->assertStringContainsString($hook, $html, "Missing runtime hook: {$hook}");
        }

        $this->assertGreaterThan(40, substr_count($html, '<sc-for'));
        $this->assertGreaterThan(60, substr_count($html, '<sc-if'));
    }

    public function test_the_animation_and_interaction_system_is_untouched(): void
    {
        $html = $this->source();

        foreach ([
            '@keyframes pcl-stem', '@keyframes pcl-bowl', '@keyframes pcl-marquee',
            '@keyframes pcl-penride', '@keyframes pcl-snap', 'prefers-reduced-motion',
            'data-cursor', 'data-magnet', 'data-morph-src', 'data-morph-dest',
            'data-scrub', 'data-mo=', 'data-tool',
        ] as $token) {
            $this->assertStringContainsString($token, $html, "Design token lost: {$token}");
        }
    }

    public function test_fallback_data_survives_a_database_outage(): void
    {
        $html = $this->source();

        foreach (['ORIGINAL_PROJECTS', 'ORIGINAL_SERVICES', 'ORIGINAL_TEAM', 'ORIGINAL_SOCIALS', 'ORIGINAL_ROUTES', 'FALLBACK_COPY'] as $constant) {
            $this->assertStringContainsString($constant, $html, "Missing fallback: {$constant}");
        }

        $this->assertStringContainsString('window.PCL_CMS', $html);
    }

    public function test_the_document_is_never_compiled_through_blade(): void
    {
        // The controller returns the file raw; Blade would eat the {{ ... }} runtime.
        $this->assertStringNotContainsString(
            '@php',
            $this->source(),
            'The design document must not contain Blade directives.',
        );

        $this->assertFileDoesNotExist(resource_path('views/pixelcraftslab'));
    }
}
