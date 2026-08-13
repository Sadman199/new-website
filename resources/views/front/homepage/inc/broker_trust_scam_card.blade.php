@props(['item'])

@php
    $broker = $item['broker'];
@endphp

<a href="{{ $item['review_url'] }}" class="bc-trust-alert">
    <span class="bc-trust-alert__flag">Flagged</span>

    <div class="bc-trust-alert__logo">
        @if($broker->logo)
            <img src="{{ asset($broker->logo) }}" alt="" loading="lazy" decoding="async">
        @else
            <span>{{ strtoupper(substr($broker->name, 0, 1)) }}</span>
        @endif
    </div>

    <div class="bc-trust-alert__body">
        <span class="bc-trust-alert__name">{{ $broker->name }}</span>
        @if(!empty($item['scam_reason']))
            <span class="bc-trust-alert__reason">{{ $item['scam_reason'] }}</span>
        @elseif(!empty($item['reported_label']))
            <span class="bc-trust-alert__reason">Reported {{ $item['reported_label'] }}</span>
        @else
            <span class="bc-trust-alert__reason">High-risk broker warning</span>
        @endif
    </div>

    <span class="bc-trust-alert__arrow" aria-hidden="true">
        <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/></svg>
    </span>
</a>
