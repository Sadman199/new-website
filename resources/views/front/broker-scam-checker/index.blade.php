@extends('front.layout.app')

@php
    $pageTitle = $pageTitle ?? 'Broker Scam Checker — Is Your Broker Safe? | BrokersCourt';
    $metaDescription = $metaDescription ?? 'Verify broker regulation, trust score, risk indicators and safety history before you deposit. Free broker safety check powered by BrokersCourt.';
@endphp

@section('title', $pageTitle)
@section('meta_description', $metaDescription)
@section('canonical', route('broker.scam_checker'))

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/broker-scam-checker.css') }}?v=6">
@endpush

@section('main_content')
<div class="bsc-page" id="bscApp"
     data-search-url="{{ route('broker.scam_checker.search') }}"
     data-compare-url="{{ route('broker.scam_checker.compare') }}"
     data-report-url="{{ route('broker.scam_checker.report') }}"
     data-checker-url="{{ route('broker.scam_checker') }}"
     data-open-report="{{ session('open_report_modal') ? '1' : '0' }}"
     @if(!empty($analysis)) data-current-slug="{{ $analysis['broker']['slug'] }}" @endif>
    <div class="bsc-bg-grid" aria-hidden="true"></div>

    <section class="bsc-hero">
        <div class="bsc-container">
            <div class="bsc-hero__badge"><i class="fas fa-shield-alt"></i> Broker Safety Intelligence</div>
            <h1 class="bsc-hero__title">Is Your Broker Safe?</h1>
            <p class="bsc-hero__subtitle">Check broker regulation, trust score, risk indicators and safety history before investing.</p>

            @include('front.partials.safety_hub_nav', ['activeHub' => 'checker'])

            <form class="bsc-search" action="{{ route('broker.scam_checker') }}" method="get" id="bscSearchForm" autocomplete="off">
                <div class="bsc-search__wrap">
                    <i class="fas fa-search bsc-search__icon"></i>
                    <input type="search"
                           name="q"
                           id="bscSearchInput"
                           class="bsc-search__input"
                           placeholder="Search broker name..."
                           value="{{ $analysis['broker']['name'] ?? ($unknownQuery ?? '') }}"
                           aria-label="Search broker name">
                    <div class="bsc-search__dropdown bsc-hidden" id="bscSearchDropdown"></div>
                </div>
                <button type="submit" class="bsc-btn bsc-btn-primary bsc-search__btn">
                    <i class="fas fa-shield-alt"></i> Check Safety
                </button>
            </form>

            <div class="bsc-examples">
                <span>Try:</span>
                @foreach(($examples ?? collect(['XM', 'OneRoyal', 'IG Markets'])) as $example)
                    <button type="button" class="bsc-example-chip" data-bsc-example="{{ $example }}">{{ $example }}</button>
                @endforeach
            </div>

            <p class="bsc-trust-msg"><i class="fas fa-database"></i> Powered by BrokersCourt Broker Intelligence Database</p>
        </div>
    </section>

    <div class="bsc-container bsc-main">
        @if(!empty($unknownQuery))
            @include('front.broker-scam-checker.partials.unknown')
        @endif

        @if(!empty($analysis))
            @include('front.broker-scam-checker.partials.dashboard', ['analysis' => $analysis])
            @include('front.broker-scam-checker.partials.report-form', [
                'analysis' => $analysis,
                'issueTypes' => $issueTypes,
            ])
        @elseif(empty($unknownQuery))
            <section class="bsc-intro glass-card">
                <div class="bsc-intro__grid">
                    <div>
                        <h2 class="bsc-section-title">Advanced broker verification</h2>
                        <p class="bsc-muted">Our safety engine scores brokers using regulation data, trust metrics, company history, client protection features, and community reports.</p>
                        <ul class="bsc-feature-list">
                            <li><i class="fas fa-check-circle"></i> Regulation & license verification</li>
                            <li><i class="fas fa-check-circle"></i> Trust score & risk analysis</li>
                            <li><i class="fas fa-check-circle"></i> Side-by-side safety comparison</li>
                            <li><i class="fas fa-check-circle"></i> Community report monitoring</li>
                        </ul>
                    </div>
                    <div>
                        <div class="bsc-intro-meter" aria-hidden="true">
                            <div class="bsc-score-ring bsc-score-ring--demo" data-score="88">
                                <svg viewBox="0 0 120 120"><circle cx="60" cy="60" r="52" class="bsc-ring-bg"/><circle cx="60" cy="60" r="52" class="bsc-ring-fill"/></svg>
                                <div class="bsc-score-ring__value">88</div>
                            </div>
                            <p class="bsc-muted bsc-intro-meter__caption">Sample safety score preview</p>
                        </div>
                    </div>
                </div>
            </section>
        @endif
    </div>

    @include('front.broker-scam-checker.partials.compare-modal')
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/broker-scam-checker.js') }}?v=3" defer></script>
@endpush
