@extends('front.layout.app')

@section('title', $seo['title'] ?? (($meta['title'] ?? $tool->name) . ' | Forex Calculators | BrokersCourt'))
@section('meta_description', $seo['description'] ?? ($meta['meta'] ?? $tool->short_description))
@section('canonical', $seo['canonical'] ?? route('trading.tools.show', ['slug' => $slug]))
@section('og_title', $seo['og_title'] ?? ($seo['title'] ?? $tool->name))
@section('og_description', $seo['og_description'] ?? ($seo['description'] ?? ''))

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/calculators.css') }}?v=6">
    <link rel="stylesheet" href="{{ asset('css/live-markets.css') }}?v=4">
@endpush

@if(! empty($jsonLd))
@push('json_ld')
    <script type="application/ld+json">@json($jsonLd)</script>
@endpush
@endif

@section('main_content')
@php
    $intro = trim((string) (($pageContent['introduction'] ?? '') ?: ($tool->short_description ?? '')));
    $aboutText = trim((string) ($calculator->description ?: ($meta['about'] ?? '')));
@endphp
<div class="calc-page calc-page--detail calc-page--markets">
    <header class="calc-hero calc-hero--detail">
        <div class="container">
            <nav class="calc-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <a href="{{ route('calculators.index') }}">Forex trading tools</a>
                <span aria-hidden="true">/</span>
                <span>{{ $tool->name }}</span>
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
                    <h1 class="calc-detail__title">{{ $tool->name }}</h1>
                    @if($intro !== '')
                        <p class="calc-detail__subtitle">{{ $intro }}</p>
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

                @isset($pageContent)
                    @include('front.calculators.partials.content-sections', [
                        'pageContent' => $pageContent,
                        'howToTitle' => 'How to use these widgets',
                    ])
                    @include('front.calculators.partials.related-tools', ['relatedTools' => $relatedTools ?? collect()])
                    @include('front.calculators.partials.faq', ['faqs' => $faqs ?? []])
                @endisset
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

                    <section class="calc-aside__panel" aria-labelledby="calc-markets-next-title">
                        <h2 class="calc-aside__title" id="calc-markets-next-title">Next steps</h2>
                        <nav class="calc-aside__nav" aria-label="BrokersCourt links">
                            <a href="{{ route('calculators.show', ['slug' => 'trading-cost-calculator']) }}" class="calc-aside__link">
                                <i class="fas fa-file-invoice-dollar" aria-hidden="true"></i>
                                <span>Calculate trading costs</span>
                            </a>
                            <a href="{{ route('broker.comparison') }}" class="calc-aside__link">
                                <i class="fas fa-balance-scale" aria-hidden="true"></i>
                                <span>Compare brokers</span>
                            </a>
                            <a href="{{ route('broker.alternatives.index') }}" class="calc-aside__link">
                                <i class="fas fa-random" aria-hidden="true"></i>
                                <span>Broker alternatives</span>
                            </a>
                        </nav>
                    </section>

                    @php $sidebarTools = ($relatedTools ?? collect())->isNotEmpty() ? $relatedTools : $calculators->take(6); @endphp
                    @if($sidebarTools->isNotEmpty())
                        <section class="calc-aside__panel" aria-labelledby="calc-more-title">
                            <h2 class="calc-aside__title" id="calc-more-title">Forex calculators</h2>
                            <nav class="calc-aside__nav" aria-label="Related calculators">
                                @foreach($sidebarTools as $item)
                                    <a href="{{ $item->public_url ?? route('calculators.show', ['slug' => $item->route_slug]) }}"
                                       class="calc-aside__link">
                                        <i class="{{ $item->icon ?? 'fas fa-calculator' }}" aria-hidden="true"></i>
                                        <span>{{ $item->name }}</span>
                                    </a>
                                @endforeach
                            </nav>
                            <a href="{{ route('calculators.index') }}" class="calc-aside__back">← All trading tools</a>
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
