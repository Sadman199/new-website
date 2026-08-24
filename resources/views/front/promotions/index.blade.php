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
    <link rel="stylesheet" href="{{ asset('css/promotions-index.css') }}?v=25">
@endpush

@php
    $isFullBoard = ! $featuredOnly && $activeTab === \App\Services\PromotionsIndexService::TAB_ALL;
@endphp

@section('main_content')
<div class="bpr-page" id="bpr-app"
     data-active-tab="{{ $activeTab }}"
     data-active-sort="{{ $activeSort }}"
     data-featured-only="{{ $featuredOnly ? '1' : '0' }}">
    <header class="bpr-hero">
        <div class="container">
            <nav class="bpr-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <span>Broker promos</span>
            </nav>

            <p class="bpr-hero__eyebrow">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 0 1-1.5 1.5H4.5a1.5 1.5 0 0 1-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 1 0 12 10.125 2.625 2.625 0 0 0 12 4.875Zm0 0V3.75m0 18.75v-1.5"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10.125A6.375 6.375 0 0 0 5.625 16.5h12.75A6.375 6.375 0 0 0 12 10.125Z"/>
                </svg>
                Live broker offers
            </p>
            <h1 class="bpr-hero__title">Broker <span class="bpr-hero__accent">promos</span></h1>
            <p class="bpr-hero__subtitle">
                Deposit bonuses, contests, and cashback from regulated brokers — verified against expiry dates
                and refreshed from our promotions database.
            </p>

            <ul class="bpr-hero__stats" aria-label="Promotions summary">
                <li>
                    <strong>{{ number_format($stats['total_active'] ?? 0) }}</strong>
                    <span>active offers</span>
                </li>
                @if(($stats['total_brokers'] ?? 0) > 0)
                    <li>
                        <strong>{{ number_format($stats['total_brokers']) }}</strong>
                        <span>brokers</span>
                    </li>
                @endif
                @if(($stats['ending_soon'] ?? 0) > 0)
                    <li>
                        <strong>{{ number_format($stats['ending_soon']) }}</strong>
                        <span>ending soon</span>
                    </li>
                @endif
                <li>
                    <strong>{{ $refreshedAt ?? now()->format('M j') }}</strong>
                    <span>last updated</span>
                </li>
                @if(($stats['featured'] ?? 0) > 0 && ($stats['total_brokers'] ?? 0) === 0)
                    <li>
                        <strong>{{ number_format($stats['featured']) }}</strong>
                        <span>editor’s picks</span>
                    </li>
                @endif
            </ul>

            @include('front.brokers.partials.country_context_hero', [
                'eyebrow' => 'Viewing offers for your region',
                'title'   => 'Offers available in {country}',
            ])
        </div>
    </header>

    <div class="container bpr-body">
        <section class="bpr-board" id="current-promotions" aria-labelledby="bprBoardTitle">
            <div class="bpr-board__head">
                <div>
                    <p class="bpr-section__eyebrow">Live listings</p>
                    <h2 class="bpr-board__title" id="bprBoardTitle">
                        {{ $isFullBoard ? 'Current promotions' : $activeTabName }}
                    </h2>
                    <p class="bpr-board__count">
                        Showing <span id="bpr-showing-count">{{ $loadedCount }}</span> of {{ $totalCount }} {{ \Illuminate\Support\Str::plural('offer', $totalCount) }}
                    </p>
                </div>
            </div>

            @include('front.promotions.partials.promo_toolbar')

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
                    <h3 class="bpr-empty__title">No promotions match your filters</h3>
                    <p class="bpr-empty__text">Try another category or turn off featured-only.</p>
                    <a href="{{ route('promotions.index') }}" class="bpr-btn bpr-btn--primary">View all offers</a>
                </div>
            @endif
        </section>

        <x-broker-slider
            :brokers="$topRatedBrokers ?? collect()"
            section-id="top-rated-brokers"
            eyebrow="Broker shortlist"
            title="Top Rated Brokers"
            lead="Check the broker behind an offer before you claim it — these are the highest-scoring regulated brokers in our database."
            :view-all-url="route('broker.reviews.index')"
            class="bpr-brokers" />

        @include('front.promotions.partials.guide_content', [
            'guide' => $guide,
            'stats' => $stats,
        ])

        <section class="bpr-cta" aria-label="More tools">
            <div class="bpr-cta__inner">
                <div>
                    <p class="bpr-cta__eyebrow">Before you claim</p>
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
<script src="{{ asset('js/promotions-index.js') }}?v=8" defer></script>
@endpush
