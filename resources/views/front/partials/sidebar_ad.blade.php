{{-- Shared floating sidebar ad for listing pages. Expects $row. --}}
@php
    $adUrl = trim((string) ($row->sidebar_ad_url ?? ''));
    $adSrc = asset('uploads/' . $row->sidebar_ad);
@endphp
<div class="relative group">
    <div class="relative rounded-lg overflow-hidden shadow-lg border-2 border-gray-200 hover:border-blue-400 transition-all duration-300">
        <div class="absolute top-1 sm:top-2 left-1 sm:left-2 bg-yellow-400 text-black text-xs font-bold px-1.5 sm:px-2 py-0.5 sm:py-1 rounded z-10">ADVERTISEMENT</div>
        @if($adUrl === '')
            <img src="{{ $adSrc }}" alt="" class="w-24 sm:w-32 lg:w-48 h-auto object-contain pointer-events-auto rounded-lg" loading="lazy" decoding="async">
        @else
            <a href="{{ $adUrl }}" class="pointer-events-auto" target="_blank" rel="noopener noreferrer">
                <img src="{{ $adSrc }}" alt="" class="w-24 sm:w-32 lg:w-48 h-auto object-contain pointer-events-auto rounded-lg" loading="lazy" decoding="async">
            </a>
        @endif
    </div>
</div>
