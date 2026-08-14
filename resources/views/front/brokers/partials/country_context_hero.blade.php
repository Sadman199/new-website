{{--
    Country context banner for broker listing pages.
    Shows when a non-global country is selected, or when $pageCountry is passed explicitly.
--}}
@php
    use Illuminate\Support\Str;

    // The compact inline strip is the shared look for every page that shows this banner.
    $variant = 'inline';
    $context = $context ?? 'listing';
    $ctx = $pageCountry ?? $preferredCountry ?? null;
    $slug = $ctx['slug'] ?? 'global';
    $shouldShow = ($pageCountry ?? null) !== null || $slug !== 'global';
    $isInline = $variant === 'inline';
    $brokerLabel = $brokerName ?? null;
@endphp

@if($shouldShow && $ctx)
    @once
        <link rel="stylesheet" href="{{ asset('css/broker-country-hero.css') }}?v=6">
    @endonce
    @php
        $countryName = $ctx['name'] ?? 'your region';
        $brokerCount = (int) ($countryBrokersCount ?? ($brokerCountries[$slug]['broker_count'] ?? 0));

        if ($context === 'review') {
            $eyebrowText = $eyebrow ?? 'Regional context';
            $titleText = $title ?? ('Review context for <em>{country}</em>');
            $metaText = $brokerLabel
                ? 'Availability and regulation for '.$brokerLabel.' may differ in '.$countryName
                : 'Product availability and regulation may differ in this market';
        } else {
            $eyebrowText = $eyebrow ?? 'Your selected region';
            $titleText = $title ?? 'Brokers in <em>{country}</em>';
            $metaText = $brokerCount > 0
                ? $brokerCount.' '.Str::plural('broker', $brokerCount).' in our database match this region'
                : 'Browse brokers relevant to traders in this market';
        }

        $titleHtml = str_replace('{country}', e($countryName), $titleText);
        $flagWidth = $isInline ? 36 : 52;
        $flagHeight = $isInline ? 26 : 38;
        $changeLabel = $isInline ? 'Change' : 'Change region';
    @endphp

    <div class="bc-country-hero-banner{{ $variant ? ' bc-country-hero-banner--'.$variant : '' }}"
         role="status"
         aria-label="Showing content for {{ $countryName }}">
        <div class="bc-country-hero-banner__inner">
            <span class="bc-country-hero-banner__flag" aria-hidden="true">
                @include('front.layout.partial.country-flag', [
                    'country' => $ctx,
                    'width' => $flagWidth,
                    'height' => $flagHeight,
                ])
            </span>

            <div class="bc-country-hero-banner__body">
                @unless($isInline)
                    <p class="bc-country-hero-banner__eyebrow">{{ $eyebrowText }}</p>
                @endunless
                @if($isInline)
                    <p class="bc-country-hero-banner__title">{!! $titleHtml !!}</p>
                @else
                    <h2 class="bc-country-hero-banner__title">{!! $titleHtml !!}</h2>
                @endif
                @unless($isInline)
                    <p class="bc-country-hero-banner__meta">{{ $metaText }}</p>
                @else
                    <p class="bc-country-hero-banner__meta bc-country-hero-banner__meta--inline">{{ $metaText }}</p>
                @endunless
            </div>

            <div class="bc-country-hero-banner__actions">
                <button type="button"
                        class="bc-country-hero-banner__change"
                        onclick="window.bcCountryDrawer?.open()"
                        aria-label="Change selected country">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182"/>
                    </svg>
                    {{ $changeLabel }}
                </button>
            </div>
        </div>
    </div>
@endif
