@php
    $listingHtml = \App\Support\RichText::forDisplay($guidePage['guide']['description'] ?? null);
@endphp

@if($listingHtml)
<section class="bgx-section" id="explainer">
    <div class="bgx-listing-copy">
        {!! $listingHtml !!}
    </div>
</section>
@endif
