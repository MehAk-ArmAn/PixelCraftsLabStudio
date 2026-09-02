{{-- Exactly what a visitor sees for this package, so the estimate wording
     can be checked without leaving the admin. --}}
@php
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
@endphp

<div class="card">
    <h3>What the visitor sees</h3>
    <p class="sub">Live preview of the public pricing card.</p>

    <div class="pkg-preview">
        @if ($record->badge || $record->promotion_label)
            <span class="chip" style="background:var(--pcl-orange); color:#fff;">{{ $record->promotion_label ?: $record->badge }}</span>
        @endif

        <div style="margin-top:10px; font-size:17px; font-weight:700; letter-spacing:-0.02em;">{{ $record->name }}</div>

        @if ($record->short_description)
            <div style="margin-top:5px; font-size:12.5px; line-height:1.5; opacity:0.65;">{{ $record->short_description }}</div>
        @endif

        @if ($qualifier)<div class="q" style="margin-top:14px;">{{ $qualifier }}</div>@endif
        <div class="p">
            @if ($was !== null)<span class="was">{{ $money($was) }}</span>@endif
            {{ $isCustom ? 'Custom' : $money($shown) }}
            @if ($record->billing_period)<small>/ {{ $record->billing_period }}</small>@endif
        </div>

        @if ($included->isNotEmpty())
            <ul>
                @foreach ($included->take(6) as $item)<li>{{ $item->text }}</li>@endforeach
            </ul>
            @if ($included->count() > 6)
                <div class="small" style="margin-top:6px; opacity:0.5;">+ {{ $included->count() - 6 }} more</div>
            @endif
        @endif

        <div class="disc">
            Marketing prices shown are estimated starting rates. Final pricing depends on scope,
            channels, content volume, campaign requirements and production needs.
            @if ($record->media_spend_separated)
                <br>Advertising media spend is separate unless explicitly included.
            @endif
        </div>
    </div>

    <p class="small muted" style="margin:12px 0 0;">
        Nothing from the internal bands appears here, or in the public payload.
    </p>
</div>
