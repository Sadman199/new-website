@extends('front.layout.app')

@section('title', 'Top Forex Brokers ' . date('Y') . ' | BrokersCourt')
@section('meta_description', 'Discover top forex brokers that match your trading needs. Compare spreads, deposits, leverage, regulation, and ratings from verified broker data.')
@section('canonical', route('brokers.top.index'))

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/homepage.css') }}?v=63">
    <link rel="stylesheet" href="{{ asset('css/top-brokers-index.css') }}?v=6">
@endpush

@section('main_content')
@php
    $brokerTotalLabel = ($totalBrokers ?? 0) >= 50
        ? '50+ brokers'
        : number_format($totalBrokers ?? 0) . ' ' . \Illuminate\Support\Str::plural('broker', $totalBrokers ?? 0);
    $awardYear = date('Y');
@endphp
<div class="tbk-page" id="tbkApp">
    <header class="tbk-hero">
        <div class="container">
            <nav class="tbk-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <span>Top brokers</span>
            </nav>

            <p class="tbk-hero__eyebrow">Independent broker research</p>
            <h1 class="tbk-hero__title">Top forex <span class="tbk-hero__accent">brokers</span></h1>
            <p class="tbk-hero__subtitle">Compare {{ $brokerTotalLabel }} using verified spreads, deposits, leverage, and regulation data. {{ $updatedLabel }}.</p>
        </div>
    </header>

    <div class="container tbk-main">
        <div class="tbk-quick-filters" role="toolbar" aria-label="Quick filters">
            @foreach($quickFilters as $filter)
                <button type="button"
                        class="tbk-chip"
                        data-tbk-filter="{{ $filter['key'] }}"
                        aria-pressed="false">
                    {{ $filter['label'] }}
                </button>
            @endforeach
        </div>

        <section class="tbk-directory" aria-labelledby="tbkDirectoryTitle">
            <div class="tbk-directory__head">
                <h2 class="tbk-directory__title" id="tbkDirectoryTitle">Top forex brokers</h2>
                <div class="tbk-directory__tools">
                    <label class="tbk-directory__search-wrap">
                        <span class="sr-only">Search brokers</span>
                        <svg class="tbk-directory__search-icon" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd"/>
                        </svg>
                        <input type="search"
                               id="tbkSearch"
                               class="tbk-directory__search"
                               placeholder="Search broker"
                               autocomplete="off">
                    </label>
                    <div class="tbk-sort" role="group" aria-label="Sort brokers">
                        <span class="tbk-sort__label">Sort:</span>
                        <button type="button" class="tbk-sort__btn is-active" data-tbk-sort="overall">Overall</button>
                        <button type="button" class="tbk-sort__btn" data-tbk-sort="low-cost">Low cost</button>
                        <button type="button" class="tbk-sort__btn" data-tbk-sort="rating">Rating</button>
                    </div>
                </div>
            </div>

            <p class="tbk-results-meta" id="tbkResultsMeta" aria-live="polite"></p>

            <div class="tbk-compare-grid" id="tbkBrokerGrid">
                @foreach($brokers as $index => $broker)
                    @include('front.brokers.partials.top_broker_compare_card', [
                        'broker' => $broker,
                        'rank' => $index + 1,
                    ])
                @endforeach
            </div>

            <div class="tbk-load-more" id="tbkLoadMoreWrap">
                <button type="button" class="tbk-load-more__button is-hidden" id="tbkLoadMore">
                    <span id="tbkLoadMoreLabel">Load more brokers</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m0 0 6-6m-6 6-6-6"/>
                    </svg>
                </button>
            </div>

            <div class="tbk-empty is-hidden" id="tbkEmptyState">
                <p>No brokers match your search or filters. Try clearing your selections.</p>
            </div>
        </section>

        @if(!empty($awardCategories))
            <section class="tbk-award-spotlight" aria-label="Award categories">
                <a href="{{ route('awards.index') }}" class="tbk-award-spotlight__card">
                    <span class="tbk-award-spotlight__glow" aria-hidden="true"></span>

                    <div class="tbk-award-spotlight__top">
                        <span class="tbk-award-spotlight__icon" aria-hidden="true"><i class="fas fa-trophy"></i></span>
                        <div class="tbk-award-spotlight__heading">
                            <p class="tbk-award-spotlight__eyebrow">BrokersCourt Awards {{ $awardYear }}</p>
                            <h2 class="tbk-award-spotlight__title">Award categories</h2>
                        </div>
                        <span class="tbk-award-spotlight__count">{{ count($awardCategories) }} {{ \Illuminate\Support\Str::plural('category', count($awardCategories)) }}</span>
                    </div>

                    <p class="tbk-award-spotlight__desc">Explore editorial award categories built from live broker data — regulation, ratings, trading conditions, and verified reviews.</p>

                    <ul class="tbk-award-spotlight__tags" aria-hidden="true">
                        @foreach(array_slice($awardCategories, 0, 6) as $award)
                            <li>{{ $award['name'] }}</li>
                        @endforeach
                    </ul>

                    <span class="tbk-award-spotlight__cta">
                        Explore award categories
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                    </span>
                </a>
            </section>
        @endif

        @if(!empty($brokerCategories))
            <section class="tbk-categories" aria-labelledby="tbkCategoriesTitle">
                <div class="tbk-section-head">
                    <div>
                        <p class="tbk-section-eyebrow">Broker guides</p>
                        <h2 class="tbk-section-title" id="tbkCategoriesTitle">Best brokers by categories</h2>
                    </div>
                    <a href="{{ route('brokers.best.index') }}" class="tbk-section-link">
                        All best broker lists
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                    </a>
                </div>

                <div class="tbk-categories__grid">
                    @foreach($brokerCategories as $category)
                        <a href="{{ $category['url'] }}" class="tbk-category-chip" @if(!empty($category['description'])) title="{{ $category['description'] }}" @endif>
                            <span class="tbk-category-chip__icon" aria-hidden="true"><i class="fas {{ $category['icon'] }}"></i></span>
                            <span class="tbk-category-chip__body">
                                <span class="tbk-category-chip__title">{{ $category['title'] }}</span>
                                <span class="tbk-category-chip__meta">{{ number_format($category['broker_count']) }} {{ \Illuminate\Support\Str::plural('broker', $category['broker_count']) }}</span>
                            </span>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        @if(!empty($regulationTabs))
            <section class="tbk-regulation" aria-labelledby="tbkRegulationTitle">
                <div class="tbk-section-head">
                    <div>
                        <p class="tbk-section-eyebrow">Verified regulation</p>
                        <h2 class="tbk-section-title" id="tbkRegulationTitle">Browse brokers by regulation</h2>
                    </div>
                    <a href="{{ route('regulated_brokers') }}" class="tbk-section-link">
                        All regulated brokers
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                    </a>
                </div>

                <div class="tbk-regulation__tabsbar">
                    <button type="button" class="tbk-regulation__nav tbk-regulation__nav--prev" id="tbkRegPrev" aria-label="Scroll regulations left" tabindex="-1">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    </button>

                    <div class="tbk-regulation__tabs" role="tablist" aria-label="Regulatory bodies" id="tbkRegTabsScroll">
                        @foreach($regulationTabs as $i => $tab)
                            <button type="button"
                                    @class(['tbk-regulation__tab', 'is-active' => $i === 0])
                                    role="tab"
                                    aria-selected="{{ $i === 0 ? 'true' : 'false' }}"
                                    data-tbk-reg-tab="{{ $tab['slug'] }}">
                                {{ $tab['label'] }}
                                <span class="tbk-reg-tab__count">{{ $tab['broker_count'] }}</span>
                            </button>
                        @endforeach
                    </div>

                    <button type="button" class="tbk-regulation__nav tbk-regulation__nav--next" id="tbkRegNext" aria-label="Scroll regulations right">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>

                @foreach($regulationTabs as $i => $tab)
                    <div @class(['bc-picks__panel', 'is-active' => $i === 0])
                         data-tbk-reg-panel="{{ $tab['slug'] }}"
                         role="tabpanel"
                         @if($i !== 0) hidden @endif>
                        <div class="tbk-compare-grid tbk-regulation__grid">
                            @foreach($tab['brokers'] as $broker)
                                @include('front.brokers.partials.top_broker_compare_card', ['broker' => $broker])
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </section>
        @endif

        @if(!empty($editorPicks))
            <section class="tbk-editorial" aria-labelledby="tbkPicksTitle">
                <div class="tbk-section-head">
                    <div>
                        <p class="tbk-section-eyebrow">Editor&rsquo;s spotlight</p>
                        <h2 class="tbk-section-title" id="tbkPicksTitle">Our editor&rsquo;s picks</h2>
                    </div>
                </div>

                <div class="tbk-editorial__list">
                    @foreach($editorPicks as $index => $pick)
                        @php $broker = $pick['broker'] ?? null; @endphp
                        @if($broker)
                            <article class="tbk-editorial__row">
                                <span class="tbk-editorial__num" aria-hidden="true">{{ sprintf('%02d', $index + 1) }}</span>

                                <div class="tbk-editorial__brand">
                                    <a href="{{ $broker['review_url'] }}" class="tbk-editorial__logo" tabindex="-1" aria-hidden="true">
                                        @if($broker['logo'] ?? null)
                                            <img src="{{ $broker['logo'] }}" alt="" loading="lazy" decoding="async">
                                        @else
                                            <span class="tbk-editorial__logo-fallback">{{ strtoupper(substr($broker['name'], 0, 1)) }}</span>
                                        @endif
                                    </a>
                                    <div class="tbk-editorial__identity">
                                        <span class="tbk-editorial__label">{{ $pick['label'] }}</span>
                                        <a href="{{ $broker['review_url'] }}" class="tbk-editorial__name">{{ $broker['name'] }}</a>
                                    </div>
                                </div>

                                <p class="tbk-editorial__quote">
                                    &ldquo;{{ \Illuminate\Support\Str::limit(strip_tags($broker['short_description'] ?? $broker['top_feature'] ?? 'A well-rounded broker for this category.'), 110) }}&rdquo;
                                </p>

                                @if($broker['rating'] ?? null)
                                    <div class="tbk-editorial__score" aria-label="Editorial score {{ $broker['rating'] }} out of 5">
                                        <strong>{{ number_format($broker['rating'], 1) }}</strong>
                                        <span>/5</span>
                                    </div>
                                @endif

                                <a href="{{ $broker['review_url'] }}" class="tbk-editorial__cta">
                                    Read review
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                                </a>
                            </article>
                        @endif
                    @endforeach
                </div>
            </section>
        @endif

        <section class="tbk-cta" aria-labelledby="tbkCtaTitle">
            <h2 class="tbk-cta__title" id="tbkCtaTitle">Still not sure?</h2>
            <div class="tbk-cta__actions">
                <a href="{{ route('find_my_broker') }}" class="bc-btn bc-btn--primary tbk-cta__btn">Find your broker</a>
                <a href="{{ route('broker.comparison') }}" class="bc-btn-secondary tbk-cta__btn">Compare brokers</a>
            </div>
        </section>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/top-brokers-index.js') }}?v=6" defer></script>
@endpush
