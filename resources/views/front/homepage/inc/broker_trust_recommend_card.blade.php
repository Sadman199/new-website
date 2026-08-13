@props(['item', 'featured' => false])

@php
    $broker = $item['broker'];
    $rank = $item['top_broker'] ?: ($item['rank'] ?? null);
@endphp

<a href="{{ $item['review_url'] }}" @class(['bc-trust-rec', 'bc-trust-rec--featured' => $featured])>
    @if($rank)
        <span class="bc-trust-rec__rank">#{{ $rank }}</span>
    @endif

    <div class="bc-trust-rec__logo">
        @if($broker->logo)
            <img src="{{ asset($broker->logo) }}" alt="" loading="lazy" decoding="async">
        @else
            <span>{{ strtoupper(substr($broker->name, 0, 1)) }}</span>
        @endif
    </div>

    <div class="bc-trust-rec__body">
        <span class="bc-trust-rec__name">{{ $broker->name }}</span>
        <span class="bc-trust-rec__meta">
            @if($broker->country)
                {{ $broker->country }}
            @endif
            @if($item['trust_score'] !== null)
                @if($broker->country) · @endif
                Trust {{ $item['trust_score'] }}
            @endif
            @if($item['review_count'] > 0)
                · {{ $item['review_count'] }} {{ \Illuminate\Support\Str::plural('review', $item['review_count']) }}
            @endif
        </span>
    </div>

    @if($item['rating'] !== null)
        <span class="bc-trust-rec__rating">{{ number_format($item['rating'], 1) }}<small>/5</small></span>
    @endif

    <span class="bc-trust-rec__arrow" aria-hidden="true">
        <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/></svg>
    </span>
</a>
