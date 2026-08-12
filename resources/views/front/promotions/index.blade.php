@extends('front.layout.app')

@section('title', 'Broker Promos — Bonuses, Contests & Cashback | BrokersCourt')
@section('meta_description', 'Browse live broker promotions: deposit bonuses, no-deposit offers, trading contests, cashback deals, and crypto contests — updated from our promotions database.')
@section('canonical', ($activeTab ?? 'all') === 'all' ? route('promotions.index') : route('promotions.tab', ['type' => $activeTab]))

@push('json_ld')
    @isset($promoJsonLd)
        <script type="application/ld+json">@json($promoJsonLd)</script>
    @endisset
@endpush

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/promotions-index.css') }}?v=14">
@endpush

@section('main_content')
<div class="bpr-page" id="bpr-app"
     data-active-tab="{{ $activeTab }}"
     data-active-sort="{{ $activeSort }}"
     data-featured-only="{{ $featuredOnly ? '1' : '0' }}">
    <header class="bpr-hero">
        <div class="bpr-wrap">
            <nav class="bpr-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <span>Broker promos</span>
            </nav>

            <p class="bpr-hero__eyebrow">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4H5z"/>
                </svg>
                Live broker offers
            </p>
            <h1 class="bpr-hero__title">Broker <span class="bpr-hero__accent">promos</span></h1>
            <p class="bpr-hero__subtitle">Deposit bonuses, contests, and cashback from regulated brokers — sorted and refreshed from our live database.</p>

            @include('front.brokers.partials.country_context_hero', [
                'variant' => 'inline',
                'eyebrow' => 'Viewing offers for your region',
                'title'   => 'Offers available in {country}',
            ])

            <p class="bpr-hero__updated">Last updated: {{ $refreshedAt ?? now()->format('M j, Y') }}</p>
        </div>
    </header>

    <div class="bpr-wrap">
        @include('front.promotions.partials.promo_toolbar')

        @if(!$featuredOnly && $activeTab === \App\Services\PromotionsIndexService::TAB_ALL)
            @include('front.promotions.partials.promo_featured_row')
        @endif

        <section class="bpr-section" aria-labelledby="bprSectionTitle">
            <div class="bpr-section__head">
                <div>
                    <p class="bpr-section__eyebrow">Currently viewing</p>
                    <h2 class="bpr-section__title" id="bprSectionTitle">{{ $activeTabName }}</h2>
                </div>
                <p class="bpr-section__count">
                    <span id="bpr-showing-count">{{ $loadedCount }}</span> of {{ $totalCount }} {{ \Illuminate\Support\Str::plural('promotion', $totalCount) }}
                </p>
            </div>

            @if($cards->isNotEmpty())
                @include('front.promotions.partials.promo_grid', [
                    'cards' => $cards,
                    'activeTab' => $activeTab,
                    'activeSort' => $activeSort,
                    'featuredOnly' => $featuredOnly,
                    'loadedCount' => $loadedCount,
                    'totalCount' => $totalCount,
                    'hasMore' => $hasMore,
                ])
            @else
                <div class="bpr-empty">
                    <div class="bpr-empty__icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4H5z"/>
                        </svg>
                    </div>
                    <h3 class="bpr-empty__title">No promotions match your filters</h3>
                    <p class="bpr-empty__text">Try another category or turn off featured-only.</p>
                    <a href="{{ route('promotions.index') }}" class="bpr-btn bpr-btn--primary">View all offers</a>
                </div>
            @endif
        </section>

        @include('front.promotions.partials.promo_faq', ['guide' => $guide])

        <section class="bpr-cta" aria-label="More tools">
            <div class="bpr-cta__inner">
                <div>
                    <h2 class="bpr-cta__title">Compare brokers before you claim a bonus</h2>
                    <p class="bpr-cta__text">Check regulation, fees, and safety scores before opening an account for any promotion.</p>
                </div>
                <div class="bpr-cta__actions">
                    <a href="{{ route('find_my_broker') }}" class="bpr-btn bpr-btn--ghost">Find my broker</a>
                    <a href="{{ route('broker.comparison') }}" class="bpr-btn bpr-btn--primary">Compare brokers</a>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/promotions-index.js') }}?v=5" defer></script>
@endpush
