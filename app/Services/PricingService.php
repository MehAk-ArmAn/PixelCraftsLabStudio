<?php

namespace App\Services;

use App\Models\Package;
use Carbon\CarbonImmutable;

class PricingService
{
    public function __construct(private readonly SettingsRepository $settings) {}

    /** @return array<string, mixed> */
    public function packagePayload(Package $package): array
    {
        $basePrice = $package->promotional_price ?? $package->price;
        $foundingPrice = $this->foundingPrice($package, $basePrice === null ? null : (float) $basePrice);
        $currentPrice = $foundingPrice ?? ($basePrice === null ? null : (float) $basePrice);
        $originalPrice = $foundingPrice !== null
            ? ($basePrice === null ? null : (float) $basePrice)
            : ($package->promotional_price !== null
                ? (float) ($package->original_price ?? $package->price)
                : ($package->original_price === null ? null : (float) $package->original_price));

        return [
            'id' => $package->slug,
            'name' => $package->name,
            'category' => $package->category,
            'billingType' => $package->billing_type,
            'short' => (string) ($package->short_description ?? ''),
            'body' => (string) ($package->full_description ?? ''),
            'price' => $this->formatPrice($package, $currentPrice),
            'rawPrice' => $currentPrice,
            'originalPrice' => $originalPrice === null ? '' : $this->formatMoney($originalPrice, $package->currency),
            'startingFrom' => $package->is_starting_from ? 'From' : '',
            'period' => (string) ($package->billing_period ?? ''),
            'featured' => (bool) $package->is_featured,
            'recommended' => (bool) $package->is_recommended,
            'badge' => (string) ($this->promotionLabel($package, $foundingPrice !== null) ?: $package->badge),
            'ctaLabel' => (string) ($package->cta_label ?: 'Start a project'),
            'ctaUrl' => (string) ($package->cta_url ?: '#contact'),
            'terms' => (string) ($package->terms ?? ''),
            'minimumTerm' => (string) ($package->minimum_term ?? ''),
            'mediaNote' => $package->media_spend_separated
                ? $this->settings->string('pricing_media_spend_note', 'Advertising/media spend is separate.')
                : '',
            'items' => $package->items->where('is_included', true)->map(fn ($item) => [
                'text' => $item->text,
                'title' => $item->text,
                'group' => (string) ($item->group ?? ''),
                'included' => (bool) $item->is_included,
                'highlighted' => (bool) $item->is_highlighted,
            ])->values()->all(),
        ];
    }

    /** @return array<string, mixed> */
    public function promotionPayload(): array
    {
        $active = $this->foundingPromotionActive();
        $limit = max(0, (int) $this->settings->get('founding_client_limit', 0));
        $claimed = max(0, (int) $this->settings->get('founding_client_claimed_count', 0));
        $showRemaining = $active
            && $this->settings->bool('founding_client_show_remaining', false)
            && $limit > 0;

        return [
            'active' => $active,
            'text' => $active ? $this->settings->string('founding_client_promotion_text', '') : '',
            'discountPercent' => $active ? $this->discountPercent() : 0,
            'durationMonths' => $active ? max(0, (int) $this->settings->get('founding_client_duration_months', 0)) : 0,
            'remaining' => $showRemaining ? max(0, $limit - $claimed) : null,
        ];
    }

    private function foundingPrice(Package $package, ?float $price): ?float
    {
        if ($price === null || $package->category !== 'Growth Bundles' || ! $this->foundingPromotionActive()) {
            return null;
        }

        return round($price * (1 - ($this->discountPercent() / 100)), 2);
    }

    private function foundingPromotionActive(): bool
    {
        if (! $this->settings->bool('founding_client_enabled', false) || $this->discountPercent() <= 0) {
            return false;
        }

        $limit = max(0, (int) $this->settings->get('founding_client_limit', 0));
        $claimed = max(0, (int) $this->settings->get('founding_client_claimed_count', 0));

        if ($limit > 0 && $claimed >= $limit) {
            return false;
        }

        $today = CarbonImmutable::today();
        $starts = $this->dateSetting('founding_client_starts_on');
        $ends = $this->dateSetting('founding_client_ends_on');

        return (! $starts || $today->greaterThanOrEqualTo($starts))
            && (! $ends || $today->lessThanOrEqualTo($ends));
    }

    private function discountPercent(): float
    {
        return min(100, max(0, (float) $this->settings->get('founding_client_discount_percent', 0)));
    }

    private function dateSetting(string $key): ?CarbonImmutable
    {
        $value = $this->settings->string($key, '');

        if ($value === '') {
            return null;
        }

        try {
            return CarbonImmutable::parse($value)->startOfDay();
        } catch (\Throwable) {
            return null;
        }
    }

    private function promotionLabel(Package $package, bool $foundingPromotionApplied): string
    {
        if ($foundingPromotionApplied) {
            return $this->settings->string('founding_client_promotion_text', 'Founding client offer');
        }

        return (string) ($package->promotion_label ?? '');
    }

    private function formatPrice(Package $package, ?float $price): string
    {
        if ($price === null || $package->billing_type === 'custom') {
            return 'Custom';
        }

        return $this->formatMoney($price, $package->currency);
    }

    private function formatMoney(float $price, string $currency): string
    {
        $decimals = fmod($price, 1.0) === 0.0 ? 0 : 2;

        return trim($currency).' '.number_format($price, $decimals);
    }
}
