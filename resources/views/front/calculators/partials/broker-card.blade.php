@php
    $card = \App\Support\BrokerCardData::from($broker);
@endphp
<article class="calc-broker-mini">
    <a href="{{ $card['review_url'] }}" class="calc-broker-mini__link">
        @if($card['logo'])
            <img src="{{ $card['logo'] }}" alt="" width="36" height="36" loading="lazy">
        @endif
        <span>
            <strong>{{ $card['name'] }}</strong>
            <small>
                {{ $card['rating'] !== null ? number_format((float) $card['rating'], 1).' rating' : 'Rating unavailable' }}
                · {{ $card['spreads'] }}
            </small>
        </span>
    </a>
    <a href="{{ $card['review_url'] }}" class="calc-broker-mini__cta">Profile</a>
</article>
