@extends('front.layout.app')

@section('title', ($meta['title'] ?? $tool->name) . ' | Trading Tools | BrokersCourt')
@section('meta_description', $meta['meta'] ?? $tool->short_description)
@section('canonical', route('trading.tools.show', ['slug' => $slug]))

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/trading-tools.css') }}?v=2">
@endpush

@section('main_content')
<div class="tt-page">
    <header class="tt-hero tt-hero--compact">
        <div class="tt-hero__bg" aria-hidden="true"></div>
        <div class="tt-wrap">
            <nav class="tt-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <a href="{{ route('trading.tools') }}">Trading tools</a>
                <span aria-hidden="true">/</span>
                <span>{{ $meta['title'] ?? $tool->name }}</span>
            </nav>

            <p class="tt-hero__eyebrow">
                <i class="{{ $tool->icon }}" aria-hidden="true"></i>
                Forex calculator
            </p>
            <h1 class="tt-hero__title">{{ $meta['title'] ?? $tool->name }}</h1>
            <p class="tt-hero__subtitle">{{ $meta['about'] ?? $tool->short_description }}</p>
        </div>
    </header>

    <div class="tt-body">
        <div class="tt-wrap">
            <div class="tt-layout">
                @include('front.trading-tools.partials.sidebar', [
                    'tools' => $tools,
                    'activeSlug' => $toolKey,
                ])

                <div class="tt-tool"
                     id="toolsDashboard"
                     data-calc-url="{{ route('trading.tools.calculate') }}"
                     data-rates='@json($rates)'>

                    <div class="tt-tool__panel">
                        <div class="tt-panel__grid">
                            <div class="tt-panel__inputs">
                                @include('front.pages.partials.tools.' . $toolKey, [
                                    'pairs' => $pairs,
                                    'currencies' => $currencies,
                                ])

                                <button type="button"
                                        class="tt-calc-btn"
                                        data-calc="{{ $toolKey }}">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-10.5M3.75 13.5H9m-5.25 0V8"/>
                                    </svg>
                                    Calculate
                                </button>
                            </div>

                            <aside class="tt-results-panel" aria-live="polite">
                                <div class="tt-results-panel__head">
                                    <h2 class="tt-results-panel__title">Results</h2>
                                    <span class="tt-status" data-status="{{ $toolKey }}">Ready</span>
                                </div>
                                <div class="tt-results" data-results="{{ $toolKey }}">
                                    <p class="tt-results__placeholder">Enter values and click Calculate to see results here.</p>
                                </div>
                            </aside>
                        </div>
                    </div>
                </div>
            </div>

            <p class="tt-disclaimer">
                Tools use standard forex formulas with reference FX rates for planning. They are educational and do not
                constitute trading advice. Always verify with your broker’s contract specifications.
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/trading-tools.js') }}?v=2"></script>
@endpush
