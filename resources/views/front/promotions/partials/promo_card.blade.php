@php
    $facts = collect([
        ['label' => 'Min. deposit', 'value' => $promo['min_deposit'] ?? null],
        ['label' => 'Max credit', 'value' => $promo['max_credit'] ?? null],
        ['label' => 'Requirement', 'value' => $promo['requirement'] ?? null],
        ['label' => 'Eligible', 'value' => $promo['eligible_clients'] ?? null],
    ])->filter(fn ($fact) => filled($fact['value']))->take(3)->values();

    $regulators = collect($promo['regulation_short'] ?? [])->take(2)->implode(' · ');
@endphp

<article class="bpr-card {{ !empty($promo['is_featured']) ? 'is-featured' : '' }}">
    <header class="bpr-card__brand">
        <span class="bpr-card__logo">
            @if(!empty($promo['broker_logo']))
                <img src="{{ $promo['broker_logo'] }}"
                     alt="{{ $promo['broker_name'] }} logo"
                     loading="lazy"
                     decoding="async"
                     width="44"
                     height="44">
            @else
                <span class="bpr-card__logo-initial" aria-hidden="true">{{ strtoupper(substr((string) $promo['broker_name'], 0, 1)) }}</span>
            @endif
        </span>

        <span class="bpr-card__brand-text">
            <span class="bpr-card__broker">{{ $promo['broker_name'] }}</span>
            @if($regulators)
                <span class="bpr-card__regulators">{{ $regulators }}</span>
            @elseif(!empty($promo['region_note']))
                <span class="bpr-card__regulators">{{ $promo['region_note'] }}</span>
            @endif
        </span>

        @if(($promo['broker_rating'] ?? null) !== null)
            <span class="bpr-card__score" aria-label="Broker rated {{ number_format($promo['broker_rating'], 1) }} out of 5">
                <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                {{ number_format($promo['broker_rating'], 1) }}
            </span>
        @endif
    </header>

    <div class="bpr-card__body">
        <div class="bpr-card__tags">
            <span class="bpr-card__type">{{ $promo['type_short'] }}</span>
            @if(!empty($promo['is_featured']))
                <span class="bpr-card__flag">Editor’s pick</span>
            @endif
            @if(!empty($promo['expiry_badge']) && in_array($promo['expiry_badge']['tone'], ['urgent', 'soon', 'expired'], true))
                @include('front.partials.expiry_badge', ['badge' => $promo['expiry_badge']])
            @elseif(!empty($promo['is_limited']))
                <span class="bpr-card__urgent">Limited time</span>
            @endif
        </div>

        <p class="bpr-card__offer">{{ $promo['offer'] }}</p>

        <h3 class="bpr-card__title">
            <a href="{{ $promo['url'] }}">{{ \Illuminate\Support\Str::limit($promo['title'], 64) }}</a>
        </h3>
    </div>

    @if($facts->isNotEmpty())
        <dl class="bpr-card__facts">
            @foreach($facts as $fact)
                <div>
                    <dt>{{ $fact['label'] }}</dt>
                    <dd>{{ $fact['value'] }}</dd>
                </div>
            @endforeach
        </dl>
    @endif

    <footer class="bpr-card__foot">
        <span class="bpr-card__cta">
            View offer
            <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.638L10.23 5.29a.75.75 0 1 1 1.04-1.08l5.5 5.25a.75.75 0 0 1 0 1.08l-5.5 5.25a.75.75 0 0 1-1.04-1.08l4.158-3.96H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd"/>
            </svg>
        </span>

        @if(!empty($promo['expiry']))
            <span class="bpr-card__expiry @if(!empty($promo['expiry_tone'])) bc-expiry-fact--{{ $promo['expiry_tone'] }} @endif">{{ $promo['expiry'] }}</span>
        @endif

        @if(!empty($promo['broker_review_url']))
            <a href="{{ $promo['broker_review_url'] }}" class="bpr-card__review">Broker review</a>
        @endif
    </footer>
</article>
