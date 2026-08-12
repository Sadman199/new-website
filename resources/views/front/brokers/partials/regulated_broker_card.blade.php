@php
    $regulators = implode(',', $broker['regulator_slugs'] ?? []);
@endphp
<li class="brb-card"
    data-rbi-card
    data-rbi-name="{{ $broker['name'] }}"
    data-rbi-regulators="{{ $regulators }}"
    data-rbi-tier="{{ $broker['regulatory_tier_key'] }}">
    <div class="brb-card__head">
        <a href="{{ $broker['review_url'] }}" class="brb-card__logo" aria-hidden="true" tabindex="-1">
            @if($broker['logo'])
                <img src="{{ $broker['logo'] }}" alt="{{ $broker['name'] }}" loading="lazy" decoding="async">
            @else
                <span class="brb-card__logo-fallback">{{ strtoupper(substr($broker['name'], 0, 1)) }}</span>
            @endif
        </a>
        <div class="brb-card__identity">
            <a href="{{ $broker['review_url'] }}" class="brb-card__name">{{ $broker['name'] }}</a>
            @if($broker['rating'] !== null)
                <div class="brb-card__rating-row">
                    <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <span>{{ number_format($broker['rating'], 1) }}</span>
                    <span class="brb-card__rating-max">/5</span>
                </div>
            @endif
            @if($broker['regulatory_tier'] !== '—')
                <span class="brb-card__tier">{{ $broker['regulatory_tier'] }}</span>
            @endif
        </div>
    </div>

    <div class="brb-card__body">
        @if($broker['top_feature'])
            <p class="brb-card__feature">{{ $broker['top_feature'] }}</p>
        @endif

        <dl class="brb-card__meta">
            <div class="brb-card__meta-row">
                <dt>Regulation</dt>
                <dd>{{ $broker['regulation_summary'] }}</dd>
            </div>
            @if($broker['spreads'])
                <div class="brb-card__meta-row">
                    <dt>Spreads</dt>
                    <dd>{{ $broker['spreads'] }}</dd>
                </div>
            @endif
            @if($broker['minimum_deposit'])
                <div class="brb-card__meta-row">
                    <dt>Min. deposit</dt>
                    <dd>{{ $broker['minimum_deposit'] }}</dd>
                </div>
            @endif
            <div class="brb-card__meta-row">
                <dt>Investor protection</dt>
                <dd>{{ $broker['investor_protection'] }}</dd>
            </div>
        </dl>

        <div class="brb-card__actions">
            @if($broker['visit_url'])
                <a href="{{ $broker['visit_url'] }}"
                   class="brb-btn-visit"
                   target="_blank"
                   rel="noopener noreferrer nofollow">
                    Visit broker
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                    </svg>
                </a>
            @endif
            <a href="{{ $broker['review_url'] }}" class="brb-link-review">Read review</a>
        </div>
    </div>
</li>
