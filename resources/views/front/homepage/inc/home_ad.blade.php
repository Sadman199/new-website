@php
    $slot = $slot ?? 'search';
    $ad = $home_ad_data ?? null;

    if ($slot === 'footer') {
        $status = $ad->above_footer_ad_status ?? null;
        $file = $ad->above_footer_ad ?? null;
        $url = trim((string) ($ad->above_footer_ad_url ?? ''));
        $labelId = 'bcHomeAdFooterLabel';
    } else {
        $status = $ad->above_search_ad_status ?? null;
        $file = $ad->above_search_ad ?? null;
        $url = trim((string) ($ad->above_search_ad_url ?? ''));
        $labelId = 'bcHomeAdSearchLabel';
    }

    $file = is_string($file) ? trim($file) : '';
    $visible = $ad && $status === 'Show' && $file !== '';

    if ($visible) {
        if (\Illuminate\Support\Str::startsWith($file, ['http://', 'https://'])) {
            $src = $file;
        } else {
            $src = asset('uploads/' . ltrim($file, '/'));
        }
    }
@endphp

@if($visible)
<section class="bc-home-ad bc-home-ad--{{ $slot }}" aria-labelledby="{{ $labelId }}">
    <div class="container">
        <p class="bc-home-ad__eyebrow" id="{{ $labelId }}">
            <span class="bc-home-ad__eyebrow-dot" aria-hidden="true"></span>
            Sponsored
        </p>

        @if($url !== '')
            <a href="{{ $url }}"
               class="bc-home-ad__frame"
               target="_blank"
               rel="sponsored noopener nofollow">
                <img src="{{ $src }}"
                     alt="Advertisement"
                     class="bc-home-ad__image"
                     loading="{{ $slot === 'footer' ? 'lazy' : 'eager' }}"
                     decoding="async">
            </a>
        @else
            <div class="bc-home-ad__frame">
                <img src="{{ $src }}"
                     alt="Advertisement"
                     class="bc-home-ad__image"
                     loading="{{ $slot === 'footer' ? 'lazy' : 'eager' }}"
                     decoding="async">
            </div>
        @endif
    </div>
</section>
@endif
