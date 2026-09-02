<?php

namespace App\Services;

use App\Models\ContactOption;
use App\Models\GrowthPlan;
use App\Models\MarketingCampaign;
use App\Models\MarketingChannel;
use App\Models\NavigationItem;
use App\Models\Package;
use App\Models\Page;
use App\Models\ProcessStage;
use App\Models\Project;
use App\Models\Service;
use App\Models\SocialLink;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Support\MediaResolver;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

/**
 * Builds the single JSON payload the locked Claude Design frontend reads from
 * `window.PCL_CMS`. Everything the public site can show comes from here.
 */
class SiteContentService
{
    public const CACHE_KEY = 'pcl.site-content.v1';

    public function __construct(
        private readonly SettingsRepository $settings,
        private readonly PricingService $pricing,
    ) {}

    public static function flush(): void
    {
        Cache::forget(self::CACHE_KEY);
        Cache::forget(SettingsRepository::CACHE_KEY);
    }

    /** @return array<string, mixed> */
    public function payload(bool $includeDrafts = false): array
    {
        if ($includeDrafts) {
            return $this->build(true);
        }

        return Cache::rememberForever(self::CACHE_KEY, fn () => $this->build(false));
    }

    /** @return array<string, mixed> */
    private function build(bool $includeDrafts): array
    {
        if (! $this->databaseReady()) {
            return ['ready' => false];
        }

        $projects = $this->projects($includeDrafts);
        $packages = $this->packages($includeDrafts);

        return [
            'ready' => true,
            'preview' => $includeDrafts,
            'settings' => $this->settingsPayload(),
            'flags' => $this->flags(),
            'nav' => $this->navigation(),
            'projects' => $projects,
            'projectCount' => count($projects),
            'categories' => $this->categories($projects),
            'services' => $this->services(Service::TRACK_BUILD, $includeDrafts),
            'marketingServices' => $this->services(Service::TRACK_GROWTH, $includeDrafts),
            'stages' => $this->stages(ProcessStage::TRACK_BUILD),
            'growthStages' => $this->stages(ProcessStage::TRACK_GROWTH),
            'team' => $this->team($includeDrafts),
            'socials' => $this->socials(),
            'testimonials' => $this->testimonials($includeDrafts),
            'growthPlans' => $this->publicGrowthPlans($includeDrafts, $packages),
            'packages' => $packages,
            'pricingPromotion' => $this->pricing->promotionPayload(),
            'pricingNotes' => [
                'mediaSpend' => $this->settings->string('pricing_media_spend_note', 'Advertising/media spend is separate.'),
                'thirdParty' => $this->settings->string('pricing_third_party_note', 'Third-party software/provider costs are separate.'),
                'production' => $this->settings->string('pricing_production_note', ''),
                'creatorFees' => $this->settings->string('pricing_creator_note', ''),
                'licensing' => $this->settings->string('pricing_licensing_note', ''),
                'websiteRebuild' => $this->settings->string('pricing_rebuild_note', ''),
                'multilingual' => $this->settings->string('pricing_multilingual_note', ''),
            ],
            'channels' => $this->channels(),
            'campaigns' => $this->campaigns($includeDrafts),
            'contactOptions' => $this->contactOptions(),
            'copy' => $this->copy($includeDrafts),
            'pages' => $this->pageVisibility($includeDrafts),
            'seo' => $this->seo(),
        ];
    }

    private function databaseReady(): bool
    {
        try {
            return Schema::hasTable('site_settings') && Schema::hasTable('projects');
        } catch (\Throwable) {
            return false;
        }
    }

