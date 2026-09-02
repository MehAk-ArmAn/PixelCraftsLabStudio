<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->packages() as $index => $row) {
            [$category, $name, $price, $billingType, $period, $items, $options] = $row;

            $package = Package::firstOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'category' => $category,
                    'billing_type' => $billingType,
                    'price' => $price,
                    'currency' => 'AED',
                    'billing_period' => $period,
                    'is_starting_from' => $options['from'] ?? false,
                    'price_presentation' => ($options['from'] ?? false) ? 'estimated_from' : 'estimated',
                    'short_description' => $options['short'] ?? null,
                    'full_description' => $options['full'] ?? null,
                    'is_featured' => $options['featured'] ?? false,
                    'is_recommended' => $options['recommended'] ?? false,
                    'badge' => $options['badge'] ?? null,
                    'cta_label' => 'Start a project',
                    'cta_url' => '/contact',
                    'sort_order' => ($index + 1) * 10,
                    'is_published' => true,
                    'terms' => $options['terms'] ?? null,
                    'media_spend_separated' => $options['media'] ?? false,
                    'minimum_term' => $options['term'] ?? null,
                ],
            );

            if (! $package->wasRecentlyCreated) {
                continue;
            }

            foreach ($items as $itemIndex => $text) {
                $package->items()->create([
                    'text' => $text,
                    'sort_order' => ($itemIndex + 1) * 10,
                    'is_included' => true,
                ]);
            }
        }
    }

    /** @return list<array{0:string,1:string,2:int,3:string,4:string,5:list<string>,6:array<string, mixed>}> */
    private function packages(): array
    {
        return [
            ['Strategy', 'Marketing Audit', 490, 'one_time', 'one-time', ['Channel and positioning review', 'Priority findings', 'Action summary'], ['short' => 'A focused review of what is working, what is not, and what to fix first.']],
            ['Strategy', '90-Day Growth Blueprint', 1290, 'one_time', 'one-time', ['Business and audience review', 'Channel priorities', '90-day campaign roadmap', 'Measurement plan'], ['short' => 'A practical three-month direction for growth.']],
            ['Strategy', 'Social Media Audit', 390, 'one_time', 'one-time', ['Profile review', 'Content review', 'Competitor scan', 'Priority recommendations'], []],
            ['Strategy', 'Competitor + Audience Snapshot', 490, 'one_time', 'one-time', ['Competitor snapshot', 'Audience definition', 'Messaging opportunities', 'Channel observations'], []],
            ['Social Media', 'Social Profile Setup / Optimisation', 790, 'one_time', 'one-time', ['Profile setup or refresh', 'Bio and link optimisation', 'Brand consistency review', 'Core account settings'], []],
            ['Strategy', 'Analytics / Tracking Setup', 990, 'one_time', 'one-time', ['Analytics configuration', 'Core conversion events', 'Campaign tracking conventions', 'Verification and handover'], []],
            ['WhatsApp', 'WhatsApp Business Growth Setup', 790, 'one_time', 'one-time', ['Business profile setup', 'Catalogue and labels structure', 'Quick replies', 'Basic enquiry journey'], []],

            ['Social Media', 'Social Start', 1790, 'monthly', 'month', ['2 platforms', '8 feed pieces', '2 Reels / short videos', '4 Story sets', 'Profile optimisation', 'Content direction', 'Captions', 'Scheduling and publishing', 'Basic engagement monitoring', 'Monthly report'], ['short' => 'A consistent, well-managed social presence.', 'terms' => 'Client-supplied footage unless a production add-on applies.']],
            ['Social Media', 'Social Growth', 2990, 'monthly', 'month', ['2–3 platforms', 'Social strategy', '12 feed pieces', '4 Reels', '8 Story sets', 'Custom graphics and captions', 'Publishing', 'Community monitoring', 'Competitor monitoring', 'Monthly optimisation and report', 'Strategy call'], ['short' => 'Stronger creative, monitoring and monthly optimisation.', 'featured' => true, 'badge' => 'Popular']],
            ['Social Media', 'Social Pro', 4490, 'monthly', 'month', ['3 platforms', '16 feed pieces', '6 Reels', '12 Story sets', 'Campaign concepts and calendar', 'Publishing', 'Community management', 'Social listening', 'Detailed reporting', 'Strategy session', 'Basic Meta Ads management'], ['short' => 'A larger social programme with campaign and paid support.', 'media' => true]],

            ['Content', 'Content Mini', 1290, 'monthly', 'month', ['Monthly content direction', '6 designed content pieces', 'Captions and hooks', 'Light editing', 'Content handover'], ['terms' => 'Client-supplied footage by default. On-site shooting and production are extra.']],
            ['Content', 'Content Studio', 2290, 'monthly', 'month', ['Monthly content plan', '10 designed content pieces', '3 short-form video edits', 'Captions and hooks', 'Creative review'], ['terms' => 'Client-supplied footage by default. On-site shooting and production are extra.']],
            ['Content', 'Content Pro', 3490, 'monthly', 'month', ['Monthly campaign direction', '16 designed content pieces', '6 short-form video edits', 'Copy and captions', 'Creative optimisation', 'Monthly content review'], ['terms' => 'Client-supplied footage by default. On-site shooting and production are extra.']],

            ['Paid Media', 'Meta Start', 1490, 'monthly', 'month', ['Meta campaign setup', 'Audience structure', 'Creative direction', 'Ongoing optimisation', 'Monthly report'], ['media' => true]],
            ['Paid Media', 'Performance Growth', 2490, 'monthly', 'month', ['Meta campaign management', 'Prospecting and retargeting', 'Tracking review', 'Creative testing roadmap', 'Conversion optimisation', 'Monthly report'], ['media' => true]],
            ['Paid Media', 'Multi-Channel Performance', 3990, 'monthly', 'month', ['Meta plus a secondary paid channel', 'Campaign and audience architecture', 'Cross-channel tracking', 'Creative testing', 'Landing-page recommendations', 'Detailed performance report'], ['media' => true, 'terms' => 'Very large media budgets may use a custom or percentage model configured in the CMS.']],

            ['SEO', 'Local Search', 1290, 'monthly', 'month', ['Local keyword direction', 'Google Business optimisation', 'Core on-page improvements', 'Local citation guidance', 'Monthly visibility report'], ['term' => '3 months']],
            ['SEO', 'SEO Growth', 2490, 'monthly', 'month', ['Technical SEO review', 'Keyword strategy', 'On-page optimisation', 'Content recommendations', 'Authority roadmap', 'Monthly report'], ['term' => '3 months']],
            ['SEO', 'Search + Content', 3990, 'monthly', 'month', ['Technical and on-page SEO', 'Keyword and content strategy', '4 search-led content pieces', 'Internal linking', 'Conversion recommendations', 'Detailed monthly report'], ['term' => '3 months']],

            ['WhatsApp', 'WhatsApp Start', 790, 'one_time', 'one-time', ['Business profile optimisation', 'Labels and quick replies', 'Basic lead flow', 'Handover guide'], []],
            ['WhatsApp', 'WhatsApp Conversion System', 1490, 'one_time', 'one-time', ['Lead journey mapping', 'Qualification scripts', 'Follow-up sequence', 'Labels and automation plan', 'Team handover'], []],
            ['WhatsApp', 'WhatsApp + Ads', 2490, 'monthly', 'month', ['Click-to-WhatsApp campaign management', 'Lead journey and scripts', 'Tracking', 'Retargeting', 'Monthly optimisation and report'], ['media' => true]],

            ['Email/Automation', 'Email Starter', 990, 'one_time', 'one-time', ['Platform setup', 'Branded email template', 'Welcome email', 'List structure', 'Basic reporting setup'], ['terms' => 'Software and provider fees are separate.']],
            ['Email/Automation', 'Automation Build', 1990, 'one_time', 'one-time', ['Lifecycle journey mapping', 'Up to 5 automated emails', 'Trigger and segment setup', 'Testing', 'Handover'], ['terms' => 'Software and provider fees are separate.']],

            ['Launch', 'Launch Lite', 1990, 'project', 'project', ['Launch direction', 'Core messaging', 'Launch content plan', 'Channel checklist', 'Measurement setup'], []],
            ['Launch', 'Product Launch', 3490, 'project', 'project', ['Launch strategy', 'Campaign concept', 'Social content direction', 'Email or WhatsApp journey', 'Tracking and launch report'], []],
            ['Launch', 'Full Launch', 5990, 'project', 'project', ['Multi-channel launch strategy', 'Campaign creative direction', 'Content and lifecycle plan', 'Paid campaign management', 'Tracking and optimisation', 'Launch review'], ['from' => true, 'media' => true]],

            ['Growth Bundles', 'Foundation', 2990, 'monthly', 'month', ['Growth direction', '2 social platforms', '8–10 content pieces', '2 Reels', 'Social management', 'Local SEO basics', 'Google Business optimisation', 'Analytics', 'Monthly report'], ['short' => 'Build your marketing foundation.', 'badge' => 'Start here']],
            ['Growth Bundles', 'Growth', 4990, 'monthly', 'month', ['Monthly growth planning', 'Competitor monitoring', 'Campaign strategy', '2–3 social platforms', '12 posts, 4 Reels and Stories', 'Scheduling and community monitoring', 'Meta Ads management and retargeting', 'Local SEO and on-page optimisation', 'Google Business optimisation', 'Landing-page and WhatsApp journey recommendations', 'Monthly growth report', 'Strategy call'], ['short' => 'The primary joined-up growth package.', 'featured' => true, 'recommended' => true, 'badge' => 'Recommended', 'media' => true, 'term' => '3 months']],
            ['Growth Bundles', 'Scale', 7990, 'monthly', 'month', ['Growth strategy', '3 social platforms', '16 content pieces', '6 Reels', 'Campaigns and community management', 'Meta plus Google or a secondary paid channel', 'SEO and content SEO', 'Landing-page and CRO support', 'Tracking and retargeting', 'Lifecycle recommendations', 'Detailed monthly growth review'], ['short' => 'Multi-channel growth for businesses ready to scale.', 'media' => true]],
            ['Growth Bundles', 'Growth Partner+', 11990, 'monthly', 'month', ['Multi-channel strategy', 'Social and content', 'Production planning', 'Paid media management', 'SEO and website CRO', 'CRM and automation', 'Email and retention', 'Launch support', 'Advanced analytics'], ['short' => 'A custom larger partnership across the full growth system.', 'from' => true, 'media' => true]],
        ];
    }
}
