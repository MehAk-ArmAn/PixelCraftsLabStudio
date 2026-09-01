<?php

namespace Database\Seeders;

use App\Models\MarketingChannel;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $this->buildServices();
        $this->growthServices();
    }

    /** The six capabilities exactly as the locked design shipped them. */
    private function buildServices(): void
    {
        $rows = [
            ['Utility App Development', 'Engineer', '01 · Apps', 'Components snap into the frame',
                'We design and develop practical, lightweight applications focused on solving real-world problems.'],
            ['Offline Game Development', 'Engineer', '02 · Games', 'A static frame becomes playable',
                'We create engaging offline-friendly games that prioritize performance, accessibility, and user experience.'],
            ['Web & Platform Development', 'Engineer', '03 · Web', 'Grid, code and cursor finish the page',
                'We build modern websites and supporting platforms that complement your apps, games, or digital presence.'],
            ['UI/UX Design', 'Design', '04 · Design', 'Wireframe resolves into interface',
                'We design user interfaces that are clear, intuitive, and visually balanced.'],
            ['Performance Optimization', 'Polish', '05 · Engineering', 'Weight comes down, response comes up',
                'We improve the speed, responsiveness, and efficiency of your digital product.'],
            ['Maintenance & Support', 'Launch', '06 · Ongoing', 'Launch day is the middle, not the end',
                'We provide ongoing updates, improvements, and technical support to keep your product running smoothly.'],
        ];

        foreach ($rows as $index => [$title, $stage, $tag, $caption, $body]) {
            Service::firstOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'title' => $title,
                    'stage' => $stage,
                    'track' => Service::TRACK_BUILD,
                    'group' => 'Product',
                    'tag' => $tag,
                    'caption' => $caption,
                    'body' => $body,
                    'sort_order' => ($index + 1) * 10,
                    'is_published' => true,
                    'show_on_homepage' => true,
                ],
            );
        }
    }

    /**
     * Marketing is a first-class capability: one parent service on the "Grow"
     * stage plus the sub-services that make up the offering. Every one of them
     * is a normal CMS row an admin can rename, hide, reorder or delete.
     */
    private function growthServices(): void
    {
        $parent = Service::firstOrCreate(
            ['slug' => 'digital-marketing-growth'],
            [
                'title' => 'Digital Marketing & Growth',
                'stage' => 'Grow',
                'track' => Service::TRACK_GROWTH,
                'group' => 'Growth',
                'tag' => '07 · Growth',
                'caption' => 'Attention turns into measurable growth',
                'body' => 'Strategy, content, social media and campaigns designed to help digital products and businesses reach the right audience and turn attention into measurable growth.',
                'long_body' => 'We treat marketing the way we treat engineering: define the problem, choose the channels that fit it, build the work, then measure whether it moved. That means a clear strategy, content people actually want, campaigns with a stated goal, and reporting that shows what happened rather than what we hoped would happen.',
                'sort_order' => 70,
                'is_published' => true,
                'is_featured' => true,
                'show_on_homepage' => true,
            ],
        );

        $channelIds = MarketingChannel::pluck('id', 'slug');
        $order = 10;

        foreach ($this->growthCatalogue() as $group => $services) {
            foreach ($services as [$title, $body, $channels]) {
                $service = Service::firstOrCreate(
                    ['slug' => Str::slug($title)],
                    [
                        'title' => $title,
                        'stage' => 'Grow',
                        'track' => Service::TRACK_GROWTH,
                        'group' => $group,
                        'parent_id' => $parent->id,
                        'body' => $body,
                        'sort_order' => $order,
                        'is_published' => true,
                        'show_on_homepage' => false,
                    ],
                );

                if ($service->wasRecentlyCreated && $channels !== []) {
                    $service->channels()->sync(
                        collect($channels)->map(fn ($slug) => $channelIds[$slug] ?? null)->filter()->all(),
                    );
                }

                $order += 10;
            }
        }
    }

    /** @return array<string, list<array{0: string, 1: string, 2: list<string>}>> */
    private function growthCatalogue(): array
    {
        return [
            'Strategy' => [
                ['Digital Marketing Strategy', 'A single plan covering audience, channels, message and measurement — so every piece of marketing has a reason to exist.', ['google-search']],
                ['Growth Strategy', 'Where growth realistically comes from for your product, and the sequence of work most likely to get you there.', []],
                ['Brand Growth Strategy', 'Positioning, message and creative direction that stay consistent as the audience gets bigger.', []],
                ['Marketing Audits', 'An honest review of what is running now: what is working, what is noise, and what to stop.', []],
                ['Competitor Research', 'How comparable brands are positioned, what they publish and where the gaps are.', []],
                ['Audience Research', 'Who the product is genuinely for, what they already use and what language they respond to.', []],
                ['Customer Journey Strategy', 'Mapping the route from first impression to conversion, then removing the friction along it.', []],
            ],
            'Social' => [
                ['Social Media Marketing', 'Organic social built on a stated strategy: content pillars, a calendar, and creative made for each platform.', ['instagram', 'tiktok', 'linkedin', 'facebook']],
                ['Social Media Management', 'Planning, scheduling, publishing and community engagement handled as ongoing work rather than ad-hoc posting.', ['instagram', 'tiktok', 'facebook']],
                ['Social Media Strategy', 'Channel choice, content pillars, posting rhythm and the metrics that tell you it is working.', ['instagram', 'linkedin']],
                ['Short-form Video Strategy', 'Reels, Shorts and TikTok concepts built around ideas that survive the first two seconds.', ['tiktok', 'youtube', 'instagram']],
                ['Community Growth', 'Engagement planning that treats replies, comments and DMs as part of the work, not an afterthought.', ['instagram', 'linkedin']],
                ['Audience Building', 'Consistent, useful publishing aimed at the people most likely to become customers.', []],
                ['Influencer & Creator Campaign Strategy', 'Choosing creators whose audience overlaps yours, and briefing them properly.', ['instagram', 'tiktok', 'youtube']],
                ['Social Media Audits', 'Profile optimisation, content review and a read on what your current performance actually says.', ['instagram', 'linkedin']],
            ],
            'Content' => [
                ['Content Strategy', 'What to publish, why, for whom and how often — written down so it can be executed.', ['blog']],
                ['Content Creation', 'Copy, creative direction and campaign assets produced against the strategy.', ['instagram', 'blog']],
                ['Content Pillars & Calendars', 'The recurring themes and the schedule that keep publishing consistent.', ['blog', 'instagram']],
                ['Landing Page Strategy', 'Message, structure and proof for the page a campaign actually points at.', ['website']],
            ],
            'Search' => [
                ['SEO', 'Technical review, on-page work and content direction aimed at search visibility. No ranking guarantees — nobody can honestly offer those.', ['organic-search', 'google-search']],
                ['Content SEO', 'Topic and keyword strategy that gives content a reason to rank, not just a place to live.', ['organic-search', 'blog']],
                ['Local SEO', 'Local listings, on-page signals and reviews for businesses that sell in a specific place.', ['google-search']],
                ['Search Visibility Reporting', 'What changed in impressions, clicks and positions, and what caused it.', ['organic-search']],
            ],
            'Paid' => [
                ['Paid Social Advertising', 'Campaign structure, audience strategy and creative testing across paid social.', ['facebook', 'instagram', 'tiktok']],
                ['Meta Ads Strategy', 'Objectives, audiences and creative direction for Facebook and Instagram campaigns.', ['facebook', 'instagram']],
                ['Google Ads / PPC Strategy', 'Search and performance campaign strategy, structure and measurement.', ['google-ads', 'google-search']],
                ['Campaign Creative', 'The assets a campaign runs on, built to be tested rather than admired.', []],
                ['Conversion Rate Optimization', 'Finding where people drop out of the journey and fixing that first.', ['website']],
            ],
            'Lifecycle' => [
                ['Email Marketing', 'Newsletter strategy and campaign copy that people have a reason to open.', ['email']],
                ['Marketing Automation', 'Welcome, nurture and launch sequences mapped to the customer journey.', ['email']],
                ['Retention Marketing', 'Keeping existing customers engaged after the first conversion.', ['email']],
                ['Lead Generation', 'Offer, capture and follow-up designed as one system rather than three disconnected parts.', ['website', 'email']],
            ],
            'Launch' => [
                ['Launch Marketing', 'The plan around a release date: message, assets, sequencing and channels.', []],
                ['Product Launch Campaigns', 'Campaign concept and execution for a specific launch moment.', []],
                ['App Marketing', 'Store presence, creative and campaign support for app and game releases.', ['google-search']],
                ['Website Growth Strategy', 'Turning an existing site into something that acquires and converts, not just describes.', ['website']],
            ],
            'Measurement' => [
                ['Analytics & Reporting', 'Tracking set up properly, then reported in plain language: reach, engagement, traffic, leads, conversion.', ['website']],
                ['Performance Reviews', 'A regular look at what the numbers mean and what changes next as a result.', []],
            ],
        ];
    }
}
