<?php

namespace Database\Seeders;

use App\Models\ProcessStage;
use Illuminate\Database\Seeder;

class ProcessStageSeeder extends Seeder
{
    public function run(): void
    {
        // Build track — the five stages exactly as the design shipped them,
        // plus "Grow" so marketing has a home in the same stage picker.
        $build = [
            ['Imagine', '01', '#5B2394', 'Every project starts from the problem, not the pixels. We settle scope, audience and what the product actually has to do — the part of the work that decides whether the rest is worth building.'],
            ['Design', '02', '#FF5F1F', 'We design user interfaces that are clear, intuitive, and visually balanced. Our approach focuses on usability first, then the visual language that carries the brand through it.'],
            ['Engineer', '03', '#5B2394', 'Utility apps, offline games, websites and supporting platforms — built as scalable, secure, high-performance systems that are production-ready rather than demo-ready.'],
            ['Polish', '04', '#FF5F1F', 'We improve the speed, responsiveness, and efficiency of your digital product: reducing load times, tightening interaction, and removing everything that makes it feel slower than it is.'],
            ['Launch', '05', '#5B2394', 'We provide ongoing updates, improvements, and technical support to keep your product running smoothly after release — because launch day is the middle of the project, not the end.'],
            ['Grow', '06', '#FF5F1F', 'Shipping is the start of the audience problem, not the end of it. Strategy, content, social and campaigns take the finished product to the people it was built for — and reporting shows whether it actually landed.'],
        ];

        foreach ($build as $index => [$name, $number, $accent, $body]) {
            ProcessStage::firstOrCreate(
                ['slug' => 'build-'.strtolower($name)],
                [
                    'name' => $name,
                    'number' => $number,
                    'track' => ProcessStage::TRACK_BUILD,
                    'body' => $body,
                    'accent' => $accent,
                    'sort_order' => ($index + 1) * 10,
                    'is_published' => true,
                ],
            );
        }

        // Growth track — the marketing process, in marketing language.
        $growth = [
            ['Discover', '01', '#5B2394', 'Audience, market and current performance. What is already working, who is actually buying, and what the numbers say before anyone writes a post.'],
            ['Strategize', '02', '#8B45FF', 'Positioning, channels, content pillars and the measurement plan. One document that says what we are doing and how we will know if it worked.'],
            ['Create', '03', '#FF5F1F', 'Content, campaign creative and copy built against the strategy — made for the channel it runs on rather than resized to fit it.'],
            ['Launch', '04', '#0D0B12', 'Campaigns go live with a stated goal, a defined audience and tracking already in place.'],
            ['Measure', '05', '#5B2394', 'Reach, engagement, traffic, leads and conversion reported in plain language, against the goals set at the start.'],
            ['Optimize', '06', '#8B45FF', 'Test what is uncertain, keep what performs, cut what does not. Changes are made because the data asked for them.'],
            ['Grow', '07', '#FF5F1F', 'Compounding the parts that work: more of the content that lands, more of the channels that convert, and a roadmap for the next quarter.'],
        ];

        foreach ($growth as $index => [$name, $number, $accent, $body]) {
            ProcessStage::firstOrCreate(
                ['slug' => 'growth-'.strtolower($name)],
                [
                    'name' => $name,
                    'number' => $number,
                    'track' => ProcessStage::TRACK_GROWTH,
                    'body' => $body,
                    'accent' => $accent,
                    'sort_order' => ($index + 1) * 10,
                    'is_published' => true,
                ],
            );
        }
    }
}
