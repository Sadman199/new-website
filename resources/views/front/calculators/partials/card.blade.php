@php
    $summary = trim((string) ($calculator->short_description ?? ''));
    $searchText = strtolower(trim(($calculator->name ?? '') . ' ' . $summary));
    $url = $calculator->public_url ?? route('calculators.show', ['slug' => $calculator->route_slug]);
    $cta = ! empty($calculator->is_widget) ? 'Open widgets' : 'Calculate now';
@endphp
<div class="col-12 col-md-6 col-lg-4 calc-grid__item"
     data-calc-search="{{ $searchText }}">
    <a href="{{ $url }}" class="calc-card">
        <div class="calc-card__top">
            <span class="calc-card__icon" aria-hidden="true">
                <i class="{{ $calculator->icon ?? 'fas fa-calculator' }}"></i>
            </span>
            <h3 class="calc-card__title">{{ $calculator->name }}</h3>
        </div>

        @if($summary !== '')
            <p class="calc-card__summary">{{ $summary }}</p>
        @endif

        <span class="calc-card__cta">
            {{ $cta }}
            <i class="fas fa-arrow-right" aria-hidden="true"></i>
        </span>
    </a>
</div>
