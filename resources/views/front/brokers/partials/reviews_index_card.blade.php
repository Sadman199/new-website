@php
    $rating = \App\Support\BrokerRating::outOfFive($broker['rating'] ?? null);
    $ratingPercent = \App\Support\BrokerRating::percent($broker['rating'] ?? null);
    $meta = array_values(array_filter([
        $broker['country'] ?? null,
        $broker['regulation_summary'] ?? null,
    ]));
    $stats = array_values(array_filter([
        ['label' => 'Min. deposit', 'value' => $broker['minimum_deposit'] ?? null],
        ['label' => 'Leverage', 'value' => $broker['leverage'] ?? null],
        ['label' => 'Spreads', 'value' => $broker['spreads'] ?? null],
    ], fn (array $stat) => filled($stat['value'])));
@endphp

<li class="bri-review-card-item"
    data-bri-card
    data-bri-name="{{ $broker['name'] }}"
    data-bri-markets="{{ implode(',', $broker['markets'] ?? []) }}">
    <article class="bc-pick-card bri-review-card">
        <div class="bc-pick-card__top">
            @if($broker['is_regulated'] ?? false)
                <span class="bc-pick-card__status bc-regulated-tag">Regulated</span>
            @elseif($broker['is_award_winner'] ?? false)
                <span class="bc-pick-card__status bc-pick-card__status--featured">Featured</span>
            @endif

            @if($rating !== null)
                <div class="bc-pick-card__score" aria-label="Rating {{ $rating }} out of 5">
                    <div class="bc-pick-card__score-ring" style="--score: {{ $ratingPercent }}">
                        <svg viewBox="0 0 36 36" aria-hidden="true">
                            <circle class="bc-pick-card__score-track" cx="18" cy="18" r="15.5"/>
                            <circle class="bc-pick-card__score-fill" cx="18" cy="18" r="15.5"/>
                        </svg>
                        <strong>{{ number_format($rating, 1) }}</strong>
                    </div>
                </div>
            @endif
        </div>

        <div class="bc-pick-card__brand">
            <a href="{{ $broker['review_url'] }}" class="bc-pick-card__logo-wrap" tabindex="-1" aria-hidden="true">
                <div class="bc-pick-card__logo">
                    @if($broker['logo'] ?? null)
                        <img src="{{ $broker['logo'] }}" alt="" loading="lazy" decoding="async">
                    @else
                        <span class="bc-pick-card__logo-fallback">{{ strtoupper(substr($broker['name'], 0, 1)) }}</span>
                    @endif
                </div>
            </a>

            <div class="bc-pick-card__identity">
                <a href="{{ $broker['review_url'] }}" class="bc-pick-card__name">{{ $broker['name'] }}</a>
                @if($meta !== [])
                    <p class="bc-pick-card__meta">
                        @foreach($meta as $value)
                            @if(! $loop->first)<span class="bc-pick-card__dot" aria-hidden="true">·</span>@endif
                            <span>{{ $value }}</span>
                        @endforeach
                    </p>
                @endif
            </div>
        </div>

        @if($stats !== [])
            <dl class="bc-pick-card__stats">
                @foreach($stats as $stat)
                    <div class="bc-pick-card__stat">
                        <dt>{{ $stat['label'] }}</dt>
                        <dd>{{ \Illuminate\Support\Str::limit($stat['value'], 14) }}</dd>
                    </div>
                @endforeach
            </dl>
        @endif

        <div class="bc-pick-card__actions">
            <a href="{{ $broker['review_url'] }}" class="bc-pick-card__btn bc-pick-card__btn--review">
                Read review
                <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/></svg>
            </a>
            @if($broker['visit_url'] ?? null)
                <a href="{{ $broker['visit_url'] }}" class="bc-pick-card__btn bc-pick-card__btn--visit" target="_blank" rel="noopener noreferrer nofollow">Visit</a>
            @endif
        </div>
    </article>
</li>
