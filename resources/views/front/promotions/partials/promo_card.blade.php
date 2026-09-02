@php
    $facts = collect([
        ['label' => 'Min. deposit', 'value' => $promo['min_deposit'] ?? null],
        ['label' => 'Max credit', 'value' => $promo['max_credit'] ?? null],
        ['label' => 'Eligible', 'value' => $promo['eligible_clients'] ?? null],
        ['label' => 'Requirement', 'value' => $promo['requirement'] ?? null],
    ])->filter(fn ($fact) => filled($fact['value']))->take(3)->values();

    $regulators = collect($promo['regulation_short'] ?? [])->take(2)->implode(' · ');
    $rating = $promo['broker_rating'] ?? null;
    $typeTone = $promo['type_tone'] ?? 'deposit';
    $expiryBadge = $promo['expiry_badge'] ?? null;
    $viewUrl = $promo['detail_url'] ?? $promo['url'] ?? null;
@endphp

<article class="bpr-card bpr-card--{{ $typeTone }} {{ !empty($promo['is_featured']) ? 'is-featured' : '' }} {{ !empty($promo['is_urgent']) ? 'is-urgent' : '' }}">
    @if(!empty($promo['feature_image']))
        <a href="{{ $viewUrl }}" class="bpr-card__media" tabindex="-1" aria-hidden="true">
            <img src="{{ $promo['feature_image'] }}"
                 alt=""
                 loading="lazy"
                 decoding="async"
                 width="400"
                 height="160">
        </a>
    @endif

    <div class="bpr-card__accent" aria-hidden="true"></div>

    <header class="bpr-card__top">
        <div class="bpr-card__tags">
            <span class="bpr-card__type">{{ $promo['type_short'] }}</span>
            @if(!empty($promo['is_featured']))
                <span class="bpr-card__flag">Featured</span>
            @endif
            @if(!empty($promo['promotion_status_label']))
                <span class="bpr-card__status bpr-card__status--{{ $promo['promotion_status'] ?? 'ongoing' }}">
                    {{ $promo['promotion_status_label'] }}
                </span>
            @endif
        </div>

        @if($expiryBadge)
            <span class="bc-expiry-badge bc-expiry-badge--pill bc-expiry-badge--{{ $expiryBadge['tone'] ?? 'normal' }}">
                {{ $expiryBadge['short'] ?? $expiryBadge['label'] }}
            </span>
        @endif
    </header>

    <div class="bpr-card__brand">
        <a href="{{ $viewUrl }}" class="bpr-card__logo" tabindex="-1" aria-hidden="true">
            @if(!empty($promo['broker_logo']))
                <img src="{{ $promo['broker_logo'] }}"
                     alt=""
                     loading="lazy"
                     decoding="async"
                     width="48"
                     height="48">
            @else
                <span class="bpr-card__logo-initial">{{ strtoupper(substr((string) ($promo['broker_name'] ?? $promo['title']), 0, 1)) }}</span>
            @endif
        </a>

        <div class="bpr-card__brand-text">
            @if(!empty($promo['broker_name']))
                <span class="bpr-card__broker">{{ $promo['broker_name'] }}</span>
            @endif
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
    </div>

    <div class="bpr-card__body">
        @if(!empty($promo['offer']))
            <p class="bpr-card__offer">{{ $promo['offer'] }}</p>
        @endif

        <h3 class="bpr-card__title">
            @if($viewUrl)
                <a href="{{ $viewUrl }}">{{ \Illuminate\Support\Str::limit($promo['title'], 72) }}</a>
            @else
                {{ \Illuminate\Support\Str::limit($promo['title'], 72) }}
            @endif
        </h3>

        @if(!empty($promo['description']))
            <p class="bpr-card__desc">{{ $promo['description'] }}</p>
        @endif

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

        @if(!empty($promo['region_note']))
            <p class="bpr-card__region">{{ $promo['region_note'] }}</p>
        @endif
    </div>

    <footer class="bpr-card__foot">
        <div class="bpr-card__actions">
            @if($viewUrl)
                <a href="{{ $viewUrl }}" class="bpr-card__cta bpr-card__cta--view">View bonus</a>
            @endif
            @if(!empty($promo['affiliate_link']))
                <a href="{{ $promo['affiliate_link'] }}"
                   class="bpr-card__cta bpr-card__cta--claim"
                   target="_blank"
                   rel="noopener noreferrer nofollow">Claim bonus</a>
            @endif
        </div>

        <div class="bpr-card__foot-meta">
            @if(!empty($promo['broker_review_url']))
                <a href="{{ $promo['broker_review_url'] }}" class="bpr-card__review">Broker review</a>
            @endif
            @if(!empty($promo['expiry']))
                <span class="bpr-card__expiry">{{ $promo['expiry'] }}</span>
            @endif
        </div>
    </footer>
</article>
