@php
    $summary = trim((string) ($calculator->short_description ?? ''));
    $searchText = strtolower(trim(($calculator->name ?? '') . ' ' . $summary));
@endphp
<div class="col-12 col-md-6 col-lg-4 calc-grid__item"
     data-calc-search="{{ $searchText }}">
    <a href="{{ route('calculators.show', ['slug' => $calculator->route_slug]) }}"
       class="calc-card">
        <div class="calc-card__top">
            <span class="calc-card__icon" aria-hidden="true">
                <i class="{{ $calculator->icon ?? 'fas fa-calculator' }}"></i>
            </span>
            <h2 class="calc-card__title">{{ $calculator->name }}</h2>
        </div>

        @if($summary !== '')
            <p class="calc-card__summary">{{ $summary }}</p>
        @endif

        <span class="calc-card__cta">
            Calculate now
            <i class="fas fa-arrow-right" aria-hidden="true"></i>
        </span>
    </a>
</div>
