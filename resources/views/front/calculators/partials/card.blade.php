@php
    $summary = trim((string) ($calculator->short_description ?? ''));
    $searchText = strtolower(trim(($calculator->name ?? '') . ' ' . $summary));
    $url = $calculator->public_url ?? route('calculators.show', ['slug' => $calculator->route_slug]);
    $cta = ! empty($calculator->is_widget) ? 'Open widgets' : 'Calculate now';
    $index = (int) ($index ?? 0);
@endphp
<article class="calc-grid__item"
         data-calc-card
         data-calc-search="{{ $searchText }}"
         style="--calc-card-delay: {{ $index * 60 }}ms">
    <a href="{{ $url }}" class="calc-card">
        <div class="calc-card__top">
            <span class="calc-card__icon" aria-hidden="true">
                <i class="{{ $calculator->icon ?? 'fas fa-calculator' }}"></i>
            </span>
            <h3 class="calc-card__title">{{ $calculator->name }}</h3>
            <span class="calc-card__arrow" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                </svg>
            </span>
        </div>

        @if($summary !== '')
            <p class="calc-card__summary">{{ $summary }}</p>
        @endif

        <span class="calc-card__cta">
            {{ $cta }}
            <i class="fas fa-arrow-right" aria-hidden="true"></i>
        </span>
    </a>
</article>
