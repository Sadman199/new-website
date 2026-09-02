@php
    $rank = $rank ?? null;
    $isRegulated = $broker['is_regulated'] ?? false;
    $isAwardWinner = $broker['is_award_winner'] ?? false;
    $rating = $broker['rating'] ?? null;
    $tags = implode(',', $broker['tags'] ?? []);
@endphp

<div class="tbk-pick-wrap"
     data-tbk-card
     data-tbk-name="{{ $broker['name'] }}"
     data-tbk-tags="{{ $tags }}"
     data-tbk-sort-rating="{{ $broker['sort_rating'] ?? 0 }}"
     data-tbk-sort-low-cost="{{ $broker['sort_low_cost'] ?? 0 }}">
    <article class="br-compare-card tbk-compare-card" data-tbk-card-inner>
        @if($rank)
            <span class="tbk-compare-card__rank" data-tbk-rank>#{{ $rank }}</span>
        @endif

        <a href="{{ $broker['review_url'] }}" class="br-compare-card__logo" tabindex="-1" aria-hidden="true">
            @if($broker['logo'] ?? null)
                <img src="{{ $broker['logo'] }}" alt="" loading="lazy" decoding="async">
            @else
                <span class="br-compare-card__fallback">{{ strtoupper(substr($broker['name'], 0, 1)) }}</span>
            @endif
        </a>

        <a href="{{ $broker['review_url'] }}" class="br-compare-card__name">{{ $broker['name'] }}</a>

        <p class="br-compare-card__status br-compare-card__status--{{ $isRegulated ? 'safe' : 'risk' }}">
            {{ $isRegulated ? 'Regulated' : ($isAwardWinner ? 'Featured' : 'Unregulated') }}
        </p>

        @if($rating !== null)
            <p class="br-compare-card__score">{{ number_format($rating, 1) }}</p>
        @endif

        <div class="tbk-compare-card__actions">
            <a href="{{ $broker['review_url'] }}" class="tbk-compare-card__action">Read review</a>
            @if($broker['visit_url'] ?? null)
                <span class="tbk-compare-card__action-dot" aria-hidden="true">&middot;</span>
                <a href="{{ $broker['visit_url'] }}" class="tbk-compare-card__action tbk-compare-card__action--visit" target="_blank" rel="noopener noreferrer nofollow">Visit</a>
            @endif
        </div>
    </article>
</div>