    /** @return array<string, mixed> */
    private function settingsPayload(): array
    {
        $s = $this->settings;

        return [
            'studioName' => $s->string('studio_name', 'PixelCraftsLabStudio'),
            'shortName' => $s->string('studio_short_name', 'PixelCraftsLab'),
            'tagline' => $s->string('tagline', 'Ideas . Build . Launch'),
            'growthTagline' => $s->string('growth_tagline', 'Ideas . Build . Launch . Grow'),
            'description' => $s->string('studio_description', ''),
            'email' => $s->string('studio_email', ''),
            'phone' => $s->string('studio_phone', ''),
            'location' => $s->string('studio_location', ''),
            'country' => $s->string('studio_country', ''),
            'logo' => $s->mediaUrl('logo', 'assets/pcl-logo.png'),
            'logoDark' => $s->mediaUrl('logo_dark', ''),
            'favicon' => $s->mediaUrl('favicon', ''),
            'ctaLabel' => $s->string('default_cta_label', 'Start a project'),
            'ctaTarget' => $s->string('default_cta_target', '#contact'),
            'footerDescription' => $s->string('footer_description', ''),
            'copyright' => $s->string('footer_copyright', ''),
            'footerSecondary' => $s->string('footer_secondary', ''),
            'menuLabel' => $s->string('nav_menu_label', 'Navigate'),
            'footerSiteLabel' => $s->string('footer_site_label', 'Site'),
            'footerServicesLabel' => $s->string('footer_services_label', 'Services'),
            'footerFollowLabel' => $s->string('footer_follow_label', 'Follow'),
            'footerServices' => $this->footerServices(),
            'contactStrip' => $this->contactStrip(),
        ];
    }

    private function contactStrip(): string
    {
        $parts = array_filter([
            $this->settings->string('studio_country_code', ''),
            $this->settings->string('studio_phone', ''),
        ]);

        return implode(' · ', $parts);
    }

    /** @return list<array<string, string>> */
    private function footerServices(): array
    {
        $configured = $this->settings->get('footer_services', []);

        if (is_array($configured) && $configured !== []) {
            return array_values(array_map(
                fn ($row) => ['label' => (string) ($row['label'] ?? $row), 'url' => (string) ($row['url'] ?? '#services')],
                $configured,
            ));
        }

        return Service::query()
            ->published()
            ->topLevel()
            ->where('show_on_homepage', true)
            ->ordered()
            ->limit(6)
            ->get()
            ->map(fn (Service $s) => ['label' => $s->title, 'url' => '#services'])
            ->all();
    }

    /** @return array<string, bool> */
    private function flags(): array
    {
        $s = $this->settings;

        return [
            'siteEnabled' => $s->bool('site_enabled', true),
            'contactEnabled' => $s->bool('contact_form_enabled', true),
            'labEnabled' => $s->bool('lab_page_enabled', true),
            'growthEnabled' => $s->bool('growth_page_enabled', true),
            'introEnabled' => $s->bool('intro_animation_enabled', true),
            'cursorEnabled' => $s->bool('custom_cursor_enabled', true),
            'ambientEnabled' => $s->bool('ambient_decoration_enabled', true),
            'transitionsEnabled' => $s->bool('page_transitions_enabled', true),
            'testimonialsEnabled' => $s->bool('testimonials_enabled', true),
        ];
    }

    /** @return list<array<string, mixed>> */
    private function navigation(): array
    {
        $routeDestinations = [
            'home' => route('home', [], false),
            'work' => route('work.index', [], false),
            'services' => route('services.index', [], false),
            'growth' => route('marketing.index', [], false),
            'marketing' => route('marketing.index', [], false),
            'pricing' => route('pricing.index', [], false),
            'studio' => route('studio', [], false),
            'lab' => route('lab', [], false),
            'contact' => route('contact', [], false),
        ];

        return NavigationItem::query()
            ->where('is_visible', true)
            ->ordered()
            ->get()
            ->map(fn (NavigationItem $n) => [
                'key' => $n->route_key,
                'label' => $n->label,
                'no' => (string) ($n->number ?? ''),
                'href' => $n->destination && ! str_starts_with($n->destination, '#')
                    ? $n->destination
                    : ($routeDestinations[$n->route_key] ?? route('home', [], false)),
                'desktop' => (bool) $n->show_desktop,
                'mobile' => (bool) $n->show_mobile,
                'footer' => (bool) $n->show_footer,
                'external' => (bool) $n->is_external,
                'newTab' => (bool) $n->open_new_tab,
            ])
            ->all();
    }

