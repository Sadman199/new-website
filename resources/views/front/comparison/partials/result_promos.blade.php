@php
    $leftPromo = $comparison['promotions']['broker1'] ?? null;
    $rightPromo = $comparison['promotions']['broker2'] ?? null;
@endphp

@if($leftPromo || $rightPromo)
<section class="bc-result-promos" id="bc-result-promos" aria-label="Active promotions">
    <div class="container">
        <header class="bc-result-promos__head">
            <h2 class="bc-result-promos__title">Active promotions</h2>
            <p class="bc-result-promos__sub">Live bonus offers from our promotions database.</p>
        </header>

        <div class="bc-result-promos__grid">
            @foreach(['broker1' => $leftPromo, 'broker2' => $rightPromo] as $side => $promo)
                @if($promo)
                    <a href="{{ $promo['url'] }}" class="bc-result-promo-card">
                        <span class="bc-result-promo-card__badge">{{ $promo['type_short'] }}</span>
                        <strong class="bc-result-promo-card__offer">{{ $promo['offer'] }}</strong>
                        <span class="bc-result-promo-card__broker">{{ $comparison[$side]['name'] }}</span>
                        @if($promo['expiry'])
                            <span class="bc-result-promo-card__expiry {{ !empty($promo['is_urgent']) ? 'is-urgent' : '' }}">{{ $promo['expiry'] }}</span>
                        @endif
                        <span class="bc-result-promo-card__cta">View offer →</span>
                    </a>
                @else
                    <div class="bc-result-promo-card bc-result-promo-card--empty">
                        <span class="bc-result-promo-card__broker">{{ $comparison[$side]['name'] }}</span>
                        <p>No active promotion listed right now.</p>
                        <a href="{{ route('promotions.index') }}" class="bc-result-promo-card__cta">Browse all promos</a>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</section>
@endif
