@props(['item'])

@php
    $broker = $item['broker'];
@endphp

<a href="{{ $item['review_url'] }}" class="bc-trust-alert">
    <span class="bc-trust-alert__watermark" aria-hidden="true">BAD</span>
    <span class="bc-trust-alert__flag">Flagged</span>

    <div class="bc-trust-alert__logo">
        @if($broker->logo)
            <img src="{{ asset($broker->logo) }}" alt="">
        @else
            <span>{{ strtoupper(substr($broker->name, 0, 1)) }}</span>
        @endif
    </div>

    <span class="bc-trust-alert__name">{{ $broker->name }}</span>
</a>