    /** @return list<array<string, mixed>> */
    private function projects(bool $includeDrafts): array
    {
        $query = Project::query()->with(['metrics', 'channels'])->ordered();

        if (! $includeDrafts) {
            $query->live();
        } else {
            $query->where('is_archived', false);
        }

        return $query->get()->map(function (Project $p) {
            $metrics = $p->metrics
                ->where('is_published', true)
                ->map(fn ($m) => [
                    'label' => $m->metric_label,
                    'value' => $m->metric_value,
                    'context' => (string) ($m->metric_context ?? ''),
                ])
                ->values()
                ->all();

            return [
                'id' => $p->slug,
                'name' => $p->name,
                'cat' => $p->category,
                'kind' => (string) ($p->kind ?? ''),
                'platform' => (string) ($p->platform ?? ''),
                'size' => $p->layout_size ?: 'std',
                'short' => (string) ($p->short_description ?? ''),
                'blurb' => (string) ($p->full_description ?? $p->short_description ?? ''),
                'link' => (string) ($p->external_url ?? ''),
                'image' => $p->imageUrl(),
                'gallery' => $p->galleryUrls(),
                'initials' => (string) ($p->initials ?: $this->initialsFor($p->name)),
                'tint' => (string) ($p->primary_tint ?? ''),
                'tint2' => (string) ($p->secondary_tint ?? ''),
                'ctaLabel' => (string) ($p->cta_label ?? ''),
                'ctaUrl' => (string) ($p->cta_url ?? ''),
                'featured' => (bool) $p->is_featured,
                'caseStudy' => (string) ($p->case_study ?? ''),
                'marketing' => (bool) $p->is_marketing_case_study,
                'goal' => (string) ($p->client_goal ?? ''),
                'challenge' => (string) ($p->challenge ?? ''),
                'audience' => (string) ($p->audience ?? ''),
                'strategy' => (string) ($p->strategy ?? ''),
                'approach' => (string) ($p->approach ?? ''),
                'deliverables' => (string) ($p->deliverables ?? ''),
                'results' => (string) ($p->results ?? ''),
                'lessons' => (string) ($p->lessons ?? ''),
                'period' => (string) ($p->campaign_period ?? ''),
                'metrics' => $metrics,
                'channels' => $p->channels->pluck('name')->all(),
                'seoTitle' => (string) ($p->seo_title ?? ''),
                'seoDescription' => (string) ($p->seo_description ?? ''),
                'ogImage' => MediaResolver::url($p->og_image),
            ];
        })->all();
    }

    /** @return list<string> */
    private function categories(array $projects): array
    {
        $configured = $this->settings->get('work_filters', []);

        if (is_array($configured) && $configured !== []) {
            return array_values(array_map('strval', $configured));
        }

        $found = collect($projects)->pluck('cat')->filter()->unique()->values()->all();

        return array_merge(['All'], $found);
    }

    /** @return list<array<string, mixed>> */
    private function services(string $track, bool $includeDrafts): array
    {
        $query = Service::query()->with('channels')->track($track)->ordered();

        if (! $includeDrafts) {
            $query->published();
        }

        $all = $query->get();

        return $all->whereNull('parent_id')->map(fn (Service $s) => $this->serviceRow($s, $all))->values()->all();
    }

    /** @return array<string, mixed> */
    private function serviceRow(Service $s, $all): array
    {
        return [
            'id' => $s->slug,
            'title' => $s->title,
            'stage' => (string) ($s->stage ?? ''),
            'group' => (string) ($s->group ?? ''),
            'tag' => (string) ($s->tag ?? ''),
            'body' => (string) ($s->body ?? ''),
            'longBody' => (string) ($s->long_body ?? ''),
            'caption' => (string) ($s->caption ?? ''),
            'icon' => MediaResolver::url($s->icon),
            'featured' => (bool) $s->is_featured,
            'onHome' => (bool) $s->show_on_homepage,
            'channels' => $s->channels->pluck('name')->all(),
            'children' => $all->where('parent_id', $s->id)
                ->map(fn (Service $c) => [
                    'id' => $c->slug,
                    'title' => $c->title,
                    'group' => (string) ($c->group ?? ''),
                    'body' => (string) ($c->body ?? ''),
                    'tag' => (string) ($c->tag ?? ''),
                ])
                ->values()
                ->all(),
        ];
    }

    /** @return list<array<string, mixed>> */
    private function stages(string $track): array
    {
        return ProcessStage::query()
            ->published()
            ->track($track)
            ->ordered()
            ->get()
            ->map(fn (ProcessStage $s) => [
                'id' => $s->slug,
                'name' => $s->name,
                'no' => (string) ($s->number ?? ''),
                'body' => (string) ($s->body ?? ''),
                'accent' => (string) ($s->accent ?? ''),
            ])
            ->all();
    }

