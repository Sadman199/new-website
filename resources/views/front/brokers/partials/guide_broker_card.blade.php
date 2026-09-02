@php
    $listingFields = \App\Support\BestBrokerGuideMetrics::listingFields();
@endphp

<article class="bgx-review{{ ! empty($featured) ? ' bgx-review--featured' : '' }}" id="{{ $listingId ?? 'broker-'.$entry['rank'] }}">
    <header class="bgx-review__head">
        <span class="bgx-review__rank">{{ str_pad((string) $entry['rank'], 2, '0', STR_PAD_LEFT) }}</span>

        <div class="bgx-review__brand-logo" aria-hidden="true">
            @if($entry['logo_url'])
                <img src="{{ $entry['logo_url'] }}" alt="" loading="lazy" decoding="async">
            @else
                <span>{{ $entry['initial'] }}</span>
            @endif
        </div>

        <h3 class="bgx-review__name">
            {{ $entry['name'] }}
            @if($entry['rank'] === 1)
                <span class="bgx-review__mark">Top pick</span>
            @endif
        </h3>
    </header>

    @if($entry['headline'])
        <p class="bgx-review__lede">{{ $entry['headline'] }}</p>
    @endif

    <dl class="bgx-review__facts">
        @foreach($listingFields as $field)
            @include('front.brokers.partials.guide_broker_spec', ['entry' => $entry, 'field' => $field])
        @endforeach
    </dl>

    <div class="bgx-review__cta">
        <a class="bgx-review__visit"
           href="{{ $entry['visit_url'] }}"
           target="_blank"
           rel="noopener nofollow"
           aria-label="Visit {{ $entry['name'] }}">
            <span class="bgx-review__visit-logo">
                @if($entry['logo_url'])
                    <img src="{{ $entry['logo_url'] }}" alt="" loading="lazy" decoding="async">
                @else
                    <span>{{ $entry['initial'] }}</span>
                @endif
            </span>
            <span class="bgx-review__visit-copy">
                <span class="bgx-review__visit-kicker">Visit broker</span>
                <span class="bgx-review__visit-name">{{ $entry['name'] }}</span>
            </span>
        </a>

        <a class="bgx-review__read" href="{{ $entry['review_url'] }}">
            Read review
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M5 12h14M13 6l6 6-6 6"/>
            </svg>
        </a>
    </div>
</article>
