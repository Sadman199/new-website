<article class="bpr-card {{ !empty($promo['is_featured']) ? 'is-featured' : '' }}">
    <a href="{{ $promo['url'] }}" class="bpr-card__link">
        <div class="bpr-card__head">
            <span class="bpr-badge">{{ $promo['type_short'] }}</span>
            @if(!empty($promo['is_featured']))
                <span class="bpr-card__featured">Featured</span>
            @endif
            @if(!empty($promo['expiry_badge']) && in_array($promo['expiry_badge']['tone'], ['urgent', 'soon', 'expired'], true))
                @include('front.partials.expiry_badge', ['badge' => $promo['expiry_badge']])
            @elseif(!empty($promo['is_limited']))
                <span class="bpr-card__urgent">Limited time</span>
            @endif
        </div>

        <p class="bpr-card__offer">{{ $promo['offer'] }}</p>
        <h3 class="bpr-card__title">{{ \Illuminate\Support\Str::limit($promo['title'], 64) }}</h3>

        @if($promo['broker_name'])
            <div class="bpr-card__broker">
                <span class="bpr-card__broker-logo" aria-hidden="true">
                    @if($promo['broker_logo'])
                        <img src="{{ $promo['broker_logo'] }}" alt="" loading="lazy" decoding="async">
                    @else
                        <span class="bpr-card__broker-fallback">{{ strtoupper(substr($promo['broker_name'], 0, 1)) }}</span>
                    @endif
                </span>
                <span class="bpr-card__broker-name">{{ $promo['broker_name'] }}</span>
                @if($promo['broker_rating'] !== null)
                    <span class="bpr-card__rating">
                        <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        {{ number_format($promo['broker_rating'], 1) }}
                    </span>
                @endif
            </div>

            @if(!empty($promo['regulation_short']))
                <div class="bpr-card__trust">
                    @foreach($promo['regulation_short'] as $chip)
                        <span class="bpr-card__reg">{{ $chip }}</span>
                    @endforeach
                </div>
            @endif
        @endif

        @if(!empty($promo['region_note']) || !empty($promo['eligibility_teaser']))
            <div class="bpr-card__eligibility">
                @if(!empty($promo['region_note']))
                    <p class="bpr-card__region">{{ $promo['region_note'] }}</p>
                @endif
                @if(!empty($promo['eligibility_teaser']))
                    <p class="bpr-card__eligibility-text">{{ $promo['eligibility_teaser'] }}</p>
                @endif
            </div>
        @endif

        <ul class="bpr-card__facts">
            @if($promo['min_deposit'])
                <li>{{ $promo['min_deposit'] }}</li>
            @endif
            @if($promo['expiry'])
                <li @class(['bc-expiry-fact--' . ($promo['expiry_tone'] ?? '') => !empty($promo['expiry_tone'])])>{{ $promo['expiry'] }}</li>
            @endif
        </ul>

        <span class="bpr-card__cta">
            View offer
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
            </svg>
        </span>
    </a>

    @if(!empty($promo['broker_review_url']))
        <a href="{{ $promo['broker_review_url'] }}" class="bpr-card__review" aria-label="Read {{ $promo['broker_name'] }} review">
            Read review
        </a>
    @endif
</article>
