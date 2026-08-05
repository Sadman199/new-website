@php
    use App\Support\BrokerTaxonomy;

    $country = $country ?? BrokerTaxonomy::resolvePreferredCountry($slug ?? null);
    $code = $country['code'] ?? null;
    $class = trim(($class ?? 'bc-flag-img').' bc-flag');
    $width = (int) ($width ?? 24);
    $height = (int) ($height ?? max(14, (int) round($width * 0.75)));
@endphp
@if($code)
    <img src="{{ BrokerTaxonomy::countryFlagUrl($code, $width) }}"
         srcset="{{ BrokerTaxonomy::countryFlagUrl($code, $width * 2) }} 2x"
         alt=""
         class="{{ $class }}"
         width="{{ $width }}"
         height="{{ $height }}"
         loading="lazy"
         decoding="async"
         aria-hidden="true">
@else
    <svg class="{{ $class }} bc-flag-globe" width="{{ $width }}" height="{{ $width }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
        <circle cx="12" cy="12" r="9" stroke-width="1.75"/>
        <path stroke-width="1.75" d="M3 12h18M12 3c2.5 2.8 3.8 6.2 3.8 9s-1.3 6.2-3.8 9M12 3c-2.5 2.8-3.8 6.2-3.8 9s1.3 6.2 3.8 9"/>
    </svg>
@endif
