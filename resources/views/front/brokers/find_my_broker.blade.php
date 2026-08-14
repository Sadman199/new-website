@extends('front.layout.app')

@section('title', $seoTitle)
@section('meta_description', $seoDescription)
@section('canonical', $canonicalUrl)
@section('robots', $robots ?? 'index, follow')

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/find-my-broker.css') }}?v=6">
@endpush

@section('main_content')
<div class="fmb-page">
    <header class="fmb-hero">
        <div class="container">
            <nav class="fmb-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <span>Find my broker</span>
            </nav>

            <p class="fmb-hero__eyebrow">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Smart broker matching
            </p>
            @if(!empty($fromQuiz))
                <h1 class="fmb-hero__title">Your <span class="fmb-hero__accent">BrokerMatch</span> results</h1>
                <p class="fmb-hero__subtitle">Brokers ranked from your quiz answers. Visit, read the review, save, or compare — or retake the quiz to refine the match.</p>
            @else
                <h1 class="fmb-hero__title">Find my <span class="fmb-hero__accent">broker</span></h1>
                <p class="fmb-hero__subtitle">Filter {{ number_format($pageStats['total']) }}+ reviewed brokers by deposit, regulation, platform, costs, and features — updated live from our database. Prefer questions? <a href="{{ route('home') }}#bcMatchQuiz" class="fmb-hero__quiz-link">Take the BrokerMatch quiz</a>.</p>
            @endif

            @include('front.brokers.partials.country_context_hero')

        </div>
    </header>

    <div class="container" id="fmb-app" data-endpoint="{{ route('find_my_broker') }}" data-compare-base="{{ url('/brokers/compare') }}">
        @if(!empty($quickPresets))
            <section class="fmb-presets" aria-label="Popular searches">
                <p class="fmb-presets__label">Popular searches</p>
                <div class="fmb-presets__grid">
                    @foreach($quickPresets as $preset)
                        <a href="{{ $preset['url'] }}" class="fmb-preset">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            {{ $preset['label'] }}
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        <div class="row g-4">
            <aside class="col-12 col-lg-3 fmb-filters fmb-filters--desktop" aria-label="Filter brokers">
                <div class="fmb-filters__shell">
                    <div class="fmb-filters__head">
                        <h2 class="fmb-filters__title">Refine results</h2>
                        <button type="button" class="fmb-filters__reset fmb-reset">Reset</button>
                    </div>
                    <div class="fmb-filters__body">
                        @include('front.brokers.partials.find_my_broker_filters', ['idPrefix' => 'desk'])
                    </div>
                </div>
            </aside>

            <div class="col-12 col-lg-9 fmb-main">
                <div class="fmb-mobile-bar">
                    <button type="button" class="fmb-mobile-filter-btn" id="fmb-open-filters">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h18M6 12h12M10 20h4"/>
                        </svg>
                        Filters
                    </button>
                </div>

                <div id="fmb-results" class="fmb-results">
                    @include('front.brokers.partials.find_my_broker_results')
                </div>
            </div>
        </div>

        <section class="fmb-cta" aria-label="More tools">
            <div class="fmb-cta__inner">
                <div>
                    <h2 class="fmb-cta__title">Need a second opinion?</h2>
                    <p class="fmb-cta__text">Compare brokers side by side, read in-depth reviews, or run a safety check before you deposit.</p>
                </div>
                <div class="fmb-cta__actions">
                    <a href="{{ route('broker.comparison') }}" class="fmb-btn fmb-btn--ghost">Compare brokers</a>
                    <a href="{{ route('broker.scam_checker') }}" class="fmb-btn fmb-btn--primary">Scam checker</a>
                </div>
            </div>
        </section>
    </div>
</div>

<div class="fmb-drawer" id="fmb-drawer" aria-hidden="true">
    <div class="fmb-drawer__backdrop" id="fmb-close-filters"></div>
    <div class="fmb-drawer__panel">
        <div class="fmb-drawer__head">
            <h2 class="fmb-drawer__title">Filters</h2>
            <button type="button" class="fmb-drawer__close" id="fmb-close-filters-btn" aria-label="Close filters">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="fmb-drawer__body">
            @include('front.brokers.partials.find_my_broker_filters', ['idPrefix' => 'mob'])
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/find-my-broker.js') }}?v=5" defer></script>
@endpush
