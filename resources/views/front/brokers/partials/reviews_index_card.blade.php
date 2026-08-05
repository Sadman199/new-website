@php
    $markets = implode(',', $broker['markets'] ?? []);
@endphp
<li class="bri-card"
    data-bri-card
    data-bri-name="{{ $broker['name'] }}"
    data-bri-markets="{{ $markets }}">
    <div class="bri-card__top">
        <div class="bri-card__head">
            <a href="{{ $broker['review_url'] }}" class="bri-card__logo" aria-hidden="true" tabindex="-1">
                @if($broker['logo'])
                    <img src="{{ $broker['logo'] }}" alt="{{ $broker['name'] }}">
                @else
                    <span class="bri-card__logo-fallback">{{ strtoupper(substr($broker['name'], 0, 1)) }}</span>
                @endif
            </a>
            <div class="bri-card__identity">
                <a href="{{ $broker['review_url'] }}" class="bri-card__name">{{ $broker['name'] }}</a>
                <div class="bri-card__rating-row">
                    <svg class="bri-card__star" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    @if($broker['rating'] !== null)
                        <span class="bri-card__rating-value">{{ number_format($broker['rating'], 1) }}</span>
                        <span class="bri-card__rating-max">/5</span>
                    @endif
                    @if($broker['is_award_winner'])
                        <a href="{{ route('awards.index') }}" class="bri-card__award">{{ $broker['award_label'] }}</a>
                    @endif
                </div>
            </div>
        </div>

        <div class="bri-card__social">
            <svg class="bri-card__social-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
            </svg>
            <span class="bri-card__social-count">{{ number_format($broker['popularity_count']) }}</span>
            people chose this broker
        </div>

        <div class="bri-card__metrics">
            <div class="bri-metric">
                <span class="bri-metric__label">Fee level:</span>
                <span class="bri-metric__value">
                    {{ $broker['fee_level'] }}
                    <span class="bri-metric__score">{{ number_format($broker['fee_score'], 1) }}</span>
                    <span class="bri-metric__score-muted">/5</span>
                </span>
            </div>
            <div class="bri-metric">
                <span class="bri-metric__label">Inactivity fee:</span>
                <span class="bri-metric__value">{{ $broker['inactivity_fee'] }}</span>
            </div>
            <div class="bri-metric">
                <span class="bri-metric__label">Investor protection:</span>
                <span class="bri-metric__value">{{ $broker['investor_protection'] }}</span>
            </div>
            <div class="bri-metric">
                <span class="bri-metric__label">Mobile platform:</span>
                <span class="bri-metric__value">
                    {{ $broker['mobile_platform'] }}
                    @if($broker['mobile_platform'] === 'Yes')
                        <span class="bri-metric__score">{{ number_format($broker['platform_score'], 1) }}</span>
                        <span class="bri-metric__score-muted">/5</span>
                    @endif
                </span>
            </div>
        </div>
    </div>

    <div class="bri-card__actions">
        @if($broker['visit_url'])
            <a href="{{ $broker['visit_url'] }}"
               class="bri-btn-visit"
               target="_blank"
               rel="noopener noreferrer nofollow">
                Visit broker
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                </svg>
            </a>
        @endif
        <div class="bri-card__links">
            <a href="{{ $broker['review_url'] }}" class="bri-link-review">Read review</a>
        </div>
        @if($broker['risk_disclaimer'])
            <p class="bri-card__risk">{{ $broker['risk_disclaimer'] }}</p>
        @endif
    </div>
</li>
