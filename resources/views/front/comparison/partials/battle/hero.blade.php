@php
    $left = $battle['broker1'];
    $right = $battle['broker2'];
    $winner = $battle['winner'] ?? null;
    $leftRating = \App\Support\BrokerRating::outOfFive($left['rating'] ?? null);
    $rightRating = \App\Support\BrokerRating::outOfFive($right['rating'] ?? null);
    $leftRatingPct = \App\Support\BrokerRating::percent($left['rating'] ?? null);
    $rightRatingPct = \App\Support\BrokerRating::percent($right['rating'] ?? null);
@endphp

<header class="bc-compare-hero bc-result-hero bc-battle-hero">
    <div class="container">
        <nav class="bc-compare-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span aria-hidden="true">/</span>
            <a href="{{ route('broker.comparison') }}">Compare brokers</a>
            <span aria-hidden="true">/</span>
            <span>Broker Battle</span>
        </nav>

        <p class="bc-compare-hero__eyebrow">Broker Battle</p>
        <h1 class="bc-compare-hero__title">
            <span class="bc-result-hero__name">{{ $left['name'] }}</span>
            <span class="bc-result-hero__vs">VS</span>
            <span class="bc-result-hero__name">{{ $right['name'] }}</span>
        </h1>
        <p class="bc-compare-hero__sub">
            A head-to-head matchup from the BrokersCourt database — regulation, trust, trading conditions, platforms, and service quality decided dynamically.
        </p>

        <div class="bc-result-duel">
            <article class="bc-result-duel__card {{ ($winner['broker'] ?? '') === 'broker1' ? 'is-winner' : '' }}">
                @if(($winner['broker'] ?? '') === 'broker1')
                    <span class="bc-result-duel__badge">Battle winner</span>
                @endif
                <div class="bc-result-duel__logo">
                    @if($left['logo'])
                        <img src="{{ $left['logo'] }}" alt="{{ $left['name'] }}">
                    @else
                        <span>{{ strtoupper(substr($left['name'], 0, 1)) }}</span>
                    @endif
                </div>
                <h2 class="bc-result-duel__name">{{ $left['name'] }}</h2>
                <div class="bc-result-duel__rating"
                     style="--rating-pct: {{ $leftRatingPct }}%"
                     aria-label="{{ $left['name'] }} rating {{ $leftRating !== null ? number_format($leftRating, 1) . ' out of 5' : 'not rated' }}">
                    <strong>{{ $leftRating !== null ? number_format($leftRating, 1) : '—' }}</strong>
                    <span>/5</span>
                </div>
                <dl class="bc-result-duel__meta">
                    <div>
                        <dt>Trust score</dt>
                        <dd>{{ $left['trust_score'] }}</dd>
                    </div>
                    <div>
                        <dt>Regulation</dt>
                        <dd>{{ $left['regulatory_tier'] !== '—' ? $left['regulatory_tier'] : $left['broker_type'] }}</dd>
                    </div>
                    <div>
                        <dt>Safety</dt>
                        <dd>{{ ($left['safety']['overall_score'] ?? '—') }}/100</dd>
                    </div>
                </dl>
                <div class="bc-result-duel__actions">
                    <a href="{{ $left['review_url'] }}" class="bc-compare-btn bc-compare-btn--ghost bc-result-duel__btn">View broker</a>
                    @if($left['visit_url'])
                        <a href="{{ $left['visit_url'] }}" class="bc-compare-btn bc-compare-btn--primary bc-result-duel__btn" target="_blank" rel="nofollow noopener">Visit site</a>
                    @endif
                </div>
            </article>

            <div class="bc-result-duel__divider" aria-hidden="true">
                <span>VS</span>
            </div>

            <article class="bc-result-duel__card {{ ($winner['broker'] ?? '') === 'broker2' ? 'is-winner' : '' }}">
                @if(($winner['broker'] ?? '') === 'broker2')
                    <span class="bc-result-duel__badge">Battle winner</span>
                @endif
                <div class="bc-result-duel__logo">
                    @if($right['logo'])
                        <img src="{{ $right['logo'] }}" alt="{{ $right['name'] }}">
                    @else
                        <span>{{ strtoupper(substr($right['name'], 0, 1)) }}</span>
                    @endif
                </div>
                <h2 class="bc-result-duel__name">{{ $right['name'] }}</h2>
                <div class="bc-result-duel__rating"
                     style="--rating-pct: {{ $rightRatingPct }}%"
                     aria-label="{{ $right['name'] }} rating {{ $rightRating !== null ? number_format($rightRating, 1) . ' out of 5' : 'not rated' }}">
                    <strong>{{ $rightRating !== null ? number_format($rightRating, 1) : '—' }}</strong>
                    <span>/5</span>
                </div>
                <dl class="bc-result-duel__meta">
                    <div>
                        <dt>Trust score</dt>
                        <dd>{{ $right['trust_score'] }}</dd>
                    </div>
                    <div>
                        <dt>Regulation</dt>
                        <dd>{{ $right['regulatory_tier'] !== '—' ? $right['regulatory_tier'] : $right['broker_type'] }}</dd>
                    </div>
                    <div>
                        <dt>Safety</dt>
                        <dd>{{ ($right['safety']['overall_score'] ?? '—') }}/100</dd>
                    </div>
                </dl>
                <div class="bc-result-duel__actions">
                    <a href="{{ $right['review_url'] }}" class="bc-compare-btn bc-compare-btn--ghost bc-result-duel__btn">View broker</a>
                    @if($right['visit_url'])
                        <a href="{{ $right['visit_url'] }}" class="bc-compare-btn bc-compare-btn--primary bc-result-duel__btn" target="_blank" rel="nofollow noopener">Visit site</a>
                    @endif
                </div>
            </article>
        </div>
    </div>
</header>
