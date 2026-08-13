@props(['broker', 'rank' => null, 'featured' => false])

@php
    $reviewUrl = route('broker_detail', $broker->slug);
    $visitUrl = $broker->open_live ?: $broker->visit_site ?: $broker->url;
    $rating = $broker->rating !== null ? round((float) $broker->rating, 1) : null;
    $ratingPercent = $rating !== null ? min(100, ($rating / 5) * 100) : 0;
    $isRegulated = $broker->isRegulated();
    $regs = method_exists($broker, 'regulationList') ? array_slice($broker->regulationList() ?: [], 0, 2) : [];
    $leverage = $broker->leverage ? strip_tags((string) $broker->leverage) : null;
    $spreads = $broker->spreads ? strip_tags((string) $broker->spreads) : null;
    $minDeposit = $broker->minimum_deposit !== null
        ? '$' . number_format((float) $broker->minimum_deposit, 0)
        : null;
@endphp

<article @class(['bc-pick-card', 'bc-pick-card--top' => $featured])>
    <div class="bc-pick-card__top">
        @if($rank)
            <span @class(['bc-pick-card__rank', 'bc-pick-card__rank--top' => $rank === 1])>
                {{ $rank === 1 ? '#1 pick' : '#' . $rank }}
            </span>
        @endif

        @if($isRegulated)
            <span class="bc-pick-card__status">Regulated</span>
        @elseif($broker->featured_broker)
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
            <p class="bc-pick-card__meta">
                @if($broker->country)
                    <span>{{ $broker->country }}</span>
                @endif
                @if($regs)
                    @if($broker->country)<span class="bc-pick-card__dot" aria-hidden="true">·</span>@endif
                    <span>{{ implode(', ', $regs) }}</span>
                @endif
            </p>
        </div>
    </div>

    @if($minDeposit || $leverage || $spreads)
        <dl class="bc-pick-card__stats">
            @if($minDeposit)
                <div class="bc-pick-card__stat">
                    <dt>Min. deposit</dt>
                    <dd>{{ $minDeposit }}</dd>
                </div>
            @endif
            @if($leverage)
                <div class="bc-pick-card__stat">
                    <dt>Leverage</dt>
                    <dd>{{ \Illuminate\Support\Str::limit($leverage, 14) }}</dd>
                </div>
            @endif
            @if($spreads)
                <div class="bc-pick-card__stat">
                    <dt>Spreads</dt>
                    <dd>{{ \Illuminate\Support\Str::limit($spreads, 14) }}</dd>
                </div>
            @endif
        </dl>
    @endif

    <div class="bc-pick-card__actions">
        <a href="{{ $reviewUrl }}" class="bc-pick-card__btn bc-pick-card__btn--review">
            Read review
            <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/></svg>
        </a>
        @if($visitUrl)
            <a href="{{ $visitUrl }}" class="bc-pick-card__btn bc-pick-card__btn--visit" target="_blank" rel="noopener noreferrer nofollow">Visit</a>
        @endif
    </div>
</article>