    /** @return list<array<string, mixed>> */
    private function team(bool $includeDrafts): array
    {
        $query = TeamMember::query()->ordered();

        if (! $includeDrafts) {
            $query->published();
        }

        return $query->get()->map(fn (TeamMember $m) => [
            'id' => $m->slug,
            'name' => $m->name,
            'role' => (string) ($m->role ?? ''),
            'bio' => (string) ($m->bio ?? ''),
            'image' => $m->photoUrl(),
            'initials' => (string) ($m->initials ?: $this->initialsFor($m->name)),
            'tint' => (string) ($m->primary_tint ?? ''),
            'tint2' => (string) ($m->secondary_tint ?? ''),
            'email' => (string) ($m->email ?? ''),
            'url' => (string) ($m->profile_url ?? ''),
        ])->all();
    }

    /** @return list<array<string, string>> */
    private function socials(): array
    {
        return SocialLink::query()
            ->enabled()
            ->ordered()
            ->get()
            ->map(fn (SocialLink $s) => [
                'name' => $s->platform,
                'url' => $s->url,
                'label' => (string) ($s->label ?? $s->platform),
            ])
            ->all();
    }

    /** @return list<array<string, mixed>> */
    private function testimonials(bool $includeDrafts): array
    {
        $query = Testimonial::query()->with('project')->ordered();

        if (! $includeDrafts) {
            $query->published();
        }

        return $query->get()->map(fn (Testimonial $t) => [
            'id' => (string) $t->id,
            'name' => $t->client_name,
            'company' => (string) ($t->company ?? ''),
            'role' => (string) ($t->role ?? ''),
            'quote' => $t->quote,
            'rating' => $t->rating,
            'source' => (string) ($t->source ?? ''),
            'sourceUrl' => (string) ($t->source_url ?? ''),
            'project' => $t->project?->name ?? '',
            'avatar' => $t->avatarUrl(),
            'featured' => (bool) $t->is_featured,
            'attribution' => trim(implode(', ', array_filter([$t->client_name, $t->role, $t->company]))),
        ])->all();
    }

    /** @return list<array<string, mixed>> */
    private function growthPlans(bool $includeDrafts): array
    {
        $query = GrowthPlan::query()->with(['items', 'channels'])->ordered();

        if (! $includeDrafts) {
            $query->published();
        }

        return $query->get()->map(fn (GrowthPlan $p) => [
            'id' => $p->slug,
            'name' => $p->name,
            'short' => (string) ($p->short_description ?? ''),
            'body' => (string) ($p->full_description ?? ''),
            'idealFor' => (string) ($p->ideal_for ?? ''),
            'duration' => (string) ($p->duration ?? ''),
            'price' => $p->priceDisplay(),
            'highlight' => (string) ($p->highlight_text ?? ''),
            'ctaLabel' => (string) ($p->cta_label ?: 'Start a project'),
            'ctaUrl' => (string) ($p->cta_url ?: '#contact'),
            'accent' => (string) ($p->accent ?? ''),
            'featured' => (bool) $p->is_featured,
            'channels' => $p->channels->pluck('name')->all(),
            'items' => $p->items->where('is_enabled', true)->map(fn ($i) => [
                'title' => $i->title,
                'description' => (string) ($i->description ?? ''),
            ])->values()->all(),
        ])->all();
    }

    /** @return list<array<string, mixed>> */
    private function packages(bool $includeDrafts): array
    {
        if (! Schema::hasTable('packages')) {
            return [];
        }

        $query = Package::query()
            ->with('items')
            ->orderByRaw("case when category = 'Growth Bundles' then 0 else 1 end")
            ->ordered();

        if (! $includeDrafts) {
            $query->published();
        }

        return $query->get()
            ->map(fn (Package $package) => $this->pricing->packagePayload($package))
            ->all();
    }

    /** @return list<array<string, mixed>> */
    private function publicGrowthPlans(bool $includeDrafts, array $packages): array
    {
        $growthPackages = collect($packages)
            ->where('category', 'Growth Bundles')
            ->values()
            ->all();

        return $growthPackages !== [] ? $growthPackages : $this->growthPlans($includeDrafts);
    }

