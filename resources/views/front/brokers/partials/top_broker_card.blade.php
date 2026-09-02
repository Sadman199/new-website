@php
    use App\Support\BrokerRating;

    $rating = BrokerRating::outOfFive($broker['rating'] ?? null);
    $tags = implode(',', $broker['tags'] ?? []);
    $highlights = $broker['highlights'] ?? [];
    $compact = $compact ?? false;
    $rank = $rank ?? null;
@endphp

@if($compact)
<article class="tbk-card-wrap tbk-card-wrap--compact"
@else
<li class="tbk-card-wrap"
@endif
    data-tbk-card
    data-tbk-name="{{ $broker['name'] }}"
    data-tbk-tags="{{ $tags }}"
    data-tbk-sort-rating="{{ $broker['sort_rating'] ?? 0 }}"
    data-tbk-sort-low-cost="{{ $broker['sort_low_cost'] ?? 0 }}">
    <article class="tbk-card">
        <div class="tbk-card__head">
            @if($rank)
                <span class="tbk-card__rank">#{{ $rank }}</span>
            @endif

            <a href="{{ $broker['review_url'] }}" class="tbk-card__logo" tabindex="-1" aria-hidden="true">
                @if($broker['logo'] ?? null)
                    <img src="{{ $broker['logo'] }}" alt="" loading="lazy" decoding="async">
                @else
                    <span class="tbk-card__logo-fallback">{{ strtoupper(substr($broker['name'], 0, 1)) }}</span>
                @endif
            </a>

            <div class="tbk-card__identity">
                <a href="{{ $broker['review_url'] }}" class="tbk-card__name">{{ $broker['name'] }}</a>
                @if($rating !== null)
                    <span class="tbk-card__rating" aria-label="Rated {{ number_format($rating, 1) }} out of 5">
                        <i class="fas fa-star" aria-hidden="true"></i>
                        {{ number_format($rating, 1) }}
                    </span>
                @endif
            </div>
        </div>

        <dl class="tbk-card__stats">
            <div>
                <dt>Spread</dt>
                <dd>{{ $broker['spreads'] ?? '—' }}</dd>
            </div>
            <div>
                <dt>Deposit</dt>
                <dd>{{ $broker['minimum_deposit'] ?? '—' }}</dd>
            </div>
            <div>
                <dt>Leverage</dt>
                <dd>{{ $broker['leverage'] ?? '—' }}</dd>
            </div>
        </dl>

        @if($highlights !== [])
            <ul class="tbk-card__tags" aria-label="Broker highlights">
                @foreach($highlights as $highlight)
                    <li>{{ $highlight }}</li>
                @endforeach
            </ul>
        @endif

        <a href="{{ $broker['review_url'] }}" class="tbk-card__cta">
            View broker
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
            </svg>
        </a>
    </article>
@if($compact)
</article>
@else
</li>
@endif
