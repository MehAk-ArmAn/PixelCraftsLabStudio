<?php

namespace Database\Seeders;

use App\Services\SettingsRepository;
use Illuminate\Database\Seeder;

/**
 * Defaults are the values the locked design already displayed, so the first
 * CMS render reads exactly the same as the hard-coded original.
 */
class SiteSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = app(SettingsRepository::class);

        foreach ($this->definitions() as $sort => [$key, $value, $group, $type, $label, $hint]) {
            $settings->ensure($key, $value, $group, $type, $label, $hint, ($sort + 1) * 10);
        }
    }

    /** @return list<array{0:string,1:mixed,2:string,3:string,4:string,5:?string}> */
    private function definitions(): array
    {
        return [
            // ------------------------------------------------------------ home
            ['intro_animation_enabled', true, 'home', 'bool', 'Home intro enabled', 'When disabled, the informational homepage renders immediately.'],
            ['home_intro_replay_on_home', true, 'home', 'bool', 'Replay on Home click', 'Replay when Home is deliberately selected, including while already on Home.'],
            ['home_intro_mode', 'forge', 'home', 'string', 'Mode preset', 'Allowed: forge or minimal.'],
            ['home_intro_heading', 'PixelCraftsLab', 'home', 'string', 'Intro heading', null],
            ['home_intro_subheading', 'A creative technology studio. We design it, build it, launch it — then help it grow.', 'home', 'text', 'Support text', null],
            ['home_intro_cta', 'Enter the studio', 'home', 'string', 'Entry CTA', null],
            ['home_intro_duration', 2600, 'home', 'int', 'Duration', 'Milliseconds; accepted range 900–6000.'],
            ['home_intro_intensity', '1', 'home', 'string', 'Intensity preset value', 'Motion strength from 0 to 1.6.'],
            ['home_intro_accent_preset', 'violet-orange', 'home', 'string', 'Accent preset', 'Allowed: violet-orange, violet, orange or ink.'],
            ['home_intro_show_project_fragments', true, 'home', 'bool', 'Show project fragments', 'Uses real Admin-selected project media.'],
            ['home_intro_interaction_preset', 'pointer-parallax', 'home', 'string', 'Interaction preset', 'Allowed: pointer-parallax or static.'],
            ['home_intro_background_preset', 'paper-grid', 'home', 'string', 'Background preset', 'Allowed: paper-grid or quiet.'],
            ['home_intro_transition_preset', 'scatter', 'home', 'string', 'Transition preset', 'Allowed: scatter or fade.'],

            // ---------------------------------------------------------- studio
            ['studio_name', 'PixelCraftsLabStudio', 'studio', 'string', 'Studio name', 'Used in the footer and metadata.'],
            ['studio_short_name', 'PixelCraftsLab', 'studio', 'string', 'Short name', 'Shown in the header lockup.'],
            ['tagline', 'Ideas . Build . Launch', 'studio', 'string', 'Tagline', 'The official brand line under the logo and in the intro.'],
            ['growth_tagline', 'Ideas . Build . Launch . Grow', 'studio', 'string', 'Extended growth line', 'Optional longer line used in growth/marketing copy. Does not replace the tagline.'],
            ['studio_description', 'Ideas. Build. Launch. We build high-converting websites and digital tools where creativity meets precision.', 'studio', 'text', 'Primary description', null],
            ['logo', 'assets/pcl-logo.png', 'studio', 'string', 'Logo', 'Header logo. Pick from the media library or keep the existing asset path.'],
            ['logo_dark', '', 'studio', 'string', 'Dark logo', 'Optional.'],
            ['favicon', '', 'studio', 'string', 'Favicon', 'Optional. Falls back to /favicon.ico.'],
            ['default_cta_label', 'Start a project', 'studio', 'string', 'Default CTA label', null],
            ['default_cta_target', '#contact', 'studio', 'string', 'Default CTA destination', null],

            // --------------------------------------------------------- contact
            ['studio_email', '', 'contact', 'string', 'Studio email', 'Shown publicly on the contact page and footer. Empty shows the placeholder.'],
            ['contact_recipient_email', '', 'contact', 'string', 'Enquiry recipient', 'Where new enquiries are emailed. Falls back to the studio email.'],
            ['studio_phone', '+44 7871 284043', 'contact', 'string', 'Phone', null],
            ['studio_country_code', 'UK', 'contact', 'string', 'Country code label', 'Prefix used in the header/footer contact strip, e.g. "UK".'],
            ['studio_location', 'United Kingdom', 'contact', 'string', 'Location', null],
            ['studio_country', 'United Kingdom', 'contact', 'string', 'Country', null],
            ['contact_notifications_enabled', true, 'contact', 'bool', 'Email notifications', 'Send an email when an enquiry arrives. Enquiries are always saved regardless.'],
            ['contact_subject_prefix', '[PixelCraftsLab]', 'contact', 'string', 'Notification subject prefix', null],
            ['contact_success_message', 'Brief received. We will be in touch shortly.', 'contact', 'text', 'Success message', 'Shown in the confirmation state after a successful submission.'],
            ['contact_disabled_message', 'The enquiry form is currently closed. Please email us instead.', 'contact', 'text', 'Form disabled message', null],
            ['site_disabled_message', 'The site is temporarily unavailable.', 'contact', 'text', 'Site disabled message', null],

            // ---------------------------------------------------------- footer
            ['footer_description', 'Ideas. Build. Launch. We build high-converting websites and digital tools where creativity meets precision.', 'footer', 'text', 'Footer description', null],
            ['footer_copyright', '© 2026 PixelCraftsLabStudio', 'footer', 'string', 'Copyright', null],
            ['footer_secondary', '', 'footer', 'string', 'Secondary footer text', 'The original prototype note was removed. Leave empty to show nothing.'],
            ['footer_site_label', 'Site', 'footer', 'string', 'Footer navigation heading', null],
            ['footer_services_label', 'Services', 'footer', 'string', 'Footer services heading', null],
            ['footer_follow_label', 'Follow', 'footer', 'string', 'Footer social heading', null],
            ['nav_menu_label', 'Navigate', 'footer', 'string', 'Mobile menu heading', null],

            // -------------------------------------------------------- features
            ['site_enabled', true, 'features', 'bool', 'Website enabled', 'Turning this off returns a 503 for visitors. The admin panel stays reachable.'],
            ['contact_form_enabled', true, 'features', 'bool', 'Contact form enabled', null],
            ['lab_page_enabled', true, 'features', 'bool', 'Lab page enabled', null],
            ['growth_page_enabled', true, 'features', 'bool', 'Growth page enabled', null],
            ['custom_cursor_enabled', true, 'features', 'bool', 'Custom cursor enabled', null],
            ['ambient_decoration_enabled', true, 'features', 'bool', 'Ambient decoration enabled', null],
            ['page_transitions_enabled', true, 'features', 'bool', 'Page transition effects enabled', null],
            ['testimonials_enabled', true, 'features', 'bool', 'Testimonial section enabled', null],

            // --------------------------------------------------------- pricing
            ['pricing_media_spend_note', 'Advertising/media spend is separate.', 'pricing', 'text', 'Media-spend note', 'Shown on packages where media spend is explicitly separated.'],
            ['pricing_third_party_note', 'Third-party software/provider costs are separate.', 'pricing', 'text', 'Third-party costs note', 'Used for automation, email and other provider-dependent work.'],
            ['pricing_production_note', 'On-site shoots and professional production may be separately scoped.', 'pricing', 'text', 'Production note', null],
            ['pricing_creator_note', 'Influencer and creator fees are separate.', 'pricing', 'text', 'Creator-fees note', null],
            ['pricing_licensing_note', 'Paid stock and licence costs are separate where applicable.', 'pricing', 'text', 'Stock/licensing note', null],
            ['pricing_rebuild_note', 'Large website rebuilds outside a marketing retainer are separately scoped.', 'pricing', 'text', 'Website-rebuild note', null],
            ['pricing_multilingual_note', 'Arabic or multilingual content beyond the included scope may be separately quoted.', 'pricing', 'text', 'Multilingual-content note', null],
            ['founding_client_enabled', false, 'pricing', 'bool', 'Founding Client offer enabled', 'Disabled by default. Enable only while the real offer is available.'],
            ['founding_client_discount_percent', 20, 'pricing', 'int', 'Founding discount percent', 'Calculated from the stored Growth Bundle price.'],
            ['founding_client_duration_months', 3, 'pricing', 'int', 'Discount duration in months', null],
            ['founding_client_limit', 8, 'pricing', 'int', 'Client limit', 'The real maximum number of clients who may claim this offer.'],
            ['founding_client_claimed_count', 0, 'pricing', 'int', 'Claimed client count', 'Update this from real records only.'],
            ['founding_client_show_remaining', false, 'pricing', 'bool', 'Show remaining count', 'Only enable when the claimed count is actively maintained.'],
            ['founding_client_promotion_text', 'Founding Client — 20% off the first 3 months', 'pricing', 'string', 'Promotion text', null],
            ['founding_client_starts_on', '', 'pricing', 'string', 'Promotion start date', 'Optional, YYYY-MM-DD.'],
            ['founding_client_ends_on', '', 'pricing', 'string', 'Promotion end date', 'Optional, YYYY-MM-DD.'],

            // ------------------------------------------------------------- seo
            ['seo_site_title', 'PixelCraftsLab Studio — Ideas. Build. Launch. Grow.', 'seo', 'string', 'Site title', null],
            ['seo_default_description', 'PixelCraftsLab is a creative technology studio building websites, apps, games and brand experiences — and the digital marketing and growth work that takes them to an audience.', 'seo', 'text', 'Default meta description', null],
            ['seo_og_image', '', 'seo', 'string', 'Default OG image', null],
            ['seo_twitter_image', '', 'seo', 'string', 'Twitter/X card image', 'Falls back to the OG image.'],
            ['seo_robots_index', true, 'seo', 'bool', 'Allow search engines to index', null],
            ['seo_canonical_base', '', 'seo', 'string', 'Canonical base URL', 'e.g. https://pixelcraftslab.com'],
        ];
    }
}
