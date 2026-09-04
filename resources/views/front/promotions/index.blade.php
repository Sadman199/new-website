@extends('front.layout.app')

@section('title', 'Forex Broker Bonuses & Promotions | BrokersCourt')
@section('meta_description', 'Browse live forex broker bonuses and promotions from our database — deposit bonuses, no-deposit offers, contests, cashback, and crypto deals.')
@section('canonical', ($activeTab ?? 'all') === 'all' ? route('promotions.index') : route('promotions.tab', ['type' => $activeTab]))

@push('json_ld')
    @isset($promoJsonLd)
        <script type="application/ld+json">@json($promoJsonLd)</script>
    @endisset
@endpush

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/promotions-index.css') }}?v=34">
@endpush

@section('main_content')
@php
    $stats = $stats ?? [];
    $activeOffers = (int) ($stats['total_active'] ?? 0);
    $activeBrokers = (int) ($stats['total_brokers'] ?? 0);
@endphp
<div class="bpr-page" id="bpr-app"
     data-active-tab="{{ $activeTab }}"
     data-active-sort="{{ $activeSort }}"
     data-featured-only="{{ $featuredOnly ? '1' : '0' }}"
     data-search="{{ $search ?? '' }}">
    <header class="bpr-hero">
        <div class="container">
            <nav class="bpr-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <span>Broker bonuses</span>
            </nav>

            <div class="bpr-hero__grid">
                <div class="bpr-hero__copy">
                    <p class="bpr-hero__eyebrow">
                        <span class="bpr-hero__eyebrow-dot" aria-hidden="true"></span>
                        Broker promotions
                    </p>
                    <h1 class="bpr-hero__title">Forex Broker <span class="bpr-hero__accent">Bonuses &amp; Promotions</span></h1>
                    @if($activeOffers > 0)
                        <p class="bpr-hero__lead">{{ number_format($activeOffers) }} active {{ \Illuminate\Support\Str::plural('offer', $activeOffers) }}{{ $activeBrokers > 0 ? ' from '.number_format($activeBrokers).' '.\Illuminate\Support\Str::plural('broker', $activeBrokers) : '' }} — compare deposit bonuses, contests, cashback, and more.</p>
                    @else
                        <p class="bpr-hero__lead">Compare deposit bonuses, contests, cashback, and crypto deals from our live promotions database.</p>
                    @endif
                    <ul class="bpr-hero__pills">
                        <li>Live catalog data</li>
                        <li>Deposit, contests, cashback</li>
                        <li>Terms on every card</li>
                    </ul>
                </div>
            </div>

            @include('front.brokers.partials.country_context_hero', [
                'eyebrow' => 'Viewing offers for your region',
                'title'   => 'Offers available in {country}',
            ])
        </div>
    </header>

    <div class="container bpr-main">
        @include('front.promotions.partials.promo_featured_row', [
            'featuredCards' => $featuredCards ?? collect(),
            'activeTab' => $activeTab,
            'activeSort' => $activeSort,
            'featuredOnly' => $featuredOnly,
            'search' => $search ?? null,
            'activeFilters' => $activeFilters ?? [],
        ])

        <section class="bpr-catalog" id="current-promotions" aria-label="Broker promotions">
            <div class="bpr-catalog__intro">
                <p class="bpr-kicker">Offer catalog</p>
                <h2 class="bpr-catalog__title">Browse live promotions</h2>
            </div>

            @include('front.promotions.partials.promo_toolbar')

            @if(collect($cards ?? [])->isNotEmpty())
                @include('front.promotions.partials.promo_grid', [
                    'cards' => $cards,
                    'activeTab' => $activeTab,
                    'activeSort' => $activeSort,
                    'featuredOnly' => $featuredOnly,
                    'search' => $search ?? null,
                    'activeFilters' => $activeFilters ?? [],
                    'loadedCount' => $loadedCount,
                    'totalCount' => $totalCount,
                    'hasMore' => $hasMore,
                ])
            @else
                <div class="bpr-empty">
                    <h2 class="bpr-empty__title">No promotions match your filters</h2>
                    <p class="bpr-empty__text">Adjust your search or reset filters to browse all active offers.</p>
                    <a href="{{ route('promotions.index') }}" class="bc-btn bc-btn--primary">View all promotions</a>
                </div>
            @endif
        </section>

        <x-broker-slider
            :brokers="$topRatedBrokers ?? collect()"
            section-id="top-rated-brokers"
            title="Top Rated Brokers"
            lead="Highly rated brokers to pair with the promotions above."
            :view-all-url="route('broker.reviews.index')"
            :compact="true"
            class="bpr-brokers" />

        @include('front.homepage.inc.trust_cta')
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/promotions-index.js') }}?v=11" defer></script>
@endpush
