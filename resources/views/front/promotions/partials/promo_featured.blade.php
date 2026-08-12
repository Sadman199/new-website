<article class="bpr-featured">
    <a href="{{ $promo['url'] }}" class="bpr-featured__link">
        <div class="bpr-featured__media">
            @if($promo['feature_image'])
                <img src="{{ $promo['feature_image'] }}"
                     alt=""
                     class="bpr-featured__image"
                     loading="lazy"
                     decoding="async">
            @else
                <div class="bpr-featured__placeholder" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4H5z"/>
                    </svg>
                </div>
            @endif
            <div class="bpr-featured__overlay" aria-hidden="true"></div>
            @if($promo['is_featured'])
                <span class="bpr-featured__badge">Featured pick</span>
            @endif
        </div>

        <div class="bpr-featured__body">
            <div class="bpr-featured__meta">
                <span class="bpr-badge">{{ $promo['type_short'] }}</span>
                @if(!empty($promo['expiry_badge']) && in_array($promo['expiry_badge']['tone'], ['urgent', 'soon', 'expired'], true))
                    @include('front.partials.expiry_badge', ['badge' => $promo['expiry_badge']])
                @elseif($promo['is_limited'])
                    <span class="bpr-badge bpr-badge--urgent">Limited time</span>
                @endif
            </div>

            <p class="bpr-featured__offer">{{ $promo['offer'] }}</p>
            <h3 class="bpr-featured__title">{{ $promo['title'] }}</h3>

            @if($promo['broker_name'])
                <div class="bpr-featured__broker">
                    @if($promo['broker_logo'])
                        <span class="bpr-featured__broker-logo">
                            <img src="{{ $promo['broker_logo'] }}" alt="">
                        </span>
                    @endif
                    <span class="bpr-featured__broker-name">{{ $promo['broker_name'] }}</span>
                    @if($promo['broker_rating'] !== null)
                        <span class="bpr-featured__broker-rating">
                            <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            {{ number_format($promo['broker_rating'], 1) }}
                        </span>
                    @endif
                </div>
                @if(!empty($promo['regulation_short']))
                    <div class="bpr-card__trust bpr-card__trust--featured">
                        @foreach($promo['regulation_short'] as $chip)
                            <span class="bpr-card__reg">{{ $chip }}</span>
                        @endforeach
                    </div>
                @endif
            @endif

            @if(!empty($promo['region_note']))
                <p class="bpr-card__region">{{ $promo['region_note'] }}</p>
            @endif

            <ul class="bpr-featured__facts">
                @if($promo['min_deposit'])
                    <li>{{ $promo['min_deposit'] }}</li>
                @endif
                @if($promo['expiry'])
                    <li @class(['bc-expiry-fact--' . ($promo['expiry_tone'] ?? '') => !empty($promo['expiry_tone'])])>{{ $promo['expiry'] }}</li>
                @endif
            </ul>

            <span class="bpr-featured__cta">
                View promotion
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                </svg>
            </span>
        </div>
    </a>
</article>
