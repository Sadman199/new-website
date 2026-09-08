@extends('front.layout.app')

@section('title', $seo['title'])
@section('meta_description', $seo['description'])
@section('canonical', $seo['canonical'])
@section('og_title', $seo['og_title'])
@section('og_description', $seo['og_description'])

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/calculators.css') }}?v=10">
@endpush

@push('json_ld')
    <script type="application/ld+json">@json($jsonLd ?? [])</script>
@endpush

@section('main_content')
@php
    $intro = trim((string) ($pageContent['introduction'] ?? ''));
@endphp
<div class="calc-page calc-page--detail">
    <header class="calc-hero calc-hero--detail">
        <div class="calc-hero__bg" aria-hidden="true"></div>
        <div class="calc-wrap">
            <nav class="calc-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <a href="{{ route('calculators.index') }}">Forex trading tools</a>
                <span aria-hidden="true">/</span>
                <span>{{ $calculator->name }}</span>
            </nav>

            <div class="calc-detail__hero">
                <span class="calc-card__icon calc-card__icon--lg" aria-hidden="true">
                    <i class="{{ $calculator->icon ?? 'fas fa-calculator' }}"></i>
                </span>
                <div>
                    <p class="calc-hero__eyebrow calc-hero__eyebrow--inline">
                        <i class="fas fa-calculator" aria-hidden="true"></i>
                        Forex calculator
                    </p>
                    <h1 class="calc-detail__title">{{ $calculator->name }}</h1>
                    @if($intro !== '')
                        <p class="calc-detail__subtitle">{{ $intro }}</p>
                    @elseif(trim((string) ($calculator->short_description ?? '')) !== '')
                        <p class="calc-detail__subtitle">{{ $calculator->short_description }}</p>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <div class="calc-wrap calc-main calc-main--detail">
        <div class="calc-detail__layout">
            <div class="calc-detail__workspace">
                <div class="calc-detail__card calc-tool"
                     id="toolsDashboard"
                     data-calc-url="{{ route('calculators.calculate') }}"
                     data-rates='@json($rates)'
                     @if(($costBrokerHints ?? []) !== []) data-brokers='@json($costBrokerHints)' @endif
                     @if(! empty($costBrokerSearchUrl)) data-broker-search-url="{{ $costBrokerSearchUrl }}" @endif>
                    <div class="calc-detail__panes">
                        <div class="calc-detail__pane calc-detail__pane--inputs">
                            <h2 class="calc-detail__pane-title">Inputs</h2>
                            <div class="calc-detail__fields">
                                @include('front.pages.partials.tools.' . $toolKey, [
                                    'pairs' => $pairs,
                                    'currencies' => $currencies,
                                    'costBrokerHints' => $costBrokerHints ?? [],
                                ])
                            </div>
                            <button type="button"
                                    class="bc-btn bc-btn--primary calc-detail__submit calc-tool__submit"
                                    data-calc="{{ $toolKey }}">
                                <i class="fas fa-calculator" aria-hidden="true"></i>
                                Calculate
                            </button>
                        </div>

                        <section class="calc-detail__pane calc-detail__pane--results" aria-live="polite">
                            <div class="calc-detail__results-head">
                                <h2 class="calc-detail__pane-title">Results</h2>
                                <span class="calc-detail__status tt-status" data-status="{{ $toolKey }}">Ready</span>
                            </div>
                            <div class="calc-detail__results tt-results" data-results="{{ $toolKey }}">
                                <p class="calc-detail__placeholder tt-results__placeholder">Enter your values and click Calculate to see results here.</p>
                            </div>
                        </section>
                    </div>
                </div>

                <p class="calc-disclaimer calc-disclaimer--detail">
                    Calculators use standard forex formulas with reference rates for planning. They are educational and do not
                    constitute trading advice. Always verify with your broker’s contract specifications.
                </p>

                @include('front.calculators.partials.content-sections', ['pageContent' => $pageContent])
                @include('front.calculators.partials.related-tools', ['relatedTools' => $relatedTools])
                @include('front.calculators.partials.broker-costs', [
                    'showBrokerCosts' => $showBrokerCosts,
                    'brokerCostCards' => $brokerCostCards,
                    'toolKey' => $toolKey,
                ])
                @if(empty($showBrokerCosts))
                    @include('front.calculators.partials.related-brokers', ['relatedBrokers' => $relatedBrokers])
                @endif
                @include('front.calculators.partials.faq', ['faqs' => $faqs])
            </div>

            <aside class="calc-detail__aside" aria-label="Calculator information">
                <section class="calc-aside__panel" aria-labelledby="calc-next-title">
                    <h2 class="calc-aside__title" id="calc-next-title">Next steps</h2>
                    <nav class="calc-aside__nav" aria-label="BrokersCourt links">
                        <a href="{{ route('broker.comparison') }}" class="calc-aside__link">
                            <i class="fas fa-balance-scale" aria-hidden="true"></i>
                            <span>Compare brokers</span>
                        </a>
                        <a href="{{ route('broker.alternatives.index') }}" class="calc-aside__link">
                            <i class="fas fa-random" aria-hidden="true"></i>
                            <span>Broker alternatives</span>
                        </a>
                        <a href="{{ route('brokers.best.index') }}" class="calc-aside__link">
                            <i class="fas fa-star" aria-hidden="true"></i>
                            <span>Best broker guides</span>
                        </a>
                        @if($toolKey !== 'cost')
                            <a href="{{ route('calculators.show', ['slug' => 'trading-cost-calculator']) }}" class="calc-aside__link">
                                <i class="fas fa-file-invoice-dollar" aria-hidden="true"></i>
                                <span>Trading cost calculator</span>
                            </a>
                        @endif
                    </nav>
                </section>

                @if($relatedTools->isNotEmpty())
                    <section class="calc-aside__panel" aria-labelledby="calc-more-title">
                        <h2 class="calc-aside__title" id="calc-more-title">Related calculators</h2>
                        <nav class="calc-aside__nav" aria-label="Related calculators">
                            @foreach($relatedTools as $item)
                                <a href="{{ $item->public_url }}" class="calc-aside__link">
                                    <i class="{{ $item->icon ?? 'fas fa-calculator' }}" aria-hidden="true"></i>
                                    <span>{{ $item->name }}</span>
                                </a>
                            @endforeach
                        </nav>
                        <a href="{{ route('calculators.index') }}" class="calc-aside__back">← All trading tools</a>
                    </section>
                @elseif($calculators->count() > 1)
                    <section class="calc-aside__panel" aria-labelledby="calc-more-title">
                        <h2 class="calc-aside__title" id="calc-more-title">More calculators</h2>
                        <nav class="calc-aside__nav" aria-label="Related calculators">
                            @foreach($calculators->where('slug', '!=', $calculator->slug)->take(6) as $item)
                                <a href="{{ $item->public_url }}" class="calc-aside__link">
                                    <i class="{{ $item->icon ?? 'fas fa-calculator' }}" aria-hidden="true"></i>
                                    <span>{{ $item->name }}</span>
                                </a>
                            @endforeach
                        </nav>
                        <a href="{{ route('calculators.index') }}" class="calc-aside__back">← All trading tools</a>
                    </section>
                @endif
            </aside>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/trading-tools.js') }}?v=5" defer></script>
@endpush
