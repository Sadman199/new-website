@extends('front.layout.app')

@section('title', ($meta['title'] ?? $tool->name) . ' | Forex Calculators | BrokersCourt')
@section('meta_description', $meta['meta'] ?? $tool->short_description)
@section('canonical', route('trading.tools.show', ['slug' => $slug]))

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/calculators.css') }}?v=4">
    <link rel="stylesheet" href="{{ asset('css/live-markets.css') }}?v=4">
@endpush

@section('main_content')
@php
    $aboutText = trim((string) ($calculator->description ?: ($meta['about'] ?? '')));
@endphp
<div class="calc-page calc-page--detail calc-page--markets">
    <header class="calc-hero calc-hero--detail">
        <div class="container">
            <nav class="calc-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <a href="{{ route('calculators.index') }}">Forex calculators</a>
                <span aria-hidden="true">/</span>
                <span>{{ $meta['title'] ?? $tool->name }}</span>
            </nav>

            <div class="calc-detail__hero">
                <span class="calc-card__icon calc-card__icon--lg" aria-hidden="true">
                    <i class="{{ $tool->icon ?? 'fas fa-chart-area' }}"></i>
                </span>
                <div>
                    <p class="calc-hero__eyebrow calc-hero__eyebrow--inline">
                        <i class="fas fa-chart-area" aria-hidden="true"></i>
                        Market data
                    </p>
                    <h1 class="calc-detail__title">{{ $meta['title'] ?? $tool->name }}</h1>
                    @if(trim((string) ($tool->short_description ?? '')) !== '')
                        <p class="calc-detail__subtitle">{{ $tool->short_description }}</p>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <div class="container calc-main">
        <div class="row g-4 g-xl-5">
            <div class="col-lg-8">
                <div class="calc-markets" id="bcMarketsAppRoot">
                    @include('front.partials.live_markets_board')
                </div>
            </div>

            <aside class="col-lg-4">
                <div class="calc-aside">
                    <section class="calc-aside__panel" aria-labelledby="calc-markets-about-title">
                        <h2 class="calc-aside__title" id="calc-markets-about-title">About these widgets</h2>
                        @if($aboutText !== '')
                            <div class="calc-aside__prose">
                                {!! nl2br(e($aboutText)) !!}
                            </div>
                        @else
                            <p class="calc-aside__text">Track live FX crosses, currency strength, and upcoming economic events while you plan trades.</p>
                        @endif
                    </section>

                    @if($calculators->isNotEmpty())
                        <section class="calc-aside__panel" aria-labelledby="calc-more-title">
                            <h2 class="calc-aside__title" id="calc-more-title">Forex calculators</h2>
                            <nav class="calc-aside__nav" aria-label="Related calculators">
                                @foreach($calculators->take(6) as $item)
                                    <a href="{{ route('calculators.show', ['slug' => $item->route_slug]) }}"
                                       class="calc-aside__link">
                                        <i class="{{ $item->icon ?? 'fas fa-calculator' }}" aria-hidden="true"></i>
                                        <span>{{ $item->name }}</span>
                                    </a>
                                @endforeach
                            </nav>
                            <a href="{{ route('calculators.index') }}" class="calc-aside__back">← All calculators</a>
                        </section>
                    @endif
                </div>
            </aside>
        </div>

        <p class="calc-disclaimer">
            Market data is provided by TradingView for informational purposes. Rates and calendar events are
            indicative — verify with your broker before trading.
        </p>

        <x-broker-slider
            :brokers="$topRatedBrokers ?? collect()"
            section-id="top-rated-brokers"
            title="Top Rated Brokers"
            lead="Compare highly rated brokers while monitoring live market conditions."
            :view-all-url="route('broker.reviews.index')"
            :compact="true"
            class="calc-brokers calc-brokers--footer" />
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/live-markets.js') }}?v=2" defer></script>
@endpush
