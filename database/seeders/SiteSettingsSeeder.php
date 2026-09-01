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
            ['intro_animation_enabled', true, 'features', 'bool', 'Intro animation enabled', null],
            ['custom_cursor_enabled', true, 'features', 'bool', 'Custom cursor enabled', null],
            ['ambient_decoration_enabled', true, 'features', 'bool', 'Ambient decoration enabled', null],
            ['page_transitions_enabled', true, 'features', 'bool', 'Page transition effects enabled', null],
            ['testimonials_enabled', true, 'features', 'bool', 'Testimonial section enabled', null],

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
