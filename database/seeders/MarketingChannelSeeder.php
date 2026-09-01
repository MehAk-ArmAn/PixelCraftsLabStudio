<?php

namespace Database\Seeders;

use App\Models\MarketingChannel;
use Illuminate\Database\Seeder;

class MarketingChannelSeeder extends Seeder
{
    public function run(): void
    {
        $channels = [
            ['Instagram', 'instagram', '#FF5F1F', 'Short-form video, carousels and community engagement.'],
            ['TikTok', 'tiktok', '#0D0B12', 'Short-form video built for discovery rather than polish.'],
            ['YouTube', 'youtube', '#FF5F1F', 'Long-form and Shorts for search-driven and evergreen content.'],
            ['Facebook', 'facebook', '#5B2394', 'Community, local reach and paid social distribution.'],
            ['LinkedIn', 'linkedin', '#5B2394', 'B2B positioning, founder-led content and hiring reach.'],
            ['Google Search', 'google-search', '#8B45FF', 'Organic search visibility for the terms your buyers use.'],
            ['Google Ads', 'google-ads', '#FF5F1F', 'Paid search and performance campaigns.'],
            ['Email', 'email', '#0D0B12', 'Newsletters, nurture sequences and lifecycle campaigns.'],
            ['Website', 'website', '#5B2394', 'Landing pages, conversion paths and on-site messaging.'],
            ['Blog', 'blog', '#8B45FF', 'Owned content that answers real questions and earns search traffic.'],
            ['Organic Search', 'organic-search', '#8B45FF', 'Technical and content SEO across the whole site.'],
        ];

        foreach ($channels as $index => [$name, $slug, $accent, $description]) {
            MarketingChannel::firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'label' => $name,
                    'description' => $description,
                    'accent' => $accent,
                    'sort_order' => ($index + 1) * 10,
                    'is_enabled' => true,
                ],
            );
        }
    }
}
