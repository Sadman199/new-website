@php
    $average10 = $reviewStats['average10'] ?? null;
    $average5 = (float) ($reviewStats['average'] ?? 0);
    $count = (int) ($reviewStats['count'] ?? 0);
@endphp

<div class="br-overall-rating">
    <div class="br-overall-rating__score">
        <p class="br-overall-rating__kicker">Overall User Rating</p>
        <div class="br-overall-rating__value-row">
            <span class="br-overall-rating__value">{{ $average10 ?? '—' }}</span>
            <span class="br-overall-rating__max">/10</span>
        </div>
    </div>

    <div class="br-overall-rating__detail">
        <span class="br-overall-rating__stars" aria-hidden="true">
            @for($i = 1; $i <= 5; $i++)
                <span class="{{ $i <= round($average5) ? 'is-on' : '' }}">&#9733;</span>
            @endfor
        </span>
        <p class="br-overall-rating__meta">
            Based on <strong>{{ $count }}</strong> published review{{ $count === 1 ? '' : 's' }} from verified community members
        </p>
    </div>
</div>
