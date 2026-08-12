@props(['broker', 'rank' => null, 'featured' => false])

@php
    $reviewUrl = route('broker_detail', $broker->slug);
    $visitUrl = $broker->open_live ?: $broker->visit_site ?: $broker->url;
    $rating = $broker->rating !== null ? round((float) $broker->rating, 1) : null;
    $ratingPercent = $rating !== null ? min(100, ($rating / 5) * 100) : 0;
@endphp

<article @class(['bc-pick-card', 'bc-pick-card--top' => $featured])>
    <div class="bc-pick-card__top">
        @if($rank)
            <span @class(['bc-pick-card__rank', 'bc-pick-card__rank--top' => $rank === 1])>
                {{ $rank === 1 ? '#1 pick' : '#' . $rank }}
            </span>
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
        <a href="{{ $reviewUrl }}" class="bc-pick-card__logo-wrap" tabindex="-1" aria-hidden="true">
            <div class="bc-pick-card__logo">
                @if($broker->logo)
                    <img src="{{ asset($broker->logo) }}" alt="" loading="lazy" decoding="async">
                @else
                    <span class="bc-pick-card__logo-fallback">{{ strtoupper(substr($broker->name, 0, 1)) }}</span>
                @endif
            </div>
        </a>

        <div class="bc-pick-card__identity">
            <a href="{{ $reviewUrl }}" class="bc-pick-card__name">{{ $broker->name }}</a>
            @if($broker->country)
                <p class="bc-pick-card__meta">{{ $broker->country }}</p>
            @endif
        </div>
    </div>

    <div class="bc-pick-card__chips">
        @if($broker->isRegulated())
            <span class="bc-pick-card__chip bc-pick-card__chip--good">Regulated</span>
        @endif
        @if($broker->minimum_deposit !== null)
            <span class="bc-pick-card__chip">Min ${{ number_format((float) $broker->minimum_deposit, 0) }}</span>
        @endif
    </div>

    <div class="bc-pick-card__actions">
        <a href="{{ $reviewUrl }}" class="bc-pick-card__btn bc-pick-card__btn--primary">Review</a>
        @if($visitUrl)
            <a href="{{ $visitUrl }}" class="bc-pick-card__btn bc-pick-card__btn--ghost" target="_blank" rel="noopener noreferrer nofollow">Visit</a>
        @endif
    </div>
</article>
