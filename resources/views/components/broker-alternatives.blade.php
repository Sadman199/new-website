@props(['broker' => null])

@php
    $enabled = $broker
        && \Illuminate\Support\Facades\Schema::hasTable('broker_alternative_pages')
        && $broker->alternativePage
        && $broker->alternativePage->is_published;
@endphp

@if($enabled)
    <aside {{ $attributes->class('ba-cta') }} aria-label="Broker alternatives">
        <span class="ba-cta__aurora" aria-hidden="true"></span>
        <span class="ba-cta__icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 7.5 4.5 10.5l3 3"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5h10.25a3.75 3.75 0 0 1 0 7.5H13"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 16.5 19.5 13.5l-3-3"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5H9.25a3.75 3.75 0 0 1 0-7.5H11"/>
            </svg>
        </span>
        <div class="ba-cta__copy">
            <p class="ba-cta__eyebrow">Curated alternatives</p>
            <p class="ba-cta__title">Looking for an alternative to <em>{{ $broker->name }}</em>?</p>
            <p class="ba-cta__text">Compare similar brokers based on trading conditions, regulation and fees.</p>
            <ul class="ba-cta__chips">
                <li>Trading conditions</li>
                <li>Regulation</li>
                <li>Fees</li>
            </ul>
        </div>
        <a href="{{ route('broker.alternatives.show', ['slug' => $broker->slug]) }}" class="ba-cta__btn">
            <span>Explore alternatives</span>
            <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 4.5 13 10l-5.5 5.5"/>
            </svg>
        </a>
    </aside>
@endif
