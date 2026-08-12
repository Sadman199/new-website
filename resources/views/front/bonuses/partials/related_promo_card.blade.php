<a href="{{ $promo['url'] }}" class="bbd-promo-card {{ !empty($promo['is_featured']) ? 'is-featured' : '' }}">
    <span class="bbd-promo-card__thumb" aria-hidden="true">
        @if(!empty($promo['broker_logo']))
            <img src="{{ $promo['broker_logo'] }}" alt="">
        @elseif(!empty($promo['feature_image']))
            <img src="{{ $promo['feature_image'] }}" alt="">
        @else
            <span>{{ strtoupper(substr($promo['broker_name'] ?? $promo['title'], 0, 1)) }}</span>
        @endif
    </span>
    <span class="bbd-promo-card__body">
        <span class="bbd-promo-card__badge">{{ $promo['type_short'] }}</span>
        @if(!empty($promo['expiry_badge']) && in_array($promo['expiry_badge']['tone'], ['urgent', 'soon'], true))
            @include('front.partials.expiry_badge', ['badge' => $promo['expiry_badge']])
        @endif
        <strong class="bbd-promo-card__offer">{{ $promo['offer'] }}</strong>
        <span class="bbd-promo-card__title">{{ Str::limit($promo['title'], 56) }}</span>
        <span class="bbd-promo-card__meta">
            @if($promo['broker_name'])
                <span>{{ $promo['broker_name'] }}</span>
            @endif
            @if($promo['min_deposit'])
                <span>{{ $promo['min_deposit'] }}</span>
            @endif
            @if($promo['expiry'])
                <span @class(['bc-expiry-fact--' . ($promo['expiry_tone'] ?? '') => !empty($promo['expiry_tone'])])>{{ $promo['expiry'] }}</span>
            @endif
        </span>
    </span>
    <span class="bbd-promo-card__arrow" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
    </span>
</a>
