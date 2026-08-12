@php
    $snapshot = $snapshot ?? [];
    $isScam = !empty($snapshot['is_scam']);
    $primaryUrl = $isScam
        ? '#compare'
        : ($snapshot['visit_url'] ?? $broker->open_live ?: $broker->visit_site ?: $broker->url);
    $primaryLabel = $isScam ? 'Safer brokers' : 'Visit broker';
@endphp
<div class="br-mobile-cta" id="br-mobile-cta" hidden aria-hidden="true">
    <div class="br-mobile-cta__inner bbg-container">
        <div class="br-mobile-cta__brand">
            @if($broker->logo)
                <img src="{{ asset($broker->logo) }}" alt="" class="br-mobile-cta__logo" loading="lazy">
            @endif
            <div class="br-mobile-cta__meta">
                <strong>{{ $broker->name }}</strong>
                <span>{{ $snapshot['score'] ?? number_format((float) $broker->rating, 1) }}/10</span>
            </div>
        </div>
        <a href="{{ $primaryUrl }}"
           @unless($isScam) target="_blank" rel="noopener noreferrer sponsored" @endunless
           class="br-btn br-btn--primary br-btn--sm br-mobile-cta__btn{{ $isScam ? ' br-btn--warned-solid' : '' }}">
            {{ $primaryLabel }}
        </a>
    </div>
</div>
