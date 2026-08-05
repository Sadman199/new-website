@php
    $regulators = implode(',', $broker['regulator_slugs'] ?? []);
@endphp
<li class="rbi-card"
    data-rbi-card
    data-rbi-name="{{ $broker['name'] }}"
    data-rbi-regulators="{{ $regulators }}"
    data-rbi-tier="{{ $broker['regulatory_tier_key'] }}">
    <div class="rbi-card__head">
        <a href="{{ $broker['review_url'] }}" class="rbi-card__logo" aria-hidden="true" tabindex="-1">
            @if($broker['logo'])
                <img src="{{ $broker['logo'] }}" alt="{{ $broker['name'] }}">
            @else
                <span class="rbi-card__logo-fallback">{{ strtoupper(substr($broker['name'], 0, 1)) }}</span>
            @endif
        </a>
        <div class="rbi-card__identity">
            <a href="{{ $broker['review_url'] }}" class="rbi-card__name">{{ $broker['name'] }}</a>
            @if($broker['rating'] !== null)
                <div class="rbi-card__rating-row">
                    <span>{{ number_format($broker['rating'], 1) }}</span>
                    <span>/5</span>
                </div>
            @endif
        </div>
    </div>

    <div class="rbi-card__body">
        @if($broker['top_feature'])
            <p class="rbi-card__feature">{{ $broker['top_feature'] }}</p>
        @endif

        <div class="rbi-card__meta">
            <div class="rbi-card__meta-row">
                <span class="rbi-card__meta-label">Regulation</span>
                <span class="rbi-card__meta-value">{{ $broker['regulation_summary'] }}</span>
            </div>
            <div class="rbi-card__meta-row">
                <span class="rbi-card__meta-label">Regulatory tier</span>
                <span class="rbi-card__meta-value">{{ $broker['regulatory_tier'] }}</span>
            </div>
            @if($broker['spreads'])
                <div class="rbi-card__meta-row">
                    <span class="rbi-card__meta-label">Spreads</span>
                    <span class="rbi-card__meta-value">{{ $broker['spreads'] }}</span>
                </div>
            @endif
            @if($broker['minimum_deposit'])
                <div class="rbi-card__meta-row">
                    <span class="rbi-card__meta-label">Min. deposit</span>
                    <span class="rbi-card__meta-value">{{ $broker['minimum_deposit'] }}</span>
                </div>
            @endif
            <div class="rbi-card__meta-row">
                <span class="rbi-card__meta-label">Investor protection</span>
                <span class="rbi-card__meta-value">{{ $broker['investor_protection'] }}</span>
            </div>
        </div>

        <div class="rbi-card__actions">
            @if($broker['visit_url'])
                <a href="{{ $broker['visit_url'] }}"
                   class="rbi-btn-visit"
                   target="_blank"
                   rel="noopener noreferrer nofollow">
                    Visit broker
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                    </svg>
                </a>
            @endif
            <a href="{{ $broker['review_url'] }}" class="rbi-link-review">Read review</a>
        </div>
    </div>
</li>
