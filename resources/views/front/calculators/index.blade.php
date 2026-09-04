@extends('front.layout.app')

@section('title', 'Forex Trading Tools | Calculators & Market Data | BrokersCourt')
@section('meta_description', 'Free forex trading tools to calculate pip value, position size, profit, margin, risk, and trading costs, plus pivot points, Fibonacci levels, currency conversion, and live market data.')
@section('canonical', route('calculators.index'))

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/calculators.css') }}?v=6">
@endpush

@push('json_ld')
    <script type="application/ld+json">@json($hubJsonLd ?? [])</script>
@endpush

@section('main_content')
<div class="calc-page calc-page--hub">
    <header class="calc-hero">
        <div class="container">
            <nav class="calc-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <span>Forex trading tools</span>
            </nav>

            <p class="calc-hero__eyebrow">
                <i class="fas fa-calculator" aria-hidden="true"></i>
                BrokersCourt tools
            </p>
            <h1 class="calc-hero__title">Forex Trading Tools</h1>
            <p class="calc-hero__lead">
                Calculate trading costs, position size, risk, and margin, then map technical levels
                and convert currencies before you compare brokers.
            </p>
            <div class="calc-hero__links">
                <a href="{{ route('broker.comparison') }}" class="bc-btn bc-btn--ghost">Compare brokers</a>
                <a href="{{ route('broker.alternatives.index') }}" class="bc-btn bc-btn--ghost">Broker alternatives</a>
                <a href="{{ route('brokers.best.index') }}" class="bc-btn bc-btn--ghost">Best broker guides</a>
            </div>
        </div>
    </header>

    <div class="container calc-main">
        @forelse($toolGroups as $group)
            <section class="calc-category" aria-labelledby="calc-cat-{{ $group['key'] }}">
                <div class="calc-category__head">
                    <span class="calc-category__icon" aria-hidden="true">
                        <i class="{{ $group['icon'] }}"></i>
                    </span>
                    <div>
                        <h2 class="calc-category__title" id="calc-cat-{{ $group['key'] }}">{{ $group['label'] }}</h2>
                        <p class="calc-category__intro">{{ $group['intro'] }}</p>
                    </div>
                </div>
                <div class="row g-3 g-lg-4">
                    @foreach($group['tools'] as $calculator)
                        @include('front.calculators.partials.card', ['calculator' => $calculator])
                    @endforeach
                </div>
            </section>
        @empty
            <div class="calc-empty-state">
                <div class="calc-empty-state__icon" aria-hidden="true">
                    <i class="fas fa-calculator"></i>
                </div>
                <h2 class="calc-empty-state__title">No tools available at the moment</h2>
                <p class="calc-empty-state__text">Check back soon — we are preparing new trading tools for you.</p>
            </div>
        @endforelse

        <section class="calc-journey" aria-labelledby="calc-journey-title">
            <h2 class="calc-journey__title" id="calc-journey-title">From calculation to broker comparison</h2>
            <p class="calc-journey__text">
                After you have a pip value, position size, or estimated cost, compare published spreads and
                minimum deposits on live broker profiles. Missing figures stay marked unavailable.
            </p>
            <div class="calc-journey__actions">
                <a href="{{ route('broker.reviews.index') }}" class="bc-btn bc-btn--primary">Browse broker reviews</a>
                <a href="{{ route('broker.comparison') }}" class="bc-btn bc-btn--ghost">Open comparison tool</a>
            </div>
        </section>

        <p class="calc-disclaimer">
            Calculators use standard forex formulas with reference rates for planning. They are educational and do not
            constitute trading advice. Always verify with your broker’s contract specifications.
        </p>

        <x-broker-slider
            :brokers="$topRatedBrokers ?? collect()"
            section-id="top-rated-brokers"
            title="Top Rated Brokers"
            lead="Compare highly rated brokers after planning your trade."
            :view-all-url="route('broker.reviews.index')"
            :compact="true"
            class="calc-brokers calc-brokers--footer" />
    </div>
</div>
@endsection
