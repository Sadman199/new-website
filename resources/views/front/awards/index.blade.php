@extends('front.layout.app')

@section('title', 'Broker Awards ' . date('Y') . ' | BrokersCourt')
@section('meta_description', 'Explore BrokersCourt award categories and discover top-rated forex brokers recognized for trust, execution, spreads, and platform quality.')

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/awards-index.css') }}?v=4">
@endpush

@section('main_content')
<div class="awd-page">
    <header class="awd-hero">
        <div class="awd-hero__bg" aria-hidden="true"></div>

        <div class="awd-wrap">
            <nav class="awd-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <span>Broker awards</span>
            </nav>

            <div class="awd-hero__inner">
                <div class="awd-hero__copy">
                    <p class="awd-hero__eyebrow">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M18.75 4.236c.982.143 1.954.317 2.916.52A6.003 6.003 0 0116.27 9.728M18.75 4.236V4.5c0 2.108-.966 3.99-2.48 5.228m0 0a6.003 6.003 0 01-2.48 5.228"/>
                        </svg>
                        BrokersCourt Awards {{ date('Y') }}
                    </p>
                    <h1 class="awd-hero__title">The Hall of Fame for top brokers</h1>
                    <p class="awd-hero__subtitle">
                        Award categories are built from live broker data — regulation, ratings, trading conditions,
                        and verified reviews — so every list reflects real, current performance.
                    </p>
                </div>

                <div class="awd-hero__trophy" aria-hidden="true">
                    <div class="awd-hero__trophy-ring"></div>
                    <svg class="awd-hero__trophy-icon" viewBox="0 0 64 64" fill="none">
                        <path d="M32 8v6M22 14h20M24 20h16v8c0 6-3 11-8 13v9h8v4H24v-4h8v-9c-5-2-8-7-8-13v-8z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M18 20H14a4 4 0 004 4v2a6 6 0 01-6 6M46 20h4a4 4 0 01-4 4v2a6 6 0 006 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>
            </div>

            <div class="awd-hero__stats awd-hero__stats--compact awd-hero__stats--cols-4">
                <div class="awd-stat">
                    <span class="awd-stat__label">Brokers reviewed</span>
                    <span class="awd-stat__value">{{ number_format($stats['total_brokers']) }}</span>
                </div>
                <div class="awd-stat">
                    <span class="awd-stat__label">Featured picks</span>
                    <span class="awd-stat__value">{{ number_format($stats['featured_brokers']) }}</span>
                </div>
                <div class="awd-stat awd-stat--highlight">
                    <span class="awd-stat__label">Award categories</span>
                    <span class="awd-stat__value">{{ $stats['award_categories'] }}</span>
                </div>
                <div class="awd-stat">
                    <span class="awd-stat__label">Avg. rating /5</span>
                    <span class="awd-stat__value">{{ number_format($stats['average_rating'], 1) }}</span>
                </div>
            </div>
        </div>
    </header>

    <div class="awd-body">
        <div class="awd-wrap">
            <div class="awd-layout">
                <aside class="awd-sidebar" aria-label="Filter awards">
                    <div class="awd-sidebar__panel">
                        <h2 class="awd-sidebar__title">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                            </svg>
                            Find an award
                        </h2>
                        <div class="awd-sidebar__search-wrap">
                            <input type="search"
                                   id="awiSearchInput"
                                   class="awd-sidebar__search"
                                   placeholder="Search categories…"
                                   autocomplete="off"
                                   aria-label="Search award categories">
                        </div>

                        <div class="awd-sidebar__section">
                            <h3 class="awd-sidebar__subtitle">Quick links</h3>
                            <nav class="awd-sidebar__links">
                                <a href="{{ route('broker.reviews.index') }}" class="awd-sidebar__link">
                                    <span>All broker reviews</span>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                                </a>
                                <a href="{{ route('brokers.best.index') }}" class="awd-sidebar__link">
                                    <span>Best broker guides</span>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                                </a>
                                <a href="{{ route('regulated_brokers') }}" class="awd-sidebar__link">
                                    <span>Regulated brokers</span>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                                </a>
                            </nav>
                        </div>
                    </div>
                </aside>

                <div class="awd-main">
                    <div class="awd-main__head">
                        <div>
                            <p class="awd-main__eyebrow">Award categories</p>
                            <h2 class="awd-main__heading">Explore the winners</h2>
                        </div>
                        <p class="awd-results-count" id="awiResultsCount">
                            {{ count($awardCards) }} {{ \Illuminate\Support\Str::plural('category', count($awardCards)) }}
                        </p>
                    </div>

                    @if(count($awardCards))
                        <ul class="awd-grid" id="awiAwardGrid">
                            @foreach($awardCards as $index => $award)
                                @include('front.awards.partials.award_card', [
                                    'award' => $award,
                                    'index' => $index,
                                ])
                            @endforeach
                        </ul>
                    @endif

                    <div class="awd-empty {{ count($awardCards) ? 'is-hidden' : '' }}" id="awiEmptyState">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                        </svg>
                        <p>No award categories match your search.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="awd-methodology-wrap" aria-labelledby="awdMethodologyTitle">
    <div class="awd-wrap">
        <div class="awd-methodology">
            <div class="awd-methodology__head">
                <p class="awd-methodology__eyebrow">Our process</p>
                <h2 class="awd-methodology__title" id="awdMethodologyTitle">How we evaluate brokers</h2>
                <p class="awd-methodology__lead">Every award category is scored against four independent pillars before a broker earns a place on the list.</p>
            </div>
            <div class="awd-methodology__grid">
                @foreach($evaluationPillars as $pillarIndex => $pillar)
                    <article class="awd-pillar">
                        <span class="awd-pillar__num">{{ str_pad($pillarIndex + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <h3 class="awd-pillar__title">{{ $pillar['title'] }}</h3>
                        <p class="awd-pillar__text">{{ $pillar['description'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="{{ asset('js/awards-index.js') }}?v=2"></script>
@endpush
