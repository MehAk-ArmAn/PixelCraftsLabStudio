<?php

namespace Database\Seeders;

use App\Models\GrowthPlan;
use Illuminate\Database\Seeder;

/**
 * Starting packages only. No prices are invented — every plan displays
 * "Custom" until an admin fills in the pricing fields.
 */
class GrowthPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'slug' => 'foundation',
                'name' => 'Foundation',
                'ideal_for' => 'Businesses that need a clear starting point.',
                'short' => 'The groundwork: who you are talking to, what you should say, and where to say it.',
                'full' => 'Foundation is the first engagement. We audit what exists, define the audience properly, and leave you with a written strategy and a 30-day content direction you could execute without us. It is deliberately front-loaded — the decisions made here shape everything that follows.',
                'duration' => 'One-off, typically 3–4 weeks',
                'highlight' => 'Start here',
                'accent' => '#5B2394',
                'featured' => false,
                'items' => [
                    ['Brand & marketing audit', 'A review of current positioning, channels and output — including what to stop doing.'],
                    ['Audience definition', 'Who the product is genuinely for, and the language they respond to.'],
                    ['Social media strategy', 'Channel choice, posting rhythm and what each platform is for.'],
                    ['Content pillars', 'The recurring themes everything else is built from.'],
                    ['30-day content direction', 'A concrete first month rather than an abstract plan.'],
                    ['Growth roadmap', 'The sequence of work most likely to move the numbers.'],
                    ['Core analytics setup', 'Tracking configured so later reporting means something.'],
                ],
            ],
            [
                'slug' => 'growth',
                'name' => 'Growth',
                'ideal_for' => 'Brands ready for consistent marketing.',
                'short' => 'Ongoing strategy, content planning and campaigns with monthly reporting.',
                'full' => 'Growth is the month-to-month engagement. Strategy stays live rather than sitting in a document: content is planned, campaigns are concepted, search and conversion recommendations are made, and every month closes with a report on what actually happened.',
                'duration' => 'Monthly, 3-month minimum',
                'highlight' => 'Most common',
                'accent' => '#FF5F1F',
                'featured' => true,
                'items' => [
                    ['Everything in Foundation', 'The strategy work stays current rather than going stale.'],
                    ['Monthly social strategy', 'Reviewed against performance, not repeated on autopilot.'],
                    ['Content planning', 'A calendar with pillars, formats and hooks mapped out.'],
                    ['Campaign concepts', 'Ideas built around a stated goal and a defined audience.'],
                    ['SEO & content recommendations', 'Topics and on-page work that give content a reason to rank.'],
                    ['Analytics review', 'What the numbers moved, and what caused it.'],
                    ['Conversion recommendations', 'Where people are dropping out, and what to change first.'],
                    ['Monthly growth reporting', 'Reach, engagement, traffic and leads in plain language.'],
                ],
            ],
            [
                'slug' => 'scale',
                'name' => 'Scale',
                'ideal_for' => 'Businesses actively investing in growth.',
                'short' => 'Multi-channel strategy across organic, paid, search and email, with a testing roadmap.',
                'full' => 'Scale is for teams already spending on growth who need the channels to work together. Organic, paid, search and email run against one strategy, landing pages are optimised for the campaigns pointing at them, and a testing roadmap decides what gets tried next.',
                'duration' => 'Monthly, ongoing',
                'highlight' => '',
                'accent' => '#0D0B12',
                'featured' => false,
                'items' => [
                    ['Multi-channel growth strategy', 'One plan covering every channel rather than several competing ones.'],
                    ['Paid campaign strategy', 'Structure, audiences and creative direction for paid social and search.'],
                    ['Organic social strategy', 'Sustained publishing that supports the paid work rather than duplicating it.'],
                    ['SEO strategy', 'Technical, on-page and content SEO run as a programme.'],
                    ['Email & automation strategy', 'Lifecycle sequences mapped to the customer journey.'],
                    ['Landing-page optimization', 'The pages campaigns point at, treated as part of the campaign.'],
                    ['Testing roadmap', 'What gets tested next, and what a result would have to look like.'],
                    ['Detailed performance reporting', 'Channel-level reporting with context, not a dashboard screenshot.'],
                    ['Ongoing strategic recommendations', 'A standing point of contact for growth decisions.'],
                ],
            ],
        ];

        foreach ($plans as $index => $row) {
            $plan = GrowthPlan::firstOrCreate(
                ['slug' => $row['slug']],
                [
                    'name' => $row['name'],
                    'short_description' => $row['short'],
                    'full_description' => $row['full'],
                    'ideal_for' => $row['ideal_for'],
                    'duration' => $row['duration'],
                    'highlight_text' => $row['highlight'],
                    'accent' => $row['accent'],
                    'cta_label' => 'Start a project',
                    'cta_url' => '#contact',
                    'is_featured' => $row['featured'],
                    'is_published' => true,
                    'sort_order' => ($index + 1) * 10,
                ],
            );

            if (! $plan->wasRecentlyCreated) {
                continue;
            }

            foreach ($row['items'] as $i => [$title, $description]) {
                $plan->items()->create([
                    'title' => $title,
                    'description' => $description,
                    'sort_order' => ($i + 1) * 10,
                    'is_enabled' => true,
                ]);
            }
        }
    }
}
