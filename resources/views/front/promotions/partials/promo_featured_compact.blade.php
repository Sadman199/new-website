<article class="bpr-spotlight">
    <a href="{{ $promo['url'] }}" class="bpr-spotlight__link">
        <div class="bpr-spotlight__top">
            <span class="bpr-badge">{{ $promo['type_short'] }}</span>
            @if(!empty($promo['is_featured']))
                <span class="bpr-spotlight__tag">Featured</span>
            @endif
            @if(!empty($promo['expiry_badge']) && in_array($promo['expiry_badge']['tone'], ['urgent', 'soon', 'expired'], true))
                @include('front.partials.expiry_badge', ['badge' => $promo['expiry_badge']])
            @endif
        </div>

        <p class="bpr-spotlight__offer">{{ $promo['offer'] }}</p>
        <h3 class="bpr-spotlight__title">{{ \Illuminate\Support\Str::limit($promo['title'], 56) }}</h3>

        @if($promo['broker_name'])
            <div class="bpr-spotlight__broker">
                <span class="bpr-spotlight__logo" aria-hidden="true">
                    @if($promo['broker_logo'])
                        <img src="{{ $promo['broker_logo'] }}" alt="">
                    @else
                        <span>{{ strtoupper(substr($promo['broker_name'], 0, 1)) }}</span>
                    @endif
                </span>
                <span class="bpr-spotlight__broker-name">{{ $promo['broker_name'] }}</span>
                @if($promo['broker_rating'] !== null)
                    <span class="bpr-spotlight__rating">{{ number_format($promo['broker_rating'], 1) }}</span>
                @endif
            </div>
        @endif

        <ul class="bpr-spotlight__facts">
            @if($promo['min_deposit'])
                <li>{{ $promo['min_deposit'] }}</li>
            @endif
            @if($promo['expiry'])
                <li>{{ $promo['expiry'] }}</li>
            @endif
        </ul>
    </a>
</article>
