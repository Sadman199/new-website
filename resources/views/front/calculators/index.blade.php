@extends('front.layout.app')

@section('title', 'Forex Calculators | Free Trading Tools | BrokersCourt')
@section('meta_description', 'Free forex calculators for pip value, position sizing, profit and loss, margin, risk management, pivot points, Fibonacci levels, and currency conversion.')
@section('canonical', route('calculators.index'))

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/calculators.css') }}?v=4">
@endpush

@section('main_content')
<div class="calc-page">
    <header class="calc-hero">
        <div class="container">
            <nav class="calc-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <span>Forex calculators</span>
            </nav>

            <p class="calc-hero__eyebrow">
                <i class="fas fa-calculator" aria-hidden="true"></i>
                Trading tools
            </p>
            <h1 class="calc-hero__title">Forex Calculators</h1>
            <p class="calc-hero__lead">
                Estimate trading costs, position sizes, profit and loss, margin requirements, pip values,
                and other key metrics before you place a trade.
            </p>
        </div>
    </header>

    <div class="container calc-main">
        @if($calculators->isNotEmpty())
            <div class="row g-3 g-lg-4">
                @foreach($calculators as $calculator)
                    @include('front.calculators.partials.card', ['calculator' => $calculator])
                @endforeach
            </div>
        @else
            <div class="calc-empty-state">
                <div class="calc-empty-state__icon" aria-hidden="true">
                    <i class="fas fa-calculator"></i>
                </div>
                <h2 class="calc-empty-state__title">No calculators available at the moment</h2>
                <p class="calc-empty-state__text">Check back soon — we are preparing new trading tools for you.</p>
            </div>
        @endif

        @if($widgetTool ?? null)
            <section class="calc-widget-promo" aria-labelledby="calc-widget-promo-title">
                <a href="{{ route('trading.tools.show', ['slug' => $widgetTool->route_slug]) }}"
                   class="calc-widget-promo__card">
                    <span class="calc-widget-promo__icon" aria-hidden="true">
                        <i class="{{ $widgetTool->icon ?? 'fas fa-chart-area' }}"></i>
                    </span>
                    <span class="calc-widget-promo__body">
                        <span class="calc-widget-promo__eyebrow">Market data</span>
                        <span class="calc-widget-promo__title" id="calc-widget-promo-title">{{ $widgetTool->name }}</span>
                        <span class="calc-widget-promo__text">{{ $widgetTool->short_description }}</span>
                    </span>
                    <span class="calc-widget-promo__cta">
                        Open widgets
                        <i class="fas fa-arrow-right" aria-hidden="true"></i>
                    </span>
                </a>
            </section>
        @endif

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
