<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

/**
 * Serves the locked Claude Design document verbatim, with two additions made
 * to <head> only:
 *
 *   1. dynamic SEO metadata
 *   2. a `window.PCL_CMS` payload injected BEFORE support.js boots
 *
 * The document body — layout, styles, animations, runtime expressions — is
 * never touched here.
 */
class SiteRenderer
{
    private const SOURCE = 'pixelcraftslab/PixelCraftsLab Site.dc.html';

    public function __construct(
        private readonly SiteContentService $content,
        private readonly SettingsRepository $settings,
    ) {}

    public function sourcePath(): string
    {
        return resource_path(self::SOURCE);
    }

    public function render(bool $preview = false, ?string $csrfToken = null, array $context = []): string
    {
        $html = File::get($this->sourcePath());
        $html = $this->routeAwareLinks($html);

        $payload = $this->content->payload($preview);
        $payload['endpoints'] = [
            'contact' => route('contact.store', [], false),
        ];
        $payload['routing'] = [
            'route' => $context['route'] ?? 'home',
            'projectSlug' => $context['projectSlug'] ?? null,
            'canonicalUrl' => $context['canonicalUrl'] ?? route('home', [], false),
            'urls' => [
                'home' => route('home', [], false),
                'work' => route('work.index', [], false),
                'services' => route('services.index', [], false),
                'growth' => route('marketing.index', [], false),
                'marketing' => route('marketing.index', [], false),
                'pricing' => route('pricing.index', [], false),
                'studio' => route('studio', [], false),
                'lab' => route('lab', [], false),
                'contact' => route('contact', [], false),
            ],
            'projectUrls' => collect($payload['projects'] ?? [])->mapWithKeys(
                fn (array $project) => [
                    $project['id'] => route('projects.show', ['project' => $project['id']], false),
                ],
            )->all(),
        ];
        $payload['csrf'] = $csrfToken ?? csrf_token();

        return $this->injectHead($html, $this->metaTags($payload, $context['seo'] ?? []).$this->payloadScript($payload));
    }

    private function payloadScript(array $payload): string
    {
        // JSON_HEX_* closes the </script> and attribute-escape holes, so the
        // payload can never break out of the script element.
        $json = json_encode(
            $payload,
            JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
                | JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE,
        );

        if ($json === false) {
            $json = '{"ready":false}';
        }

        return "\n<script>window.PCL_CMS = {$json};</script>\n";
    }

    private function metaTags(array $payload, array $routeSeo = []): string
    {
        $seo = $payload['seo'] ?? [];
        $settings = $payload['settings'] ?? [];

        $title = ($routeSeo['title'] ?? null) ?: ($seo['title'] ?? ($settings['studioName'] ?? 'PixelCraftsLab'));
        $description = ($routeSeo['description'] ?? null) ?: ($seo['description'] ?? '');
        $ogTitle = ($routeSeo['ogTitle'] ?? null) ?: $title;
        $ogDescription = ($routeSeo['ogDescription'] ?? null) ?: $description;
        $ogImage = $this->absolute(($routeSeo['ogImage'] ?? null) ?: ($seo['ogImage'] ?? ''));
        $twitterImage = $this->absolute($seo['twitterImage'] ?? '') ?: $ogImage;
        $canonical = $this->absolute(($routeSeo['canonical'] ?? null) ?: ($seo['canonicalBase'] ?? ''));
        $favicon = $this->absolute($settings['favicon'] ?? '');

        $tags = [
            '<title>'.e($title).'</title>',
            '<meta name="description" content="'.e($description).'" />',
            '<meta property="og:type" content="website" />',
            '<meta property="og:site_name" content="'.e($settings['studioName'] ?? '').'" />',
            '<meta property="og:title" content="'.e($ogTitle).'" />',
            '<meta property="og:description" content="'.e($ogDescription).'" />',
            '<meta name="twitter:card" content="summary_large_image" />',
            '<meta name="twitter:title" content="'.e($title).'" />',
            '<meta name="twitter:description" content="'.e($description).'" />',
        ];

        if ($ogImage !== '') {
            $tags[] = '<meta property="og:image" content="'.e($ogImage).'" />';
        }

        if ($twitterImage !== '') {
            $tags[] = '<meta name="twitter:image" content="'.e($twitterImage).'" />';
        }

        if ($canonical !== '') {
            $tags[] = '<link rel="canonical" href="'.e(rtrim($canonical, '/')).'" />';
            $tags[] = '<meta property="og:url" content="'.e(rtrim($canonical, '/')).'" />';
        }

        if ($favicon !== '') {
            $tags[] = '<link rel="icon" href="'.e($favicon).'" />';
        }

        $tags[] = ($routeSeo['robotsIndex'] ?? $seo['robotsIndex'] ?? true)
            ? '<meta name="robots" content="index, follow" />'
            : '<meta name="robots" content="noindex, nofollow" />';

        return "\n".implode("\n", $tags)."\n";
    }

    private function absolute(string $url): string
    {
        if ($url === '' || str_starts_with($url, 'http') || str_starts_with($url, '//')) {
            return $url;
        }

        return rtrim((string) config('app.url'), '/').'/'.ltrim($url, '/');
    }

    /**
     * Gives every rendered anchor a real Laravel destination while leaving the
     * Claude transition handlers in place for enhanced client-side navigation.
     */
    private function routeAwareLinks(string $html): string
    {
        $links = [
            'home' => route('home', [], false),
            'work' => route('work.index', [], false),
            'services' => route('services.index', [], false),
            'growth' => route('marketing.index', [], false),
            'studio' => route('studio', [], false),
            'lab' => route('lab', [], false),
            'contact' => route('contact', [], false),
        ];

        foreach ($links as $key => $url) {
            $html = str_replace('href="#'.$key.'"', 'href="'.e($url).'"', $html);
        }

        return str_replace(
            [
                'onClick="{{ p.open }}" href="#project"',
                'onClick="{{ nextP.open }}" href="#project"',
                'onClick="{{ openFirst }}" href="#project"',
            ],
            [
                'onClick="{{ p.open }}" href="{{ p.url }}"',
                'onClick="{{ nextP.open }}" href="{{ nextP.url }}"',
                'onClick="{{ openFirst }}" href="{{ featuredUrl }}"',
            ],
            $html,
        );
    }

    /**
     * Inserts markup immediately before the support.js tag so the payload is
     * defined before the Claude runtime evaluates the component script.
     */
    private function injectHead(string $html, string $markup): string
    {
        $anchor = '<script src="./support.js"></script>';

        if (str_contains($html, $anchor)) {
            return str_replace($anchor, $markup.$anchor, $html);
        }

        return str_replace('</head>', $markup.'</head>', $html);
    }
}
