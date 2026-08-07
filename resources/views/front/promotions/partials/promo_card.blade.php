<article class="bpr-card bpr-card--compact bpr-card--{{ $promo['type_tone'] }} {{ !empty($promo['is_featured']) ? 'is-featured' : '' }}">
    <a href="{{ $promo['url'] }}" class="bpr-card__link">
        <div class="bpr-card__thumb" aria-hidden="true">
            @if($promo['broker_logo'])
                <img src="{{ $promo['broker_logo'] }}" alt="">
            @elseif($promo['feature_image'])
                <img src="{{ $promo['feature_image'] }}" alt="" class="bpr-card__thumb-photo">
            @else
                <span class="bpr-card__thumb-fallback">
                    {{ strtoupper(substr($promo['broker_name'] ?? $promo['title'], 0, 1)) }}
                </span>
            @endif
        </div>

        <div class="bpr-card__content">
            <div class="bpr-card__head">
                <span class="bpr-badge bpr-badge--{{ $promo['type_tone'] }}">{{ $promo['type_short'] }}</span>
                @if(!empty($promo['is_featured']))
                    <span class="bpr-card__featured">Featured</span>
                @endif
                <span class="bpr-card__offer">{{ $promo['offer'] }}</span>
            </div>

            <h3 class="bpr-card__title">{{ \Illuminate\Support\Str::limit($promo['title'], 58) }}</h3>

            <div class="bpr-card__meta">
                @if($promo['broker_name'])
                    <span class="bpr-card__broker">{{ $promo['broker_name'] }}</span>
                @endif
                @if($promo['broker_rating'] !== null)
                    <span class="bpr-card__rating">{{ number_format($promo['broker_rating'], 1) }}</span>
                @endif
                @if($promo['min_deposit'])
                    <span>{{ $promo['min_deposit'] }}</span>
                @endif
                @if($promo['expiry'])
                    <span class="{{ $promo['is_urgent'] ? 'is-urgent' : '' }}">{{ $promo['expiry'] }}</span>
                @endif
            </div>
        </div>

        <span class="bpr-card__arrow" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </span>
    </a>
</article>
