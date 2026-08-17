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
    <link rel="stylesheet" href="{{ asset('css/promotions-index.css') }}?v=23">
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

            <div class="bpr-hero__grid">
                <div class="bpr-hero__intro">
                    <p class="bpr-hero__eyebrow">Live broker offers</p>
                    <h1 class="bpr-hero__title">Broker <span class="bpr-hero__accent">promos</span></h1>
                    <p class="bpr-hero__subtitle">
                        Deposit bonuses, contests, and cashback from regulated brokers — verified against expiry
                        dates and refreshed from our promotions database.
                    </p>
                </div>

                <dl class="bpr-hero__stats">
                    <div>
                        <dt>Active offers</dt>
                        <dd>{{ number_format($stats['total_active'] ?? 0) }}</dd>
                    </div>
                    <div>
                        <dt>Brokers</dt>
                        <dd>{{ number_format($stats['total_brokers'] ?? 0) }}</dd>
                    </div>
                    <div>
                        <dt>Ending soon</dt>
                        <dd>{{ number_format($stats['ending_soon'] ?? 0) }}</dd>
                    </div>
                    <div>
                        <dt>Updated</dt>
                        <dd>{{ $refreshedAt ?? now()->format('M j, Y') }}</dd>
                    </div>
                </dl>
            </div>

            @include('front.brokers.partials.country_context_hero', [
                'eyebrow' => 'Viewing offers for your region',
                'title'   => 'Offers available in {country}',
            ])
        </div>
    </header>

    <div class="container">
        <section class="bpr-board" id="current-promotions" aria-labelledby="bprBoardTitle">
            <div class="bpr-board__head">
                <div>
                    <p class="bpr-section__eyebrow">{{ $isFullBoard ? 'Live promotions board' : 'Currently viewing' }}</p>
                    <h2 class="bpr-board__title" id="bprBoardTitle">
                        {{ $isFullBoard ? 'Current Promotions Available on BrokersCourt' : $activeTabName }}
                    </h2>
                </div>
                <p class="bpr-board__count">
                    <span id="bpr-showing-count">{{ $loadedCount }}</span> of {{ $totalCount }} {{ \Illuminate\Support\Str::plural('offer', $totalCount) }}
                </p>
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

            <p class="bpr-method-note" role="note">
                <strong>How we list promotions:</strong>
                active offers are matched to broker profiles, checked against expiry dates, and refreshed from our
                promotions database.
            </p>
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
