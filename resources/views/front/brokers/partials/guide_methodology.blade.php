@php
    $methodology = $guidePage['guide']['methodology'];
    $methodIcons = ['fa-chart-line', 'fa-wallet', 'fa-shield-alt', 'fa-desktop', 'fa-sliders-h', 'fa-database'];
@endphp

<section class="bgx-section" id="methodology">
    <div class="bgx-method">
        <header class="bgx-section__head">
            <h2 class="bgx-h2">{{ $methodology['title'] }}</h2>
            <p class="bgx-prose">{{ $methodology['intro'] }}</p>
        </header>

        <ol class="bgx-method__grid">
            @foreach($methodology['points'] as $index => $point)
                <li class="bgx-method__item" style="--bgx-delay: {{ $index * 60 }}ms">
                    <span class="bgx-method__icon" aria-hidden="true"><i class="fas {{ $methodIcons[$index] ?? 'fa-check' }}"></i></span>
                    <p>{{ $point }}</p>
                </li>
            @endforeach
        </ol>

        <a href="{{ route('methodology') }}" class="bgx-textlink">Read our full methodology <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
    </div>
</section>