    /** @return list<array<string, string>> */
    private function channels(): array
    {
        return MarketingChannel::query()
            ->enabled()
            ->ordered()
            ->get()
            ->map(fn (MarketingChannel $c) => [
                'id' => $c->slug,
                'name' => $c->name,
                'label' => (string) ($c->label ?: $c->name),
                'body' => (string) ($c->description ?? ''),
                'accent' => (string) ($c->accent ?? ''),
            ])
            ->all();
    }

    /** @return list<array<string, mixed>> */
    private function campaigns(bool $includeDrafts): array
    {
        $query = MarketingCampaign::query()->with(['channels', 'project'])->ordered();

        if (! $includeDrafts) {
            $query->published();
        }

        return $query->get()->map(fn (MarketingCampaign $c) => [
            'id' => $c->slug,
            'name' => $c->name,
            'client' => (string) ($c->client_name ?: $c->project?->name ?? ''),
            'type' => (string) ($c->campaign_type ?? ''),
            'goal' => (string) ($c->goal ?? ''),
            'status' => $c->status,
            'summary' => (string) ($c->summary ?? ''),
            'strategy' => (string) ($c->strategy ?? ''),
            'creative' => (string) ($c->creative_approach ?? ''),
            'results' => (string) ($c->results ?? ''),
            'channels' => $c->channels->pluck('name')->all(),
            'featured' => (bool) $c->is_featured,
        ])->all();
    }

    /** @return array<string, list<array<string, string>>> */
    private function contactOptions(): array
    {
        $grouped = ContactOption::query()->enabled()->ordered()->get()->groupBy('type');

        $out = [];

        foreach (ContactOption::TYPES as $type) {
            $out[$type] = ($grouped[$type] ?? collect())
                ->map(fn (ContactOption $o) => [
                    'label' => $o->label,
                    'value' => $o->value,
                    'group' => (string) ($o->group ?? ''),
                ])
                ->values()
                ->all();
        }

        return $out;
    }

    /**
     * Nested page → section → field copy tree. Empty values are omitted so the
     * frontend fallback text wins instead of rendering a blank.
     *
     * @return array<string, array<string, array<string, mixed>>>
     */
    private function copy(bool $includeDrafts): array
    {
        $pages = Page::query()->with('sections')->get();
        $out = [];

        foreach ($pages as $page) {
            if (! $page->is_published && ! $includeDrafts) {
                continue;
            }

            $sections = [];

            foreach ($page->sections as $section) {
                if (! $section->is_enabled && ! $includeDrafts) {
                    continue;
                }

                $fields = array_filter([
                    'eyebrow' => $section->eyebrow,
                    'heading' => $section->heading,
                    'subheading' => $section->subheading,
                    'body' => $section->body,
                    'ctaLabel' => $section->cta_label,
                    'ctaUrl' => $section->cta_url,
                    'cta2Label' => $section->secondary_cta_label,
                    'cta2Url' => $section->secondary_cta_url,
                    'media' => MediaResolver::url($section->media),
                ], fn ($v) => $v !== null && $v !== '');

                foreach (($section->settings ?? []) as $k => $v) {
                    if ($v !== null && $v !== '') {
                        $fields[$k] = $v;
                    }
                }

                $fields['enabled'] = (bool) $section->is_enabled;
                $sections[$section->section_key] = $fields;
            }

            $out[$page->key] = $sections;
        }

        return $out;
    }

    /** @return array<string, bool> */
    private function pageVisibility(bool $includeDrafts): array
    {
        return Page::query()
            ->get()
            ->mapWithKeys(fn (Page $p) => [$p->key => $includeDrafts ? true : (bool) $p->is_published])
            ->all();
    }

    /** @return array<string, mixed> */
    private function seo(): array
    {
        $s = $this->settings;

        return [
            'title' => $s->string('seo_site_title', $s->string('studio_name', 'PixelCraftsLab')),
            'description' => $s->string('seo_default_description', ''),
            'ogImage' => $s->mediaUrl('seo_og_image', ''),
            'twitterImage' => $s->mediaUrl('seo_twitter_image', ''),
            'robotsIndex' => $s->bool('seo_robots_index', true),
            'canonicalBase' => $s->string('seo_canonical_base', ''),
        ];
    }

    private function initialsFor(string $name): string
    {
        $clean = preg_replace('/[^A-Za-z ]/', '', $name) ?? '';

        return collect(explode(' ', $clean))
            ->filter()
            ->take(2)
            ->map(fn ($w) => strtoupper($w[0]))
            ->implode('');
    }
}
