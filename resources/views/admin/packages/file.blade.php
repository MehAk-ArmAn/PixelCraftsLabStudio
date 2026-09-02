{{--
    The package file: one screen that holds everything PixelCraftsLab knows
    about a package. Two visual languages, never mixed —

      .band.public    purple   what the client sees on the website
      .band.internal  orange   staff only, never leaves the admin

    Fields inside an internal band that the `packages` table cannot store yet
    are rendered as a spec (label + intended column + purpose) under a
    "not stored yet" banner, so nothing looks editable that would be lost.
--}}
@php
    $sections = collect($schema)->groupBy(fn ($f) => $f['section'] ?? 'Details');

    // Everything the PricingService already publishes, for the preview card.
    $currency = $record->currency ?: 'AED';
    $shown    = $record->promotional_price ?? $record->price;
    $was      = $record->promotional_price !== null
                    ? ($record->original_price ?? $record->price)
                    : $record->original_price;
    $isCustom = $record->billing_type === 'custom' || $shown === null;
    $qualifier = $isCustom ? '' : ($record->is_starting_from ? 'Estimated from' : 'Estimated');
    $money = fn ($v) => $v === null
        ? null
        : $currency.' '.number_format((float) $v, fmod((float) $v, 1.0) === 0.0 ? 0 : 2);

    $included = $record->exists ? $record->items->where('is_included', true) : collect();
    $excluded = $record->exists ? $record->items->where('is_included', false) : collect();
@endphp

<div class="pkg-head">
    <h2 class="name">{{ $record->name ?: 'New package' }}</h2>
    <span class="code">{{ $record->slug ?: 'no-slug-yet' }}</span>
    @if ($record->category)<span class="badge on">{{ $record->category }}</span>@endif
    <span class="badge {{ $record->is_published ? 'on' : 'off' }}">{{ $record->is_published ? 'Published' : 'Hidden' }}</span>
    @if ($record->is_recommended)<span class="badge hot">Recommended</span>@endif
    <span style="margin-left:auto;"></span>
    <span class="small muted">
        Last updated {{ $record->updated_at?->format('j M Y, H:i') ?? 'never' }}
    </span>
</div>

{{-- ================================================================ PUBLIC --}}
<div class="band public">
    <div class="band-head">
        <span class="chip eye dot">Visible to clients</span>
        <h2>Public package details</h2>
    </div>
    <div class="band-body">
        <p class="band-note">
            Everything in this band is published to the Marketing &amp; Growth page and appears in the
            public <code>window.PCL_CMS</code> payload. Write it for a client, not for the studio.
        </p>

        @foreach ($sections as $sectionName => $fields)
            <span class="section-head">{{ $sectionName }}</span>
            @foreach ($fields as $field)
                @include('admin.partials.field', ['field' => $field, 'record' => $record, 'extra' => $extra])
            @endforeach
        @endforeach
    </div>
</div>

@if ($mode !== 'create')
{{-- =================================================== PUBLIC DELIVERABLES --}}
<div class="band public">
    <div class="band-head">
        <span class="chip eye dot">Visible to clients</span>
        <h2>Public deliverables</h2>
    </div>
    <div class="band-body">
        <p class="band-note">
            Ticked items publish as the card's deliverable list. Un-ticking an item turns it into a
            stated exclusion — it stays on file here but is withheld from the website.
        </p>

        <div class="grid-2">
            <div>
                <span class="section-head">Included &middot; {{ $included->count() }}</span>
                @forelse ($included as $item)
                    <div class="row" style="align-items:baseline; padding:6px 0; border-bottom:1px solid var(--line-soft);">
                        <span>{{ $item->text }}</span>
                        @if ($item->group)<span class="badge">{{ $item->group }}</span>@endif
                        @if ($item->is_highlighted)<span class="badge hot">Highlighted</span>@endif
                    </div>
                @empty
                    <p class="small muted">Nothing published yet.</p>
                @endforelse
            </div>
            <div>
                <span class="section-head">Excluded &middot; {{ $excluded->count() }}</span>
                @forelse ($excluded as $item)
                    <div class="row" style="align-items:baseline; padding:6px 0; border-bottom:1px solid var(--line-soft);">
                        <span class="muted">{{ $item->text }}</span>
                        @if ($item->group)<span class="badge off">{{ $item->group }}</span>@endif
                    </div>
                @empty
                    <p class="small muted">No stated exclusions.</p>
                @endforelse
            </div>
        </div>

        <p class="small muted" style="margin:14px 0 0;">
            Edit, add and reorder items in the <strong>Package items</strong> panel in the sidebar.
        </p>
    </div>
</div>
@endif

