@extends('front.layout.app')

@section('title', 'Trading Tools Dashboard | BrokersCourt')
@section('meta_description', 'Free forex trading tools dashboard: pip, position size, profit/loss, margin, risk, pivot points, Fibonacci and currency converter — calculate results instantly.')

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/trading-tools.css') }}?v=6">
@endpush

@section('main_content')
@php
    $activeSlug = request('tool', optional($tools->first())->slug ?? 'pip');
    $toolCount = $tools->count();
@endphp

<div class="tt-page">
    <header class="tt-hero">
        <div class="tt-hero__bg" aria-hidden="true"></div>
        <div class="tt-wrap">
            <nav class="tt-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <span>Trading tools</span>
            </nav>

            <p class="tt-hero__eyebrow">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25V12zm0 2.25h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25V18zm2.498-6.75h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V12zm0 2.25h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V18zm2.504-6.75h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V12zm0 2.25h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V18z"/>
                </svg>
                Free forex calculators
            </p>
            <h1 class="tt-hero__title">Trading <span class="tt-hero__accent">tools</span> dashboard</h1>
            <p class="tt-hero__subtitle">
                All calculators in one place — pip value, position sizing, margin, risk/reward, pivot points,
                Fibonacci levels, and currency conversion. Pick a tool, enter your values, and see results instantly.
            </p>

        </div>
    </header>

    <div class="tt-body">
        <div class="tt-wrap">
            <div class="tt-dashboard"
                 id="toolsDashboard"
                 data-calc-url="{{ route('trading.tools.calculate') }}"
                 data-rates='@json($rates)'>

                <nav class="tt-tabs" aria-label="Calculator tools">
                    <div class="tt-tabs__scroll" role="tablist" id="toolTabs">
                        @foreach($tools as $tool)
                            <button type="button"
                                    class="tt-tab {{ $tool->slug === $activeSlug ? 'is-active' : '' }}"
                                    data-tool="{{ $tool->slug }}"
                                    role="tab"
                                    aria-selected="{{ $tool->slug === $activeSlug ? 'true' : 'false' }}">
                                <i class="{{ $tool->icon }}" aria-hidden="true"></i>
                                <span>{{ $tool->name }}</span>
                            </button>
                        @endforeach
                    </div>
                </nav>

                <div class="tt-panels">
                    @foreach($tools as $tool)
                        <div class="tt-panel {{ $tool->slug === $activeSlug ? '' : 'is-hidden' }}"
                             data-panel="{{ $tool->slug }}"
                             role="tabpanel">

                            <div class="tt-panel__head">
                                <h2 class="tt-panel__title">{{ $tool->name }}</h2>
                                <p class="tt-panel__desc">{{ $tool->short_description }}</p>
                            </div>

                            <div class="tt-panel__grid">
                                <div class="tt-panel__inputs">
                                    @include('front.pages.partials.tools.' . $tool->slug, [
                                        'pairs' => $pairs,
                                        'currencies' => $currencies,
                                    ])

                                    <button type="button"
                                            class="tt-calc-btn"
                                            data-calc="{{ $tool->slug }}">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-10.5M3.75 13.5H9m-5.25 0V8"/>
                                        </svg>
                                        Calculate
                                    </button>
                                </div>

                                <aside class="tt-results-panel" aria-live="polite">
                                    <div class="tt-results-panel__head">
                                        <h3 class="tt-results-panel__title">Results</h3>
                                        <span class="tt-status" data-status="{{ $tool->slug }}">Ready</span>
                                    </div>
                                    <div class="tt-results" data-results="{{ $tool->slug }}">
                                        <p class="tt-results__placeholder">Enter values and click Calculate to see results here.</p>
                                    </div>
                                </aside>
                            </div>
                        </div>
                    @endforeach
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
<script src="{{ asset('js/trading-tools.js') }}?v=1" defer></script>
@endpush
