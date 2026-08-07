<section class="bbg-methodology" id="methodology">
    <div class="bbg-methodology__head">
        <h2 class="bbg-section__title">{{ $guidePage['guide']['methodology']['title'] }}</h2>
        <p class="bbg-section__text">{{ $guidePage['guide']['methodology']['intro'] }}</p>
    </div>

    @php
        $methodIcons = [
            'fa-chart-line',
            'fa-desktop',
            'fa-wallet',
            'fa-shield-alt',
            'fa-balance-scale',
            'fa-database',
        ];
    @endphp

    <ol class="bbg-methodology__grid">
        @foreach($guidePage['guide']['methodology']['points'] as $index => $point)
            <li class="bbg-methodology__item">
                <span class="bbg-methodology__icon" aria-hidden="true">
                    <i class="fas {{ $methodIcons[$index] ?? 'fa-check' }}"></i>
                </span>
                <p class="bbg-methodology__text">{{ $point }}</p>
            </li>
        @endforeach
    </ol>

    <a href="{{ route('methodology') }}" class="bbg-methodology__link">Read our full methodology</a>
</section>