{{-- ================================================== INTERNAL — DELIVERY --}}
<div class="band internal">
    <div class="band-head">
        <span class="chip lock dot">Admin only</span>
        <h2>Internal delivery details</h2>
    </div>
    <div class="band-body">
        <div class="pending">
            <strong>Designed, not stored yet.</strong>
            These fields have a home on this screen but no column on <code>packages</code>.
            Codex wires them; the column name on each card is the intended one. Nothing here is
            editable until then, so nothing typed can be lost.
        </div>

        <div class="spec">
            @foreach ([
                ['Public display name', 'public_name', 'Client-facing name when it differs from the internal one.'],
                ['Internal package code', 'internal_code', 'Short reference for quotes and invoices.'],
                ['Number of platforms', 'platform_count', 'How many channels the fee covers.'],
                ['Posts per month', 'post_count', 'Static/carousel volume included.'],
                ['Reels / videos per month', 'video_count', 'Short-form volume included.'],
                ['Story sets per month', 'story_count', 'Story volume included.'],
                ['Community management level', 'community_level', 'None, light, standard or full.'],
                ['SEO inclusion', 'seo_inclusion', 'What search work, if any, the fee covers.'],
                ['Ads management inclusion', 'ads_inclusion', 'Whether campaign management is in scope.'],
                ['Campaign limits', 'campaign_limit', 'How many concurrent campaigns are covered.'],
                ['Media-spend threshold', 'media_spend_threshold', 'Spend level above which the fee is renegotiated.'],
                ['Client-supplied footage required', 'requires_client_footage', 'Whether delivery depends on client assets.'],
                ['Production / shooting required', 'requires_production', 'Whether a shoot is needed and who pays.'],
                ['Reporting level', 'reporting_level', 'Frequency and depth of reporting.'],
                ['Strategy calls', 'strategy_calls', 'How many calls per cycle are included.'],
                ['Setup / onboarding requirement', 'onboarding_requirement', 'One-off work before the cycle starts.'],
                ['Add-ons', 'addons', 'Extras commonly attached to this package.'],
                ['Third-party costs', 'third_party_costs', 'Tools or licences the client pays for directly.'],
                ['Internal recommended scope', 'recommended_scope', 'What the studio considers a healthy scope.'],
                ['Delivery workload notes', 'workload_notes', 'Realistic effort, for capacity planning.'],
            ] as [$label, $column, $why])
                <div class="row-item">
                    <b>{{ $label }}</b>
                    <code>{{ $column }}</code>
                    <span class="why">{{ $why }}</span>
                </div>
            @endforeach
        </div>

        <span class="section-head">Already stored</span>
        <div class="spec">
            @foreach ([
                ['Minimum commitment', 'minimum_term', $record->minimum_term ?: '—'],
                ['Media spend separate', 'media_spend_separated', $record->media_spend_separated ? 'Yes' : 'No'],
                ['Inclusions / exclusions', 'package_items.is_included', $included->count().' in / '.$excluded->count().' out'],
            ] as [$label, $column, $value])
                <div class="row-item live">
                    <b>{{ $label }}</b>
                    <code>{{ $column }}</code>
                    <span class="why">{{ $value }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- =================================================== INTERNAL — PRICING --}}
<div class="band internal">
    <div class="band-head">
        <span class="chip lock dot">Admin only</span>
        <h2>Internal pricing notes</h2>
    </div>
    <div class="band-body">
        <div class="pending">
            <strong>Never publishable.</strong>
            Floor pricing and margin guidance must stay out of <code>window.PCL_CMS</code>,
            the public HTML and every public JSON response — including admin preview.
        </div>

        <div class="spec">
            @foreach ([
                ['Minimum acceptable fee', 'minimum_fee', 'The floor. Below this, decline or re-scope.'],
                ['Internal cost considerations', 'cost_notes', 'What this package actually costs to deliver.'],
                ['Internal pricing guidance', 'pricing_guidance', 'How to move within the range on a call.'],
                ['Discount eligibility', 'discount_eligibility', 'Whether and how far this package may be discounted.'],
                ['Founding Client eligibility', 'founding_eligible', 'Whether the founding offer applies.'],
                ['Custom quote notes', 'custom_quote_notes', 'When to quote bespoke instead of listing.'],
                ['Scope-risk notes', 'scope_risk_notes', 'Where this package usually overruns.'],
            ] as [$label, $column, $why])
                <div class="row-item">
                    <b>{{ $label }}</b>
                    <code>{{ $column }}</code>
                    <span class="why">{{ $why }}</span>
                </div>
            @endforeach
        </div>

        <span class="section-head">Already stored</span>
        <div class="spec">
            @foreach ([
                ['Public estimated price', 'price', $money($record->price) ?? 'Custom'],
                ['Promotional price', 'promotional_price', $money($record->promotional_price) ?? '—'],
                ['Original price', 'original_price', $money($record->original_price) ?? '—'],
                ['Promotion label', 'promotion_label', $record->promotion_label ?: '—'],
                ['Billing type', 'billing_type', \Illuminate\Support\Str::headline($record->billing_type ?: '—')],
                ['Shown as', 'is_starting_from', $qualifier ?: 'Custom'],
            ] as [$label, $column, $value])
                <div class="row-item live">
                    <b>{{ $label }}</b>
                    <code>{{ $column }}</code>
                    <span class="why">{{ $value }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ===================================================== INTERNAL — SALES --}}
<div class="band internal">
    <div class="band-head">
        <span class="chip lock dot">Admin only</span>
        <h2>Internal sales notes</h2>
    </div>
    <div class="band-body">
        <div class="pending">
            <strong>Staff notes.</strong>
            Negotiation history and positioning, for whoever picks up the next call.
        </div>

        <div class="spec">
            @foreach ([
                ['Admin-only sales notes', 'sales_notes', 'What has worked, what to lead with.'],
                ['Negotiation notes', 'negotiation_notes', 'Concessions already given, and to whom.'],
                ['Internal notes', 'internal_notes', 'Anything else staff should read first.'],
                ['Internal scope warnings', 'scope_warnings', 'Say this out loud before signing.'],
            ] as [$label, $column, $why])
                <div class="row-item">
                    <b>{{ $label }}</b>
                    <code>{{ $column }}</code>
                    <span class="why">{{ $why }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>
