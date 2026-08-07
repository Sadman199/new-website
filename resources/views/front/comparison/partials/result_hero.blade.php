@php
    $left = $comparison['broker1'];
    $right = $comparison['broker2'];
    $winner = $comparison['overall_winner'];
@endphp

<header class="bc-compare-hero bc-result-hero">
    <div class="bc-compare-wrap">
        <nav class="bc-compare-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span aria-hidden="true">/</span>
            <a href="{{ route('broker.comparison') }}">Compare brokers</a>
            <span aria-hidden="true">/</span>
            <span>{{ $left['name'] }} vs {{ $right['name'] }}</span>
        </nav>

        <p class="bc-compare-hero__eyebrow">Side-by-side broker analysis</p>
        <h1 class="bc-compare-hero__title">
            <span class="bc-result-hero__name">{{ $left['name'] }}</span>
            <span class="bc-result-hero__vs">vs</span>
            <span class="bc-result-hero__name">{{ $right['name'] }}</span>
        </h1>
        <p class="bc-compare-hero__sub">Live comparison from our broker database — regulation, trading costs, platforms, deposits, safety scores, and service quality.</p>

        <div class="bc-result-duel">
            <article class="bc-result-duel__card {{ ($winner['broker'] ?? '') === 'broker1' ? 'is-winner' : '' }}">
                @if(($winner['broker'] ?? '') === 'broker1')
                    <span class="bc-result-duel__badge">Editor's pick</span>
                @endif
                <div class="bc-result-duel__logo">
                    @if($left['logo'])
                        <img src="{{ $left['logo'] }}" alt="{{ $left['name'] }}">
                    @else
                        <span>{{ strtoupper(substr($left['name'], 0, 1)) }}</span>
                    @endif
                </div>
                <h2 class="bc-result-duel__name">{{ $left['name'] }}</h2>
                <p class="bc-result-duel__score">{{ $left['rating_display'] }}</p>
                <dl class="bc-result-duel__meta">
                    <div>
                        <dt>Safety</dt>
                        <dd>{{ ($left['safety']['overall_score'] ?? '—') }}/100</dd>
                    </div>
                    <div>
                        <dt>Min deposit</dt>
                        <dd>{{ $left['minimum_deposit'] }}</dd>
                    </div>
                    <div>
                        <dt>Regulation</dt>
                        <dd>{{ $left['regulatory_tier'] }}</dd>
                    </div>
                </dl>
                <div class="bc-result-duel__actions">
                    <a href="{{ $left['review_url'] }}" class="bc-compare-btn bc-compare-btn--ghost bc-result-duel__btn">Full review</a>
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
                    <span class="bc-result-duel__badge">Editor's pick</span>
                @endif
                <div class="bc-result-duel__logo">
                    @if($right['logo'])
                        <img src="{{ $right['logo'] }}" alt="{{ $right['name'] }}">
                    @else
                        <span>{{ strtoupper(substr($right['name'], 0, 1)) }}</span>
                    @endif
                </div>
                <h2 class="bc-result-duel__name">{{ $right['name'] }}</h2>
                <p class="bc-result-duel__score">{{ $right['rating_display'] }}</p>
                <dl class="bc-result-duel__meta">
                    <div>
                        <dt>Safety</dt>
                        <dd>{{ ($right['safety']['overall_score'] ?? '—') }}/100</dd>
                    </div>
                    <div>
                        <dt>Min deposit</dt>
                        <dd>{{ $right['minimum_deposit'] }}</dd>
                    </div>
                    <div>
                        <dt>Regulation</dt>
                        <dd>{{ $right['regulatory_tier'] }}</dd>
                    </div>
                </dl>
                <div class="bc-result-duel__actions">
                    <a href="{{ $right['review_url'] }}" class="bc-compare-btn bc-compare-btn--ghost bc-result-duel__btn">Full review</a>
                    @if($right['visit_url'])
                        <a href="{{ $right['visit_url'] }}" class="bc-compare-btn bc-compare-btn--primary bc-result-duel__btn" target="_blank" rel="nofollow noopener">Visit site</a>
                    @endif
                </div>
            </article>
        </div>

        @if(!empty($comparison['summary']))
            <div class="bc-result-quick">
                @foreach($comparison['summary'] as $item)
                    <div class="bc-result-quick__item bc-result-quick__item--{{ $item['tone'] }}">
                        <span class="bc-result-quick__label">{{ $item['label'] }}</span>
                        <strong class="bc-result-quick__broker">{{ $item['broker'] }}</strong>
                        <span class="bc-result-quick__value">{{ $item['value'] }}</span>
                    </div>
                @endforeach
            </div>
        @endif

        @if($winner)
            <div class="bc-result-verdict">
                <i class="fas fa-trophy" aria-hidden="true"></i>
                <p><strong>{{ $winner['name'] }}</strong> leads this comparison based on {{ strtolower($winner['reason']) }}</p>
            </div>
        @endif
    </div>
</header>
