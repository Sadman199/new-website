@php
    $rating = \App\Support\BrokerRating::outOfFive($broker['rating'] ?? null);
    $ratingPercent = \App\Support\BrokerRating::percent($broker['rating'] ?? null);
    $feeScore = isset($broker['fee_score']) ? max(0, min(5, (float) $broker['fee_score'])) : null;
    $platformScore = isset($broker['platform_score']) ? max(0, min(5, (float) $broker['platform_score'])) : null;
    $summary = $broker['top_feature'] ?: $broker['short_description'] ?: 'Recognized for dependable service and strong trading conditions.';
@endphp

<li class="awd-winner-card {{ $rank === 1 ? 'is-top-pick' : '' }}">
    <div class="awd-winner-card__topline">
        <span class="awd-winner-card__rank">
            <strong>#{{ $rank }}</strong>
            {{ $rank === 1 ? 'Top pick' : 'Award winner' }}
        </span>
        <span class="awd-winner-card__award">{{ $awardName }}</span>
    </div>

    <div class="awd-winner-card__identity">
        <a href="{{ $broker['review_url'] }}" class="awd-winner-card__logo" aria-hidden="true" tabindex="-1">
            @if($broker['logo'])
                <img src="{{ $broker['logo'] }}" alt="" loading="lazy" decoding="async">
            @else
                <span>{{ strtoupper(substr($broker['name'], 0, 1)) }}</span>
            @endif
        </a>

        <div class="awd-winner-card__name-wrap">
            <a href="{{ $broker['review_url'] }}" class="awd-winner-card__name">{{ $broker['name'] }}</a>
            @if(!empty($broker['is_regulated']))
                <span class="awd-winner-card__trust bc-regulated-tag">Regulated</span>
            @else
                <span class="awd-winner-card__trust awd-winner-card__trust--reviewed">Editorially reviewed</span>
            @endif
        </div>

        @if($rating !== null)
            <div class="awd-winner-card__rating"
                 style="--award-rating: {{ $ratingPercent }}%"
                 aria-label="Rating {{ number_format($rating, 1) }} out of 5">
                <strong>{{ number_format($rating, 1) }}</strong>
                <span>/5</span>
            </div>
        @endif
    </div>

    <p class="awd-winner-card__summary">{{ $summary }}</p>

    <dl class="awd-winner-card__facts">
        <div>
            <dt>Fee level</dt>
            <dd>{{ $broker['fee_level'] ?? '—' }}</dd>
        </div>
        <div>
            <dt>Client reviews</dt>
            <dd>{{ number_format((int) ($broker['review_count'] ?? 0)) }}</dd>
        </div>
        <div>
            <dt>Mobile access</dt>
            <dd>{{ \Illuminate\Support\Str::limit($broker['mobile_platform'] ?? '—', 18) }}</dd>
        </div>
    </dl>

    @if($feeScore !== null || $platformScore !== null)
        <div class="awd-winner-card__scores" aria-label="Editorial category scores">
            @if($feeScore !== null)
                <div class="awd-winner-card__score">
                    <div class="awd-winner-card__score-head">
                        <span>Fees & costs</span>
                        <strong>{{ number_format($feeScore, 1) }}/5</strong>
                    </div>
                    <div class="awd-winner-card__bar" aria-hidden="true">
                        <i style="width: {{ ($feeScore / 5) * 100 }}%"></i>
                    </div>
                </div>
            @endif
            @if($platformScore !== null)
                <div class="awd-winner-card__score">
                    <div class="awd-winner-card__score-head">
                        <span>Platform quality</span>
                        <strong>{{ number_format($platformScore, 1) }}/5</strong>
                    </div>
                    <div class="awd-winner-card__bar" aria-hidden="true">
                        <i style="width: {{ ($platformScore / 5) * 100 }}%"></i>
                    </div>
                </div>
            @endif
        </div>
    @endif

    @if(!empty($broker['regulation_summary']))
        <p class="awd-winner-card__regulation">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M12 3l7.5 3v5.25c0 4.75-3.2 8.25-7.5 9.75-4.3-1.5-7.5-5-7.5-9.75V6L12 3Z"/>
            </svg>
            {{ $broker['regulation_summary'] }}
        </p>
    @endif

    <div class="awd-winner-card__actions">
        <a href="{{ $broker['review_url'] }}" class="awd-winner-card__btn awd-winner-card__btn--review">Read full review</a>
        @if(!empty($broker['visit_url']))
            <a href="{{ $broker['visit_url'] }}" class="awd-winner-card__btn awd-winner-card__btn--visit" target="_blank" rel="noopener noreferrer nofollow">
                Visit broker
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                </svg>
            </a>
        @endif
    </div>

    <p class="awd-winner-card__risk">Your capital is at risk.</p>
</li>
