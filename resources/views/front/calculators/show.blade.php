@extends('front.layout.app')

@section('title', $calculator->name . ' | Forex Calculators | BrokersCourt')
@section('meta_description', $calculator->short_description ?: ($meta['meta'] ?? ''))
@section('canonical', route('calculators.show', ['slug' => $slug]))

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/calculators.css') }}?v=5">
@endpush

@section('main_content')
@php
    $aboutText = trim((string) ($calculator->description ?: ($meta['about'] ?? '')));
@endphp
<div class="calc-page calc-page--detail">
    <header class="calc-hero calc-hero--detail">
        <div class="container">
            <nav class="calc-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <a href="{{ route('calculators.index') }}">Forex calculators</a>
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
                    @if(trim((string) ($calculator->short_description ?? '')) !== '')
                        <p class="calc-detail__subtitle">{{ $calculator->short_description }}</p>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <div class="container calc-main calc-main--detail">
        <div class="calc-detail__layout">
            <div class="calc-detail__workspace">
                <div class="calc-detail__card calc-tool"
                     id="toolsDashboard"
                     data-calc-url="{{ route('calculators.calculate') }}"
                     data-rates='@json($rates)'>
                    <div class="calc-detail__panes">
                        <div class="calc-detail__pane calc-detail__pane--inputs">
                            <h2 class="calc-detail__pane-title">Inputs</h2>
                            <div class="calc-detail__fields">
                                @include('front.pages.partials.tools.' . $toolKey, [
                                    'pairs' => $pairs,
                                    'currencies' => $currencies,
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
            </div>

            <aside class="calc-detail__aside" aria-label="Calculator information">
                <section class="calc-aside__panel" aria-labelledby="calc-about-title">
                    <h2 class="calc-aside__title" id="calc-about-title">About this calculator</h2>
                    @if($aboutText !== '')
                        <div class="calc-aside__prose">
                            {!! nl2br(e($aboutText)) !!}
                        </div>
                    @else
                        <p class="calc-aside__text">Use this tool to plan your trade with clearer numbers before you execute.</p>
                    @endif
                </section>

                @if($calculators->count() > 1)
                    <section class="calc-aside__panel" aria-labelledby="calc-more-title">
                        <h2 class="calc-aside__title" id="calc-more-title">More calculators</h2>
                        <nav class="calc-aside__nav" aria-label="Related calculators">
                            @foreach($calculators->where('slug', '!=', $calculator->slug)->take(6) as $item)
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
            </aside>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/trading-tools.js') }}?v=3" defer></script>
@endpush
