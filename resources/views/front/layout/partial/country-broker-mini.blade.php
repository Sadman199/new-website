@php
    $reviewUrl = route('broker_detail', $broker->slug);
    $rating = $broker->rating !== null ? round((float) $broker->rating, 1) : null;
    $showHeadquarters = $showHeadquarters ?? false;
@endphp

<article class="bc-country-mini">
    @if(!empty($rank))
        <span class="bc-country-mini__rank">#{{ $rank }}</span>
    @endif

    <a href="{{ $reviewUrl }}" class="bc-country-mini__logo" tabindex="-1" aria-hidden="true">
        @if($broker->logo)
            <img src="{{ asset($broker->logo) }}" alt="" loading="lazy" decoding="async">
        @else
            <span class="bc-country-mini__logo-fallback">{{ strtoupper(substr($broker->name, 0, 1)) }}</span>
        @endif
    </a>

    <div class="bc-country-mini__body">
        <a href="{{ $reviewUrl }}" class="bc-country-mini__name">{{ $broker->name }}</a>
        <div class="bc-country-mini__meta">
            @if($showHeadquarters && $broker->country)
                <span class="bc-country-mini__hq">{{ $broker->country }}</span>
            @endif
            @if($rating !== null)
                <span class="bc-country-mini__rating">{{ number_format($rating, 1) }}/5</span>
            @endif
            @if($broker->minimum_deposit !== null)
                <span class="bc-country-mini__deposit">Min ${{ number_format((float) $broker->minimum_deposit, 0) }}</span>
            @endif
        </div>
    </div>

    <a href="{{ $reviewUrl }}" class="bc-country-mini__cta">Review</a>
</article>
