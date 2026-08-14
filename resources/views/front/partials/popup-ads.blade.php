{{-- Scroll / time / stay triggered popup ads --}}
@php
    $popupAdsPayload = collect($global_popup_ads ?? [])->map->toPopupPayload()->values();
@endphp

@if($popupAdsPayload->isNotEmpty())
<link rel="stylesheet" href="{{ asset('css/popup-ads.css') }}?v=1">

<div id="bc-ad-overlay" role="dialog" aria-modal="true" aria-hidden="true">
    <div id="bc-ad-modal">
        <button type="button" id="bc-ad-close" aria-label="Close">&times;</button>
        <div class="bc-ad-body" id="bc-ad-content"></div>
    </div>
</div>

<script>
    window.__bcPopupAds = @json($popupAdsPayload);
</script>
<script src="{{ asset('js/popup-ads.js') }}?v=1" defer></script>
@endif
