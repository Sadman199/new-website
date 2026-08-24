@php
    $facts = collect([
        ['label' => 'Min. deposit', 'value' => $promo['min_deposit'] ?? null],
        ['label' => 'Max credit', 'value' => $promo['max_credit'] ?? null],
        ['label' => 'Requirement', 'value' => $promo['requirement'] ?? null],
    ])->filter(fn ($fact) => filled($fact['value']))->take(2)->values();

    $regulators = collect($promo['regulation_short'] ?? [])->take(2)->implode(' · ');
    $rating = $promo['broker_rating'] ?? null;
@endphp

<article class="bpr-card {{ !empty($promo['is_featured']) ? 'is-featured' : '' }}">
    <header class="bpr-card__brand">
        <a href="{{ $promo['url'] }}" class="bpr-card__logo" tabindex="-1" aria-hidden="true">
            @if(!empty($promo['broker_logo']))
                <img src="{{ $promo['broker_logo'] }}"
                     alt=""
                     loading="lazy"
                     decoding="async"
                     width="44"
                     height="44">
            @else
                <span class="bpr-card__logo-initial">{{ strtoupper(substr((string) $promo['broker_name'], 0, 1)) }}</span>
            @endif
        </a>

        <div class="bpr-card__brand-text">
            <span class="bpr-card__broker">{{ $promo['broker_name'] }}</span>
            @if($regulators)
                <span class="bpr-card__regulators">{{ $regulators }}</span>
            @endif
        </div>

        @if($rating !== null)
            <span class="bpr-card__score" aria-label="Broker rated {{ number_format($rating, 1) }} out of 5">
                <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                {{ number_format($rating, 1) }}
            </span>
        @endif
    </header>

    <div class="bpr-card__body">
        <div class="bpr-card__tags">
            <span class="bpr-card__type">{{ $promo['type_short'] }}</span>
            @if(!empty($promo['is_featured']))
                <span class="bpr-card__flag">Editor’s pick</span>
            @endif
        </div>

        <p class="bpr-card__offer">{{ $promo['offer'] }}</p>

        <h3 class="bpr-card__title">
            <a href="{{ $promo['url'] }}">{{ \Illuminate\Support\Str::limit($promo['title'], 72) }}</a>
        </h3>

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
    </div>

    <footer class="bpr-card__foot">
        <a href="{{ $promo['url'] }}" class="bpr-card__cta">
            View offer
            <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
            </svg>
        </a>

        @if(!empty($promo['expiry']))
            <span class="bpr-card__expiry">{{ $promo['expiry'] }}</span>
        @endif
    </footer>
</article>
