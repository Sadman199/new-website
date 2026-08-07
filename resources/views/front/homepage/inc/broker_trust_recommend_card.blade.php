@props(['item'])

@php
    $broker = $item['broker'];
@endphp

<a href="{{ $item['review_url'] }}" class="bc-trust-rec">
    <span class="bc-trust-rec__watermark" aria-hidden="true">GOOD</span>
    <span class="bc-trust-rec__rank">#{{ $item['top_broker'] }}</span>

    <div class="bc-trust-rec__logo">
        @if($broker->logo)
            <img src="{{ asset($broker->logo) }}" alt="">
        @else
            <span>{{ strtoupper(substr($broker->name, 0, 1)) }}</span>
        @endif
    </div>

    <span class="bc-trust-rec__name">{{ $broker->name }}</span>

    @if($item['rating'] !== null)
        <span class="bc-trust-rec__rating">{{ number_format($item['rating'], 1) }}<small>/5</small></span>
    @endif
</a>
