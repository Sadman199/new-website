@extends('front.layout.app')

@section('title', 'Best brokers for every need in ' . date('Y') . ' | BrokersCourt')
@section('meta_description', $pageLead ?? 'Compare regulated brokers with competitive fees, robust platforms, and transparent trading conditions.')
@section('canonical', route('brokers.best.index'))

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/broker-slider.css') }}?v=6">
    <link rel="stylesheet" href="{{ asset('css/best-brokers-index.css') }}?v=16">
@endpush

@section('main_content')
@php
    $popularLists = collect($toplists)->where('popular', true)->values();
    $allLists = collect($toplists)->values();
    $categoryLists = collect($toplists)->where('type', 'category');
    $countryLists = collect($toplists)->where('type', 'country');
    $countryListsCount = $countryLists->count();
    $countrySlug = $preferredCountry['slug'] ?? 'global';
    $countryName = $countrySlug === 'global' ? null : ($preferredCountry['name'] ?? null);
    $heroLead = $pageLead ?? 'Compare regulated brokers that offer competitive fees, robust platforms, and transparent trading conditions.';
    $categoryLabels = \App\Support\BrokerTaxonomy::categories();
    $quickGuides = $popularLists->where('type', 'category')->take(8);
    $year = date('Y');
@endphp
<div class="bbh-page">
    <header class="bbh-hero">
        <div class="container">
            <nav class="bbh-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <span>Best brokers</span>
            </nav>

            <p class="bbh-hero__eyebrow">Independent broker research</p>
            <h1 class="bbh-hero__title">
                Best brokers
                @if($countryName)
                    in <span class="bbh-hero__accent">{{ $countryName }}</span>
                @else
                    for <span class="bbh-hero__accent">every trader</span>
                @endif
            </h1>
            <p class="bbh-hero__subtitle">{{ $heroLead }}</p>

            @include('front.brokers.partials.country_context_hero')

            <ul class="bbh-hero__stats" aria-label="Broker list totals">
                <li>
                    <strong>{{ number_format($categoryLists->count()) }}</strong>
                    <span>category guides</span>
                </li>
                <li>
                    <strong>{{ number_format($countryListsCount) }}</strong>
                    <span>country lists</span>
                </li>
                <li>
                    <strong>{{ number_format($popularLists->count()) }}</strong>
                    <span>editor picks</span>
                </li>
            </ul>

            <div class="bbh-hero__search-wrap">
                <svg class="bbh-hero__search-icon" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd"/>
                </svg>
                <input type="search"
                       id="bbhHeroSearchInput"
                       class="bbh-hero__search"
                       placeholder="Search lists by name, platform, or country"
                       autocomplete="off"
                       aria-label="Search broker lists">
            </div>

            <div class="bbh-hero__actions">
                <a href="{{ route('find_my_broker') }}" class="bbh-btn bbh-btn--primary">Find my broker</a>
                <a href="{{ route('brokers.top.index') }}" class="bbh-btn bbh-btn--ghost">Top brokers</a>
                <a href="{{ route('methodology') }}" class="bbh-btn bbh-btn--ghost">Our methodology</a>
            </div>
        </div>
    </header>

    <div class="container bbh-body">
        @if($quickGuides->isNotEmpty())
            <nav class="bbh-quick" aria-label="Popular broker guides">
                @foreach($quickGuides as $guide)
                    <a href="{{ $guide['url'] }}" class="bbh-chip">{{ $categoryLabels[$guide['slug']] ?? \Illuminate\Support\Str::headline(str_replace('-', ' ', $guide['slug'])) }}</a>
                @endforeach
            </nav>
        @endif

        <section class="bbh-award" aria-label="BrokersCourt Awards">
            <a href="{{ route('awards.index') }}" class="bbh-award__card">
                <span class="bbh-award__glow" aria-hidden="true"></span>
                <div class="bbh-award__top">
                    <span class="bbh-award__icon" aria-hidden="true"><i class="fas fa-trophy"></i></span>
                    <div class="bbh-award__heading">
                        <p class="bbh-award__eyebrow">BrokersCourt Awards {{ $year }}</p>
                        <h2 class="bbh-award__title">Meet the best of the best</h2>
                    </div>
                    <span class="bbh-award__count">View winners</span>
                </div>
                <p class="bbh-award__desc">Editorial award categories built from live broker data — regulation, ratings, trading conditions, and verified reviews.</p>
                <span class="bbh-award__cta">
                    Explore award categories
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                </span>
            </a>
        </section>

        <div class="bbh-layout">
            <aside class="bbh-filters" id="bbhFiltersPanel" aria-label="Filter broker lists">
                <div class="bbh-filters-backdrop is-hidden" id="bbhFiltersBackdrop" aria-hidden="true"></div>
                <div class="bbh-filters__inner">
                    <div class="bbh-filters__head">
                        <h2 class="bbh-filters__title">Filters</h2>
                        <button type="button" class="bbh-filters__close" id="bbhFiltersClose" aria-label="Close filters">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <input type="search"
                           id="bbhSearchInput"
                           class="bbh-filters__search bbh-filters__search--desktop"
                           placeholder="Search broker lists by name"
                           autocomplete="off"
                           aria-label="Search broker lists">
                    <button type="button" class="bbh-filters__reset" id="bbhResetFiltersTop">
                        <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M15.312 11.424a5.5 5.5 0 01-9.201 2.466l-.312-.311h2.433a.75.75 0 000-1.5H4.268a.75.75 0 00-.75.75v3.182a.75.75 0 001.5 0v-2.433l.31.31a7 7 0 0011.712-3.138.75.75 0 00-1.449-.387zm-1.562 2.896a.75.75 0 00-1.449.387 5.5 5.5 0 019.201-2.466l.312.311H9.88a.75.75 0 000 1.5h3.182a.75.75 0 00.75-.75V9.88a.75.75 0 00-1.5 0v2.433l-.31-.31a7 7 0 00-11.712 3.138z" clip-rule="evenodd"/></svg>
                        Reset filters
                    </button>

                    @foreach($filterGroups as $groupKey => $group)
                        <div class="bbh-filter-group is-open" data-bbh-filter-group>
                            <button type="button" class="bbh-filter-group__toggle" data-bbh-filter-toggle>
                                <span>{{ $group['label'] }}</span>
                                <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                            <div class="bbh-filter-group__body">
                                @foreach($group['options'] as $value => $label)
                                    <label class="bbh-filter-option">
                                        <input type="checkbox"
                                               value="{{ $value }}"
                                               data-bbh-filter
                                               data-bbh-filter-group="{{ $groupKey }}">
                                        <span>{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <button type="button" class="bbh-filters__reset bbh-filters__reset--bottom" id="bbhResetFiltersBottom">
                        <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M15.312 11.424a5.5 5.5 0 01-9.201 2.466l-.312-.311h2.433a.75.75 0 000-1.5H4.268a.75.75 0 00-.75.75v3.182a.75.75 0 001.5 0v-2.433l.31.31a7 7 0 0011.712-3.138.75.75 0 00-1.449-.387zm-1.562 2.896a.75.75 0 00-1.449.387 5.5 5.5 0 019.201-2.466l.312.311H9.88a.75.75 0 000 1.5h3.182a.75.75 0 00.75-.75V9.88a.75.75 0 00-1.5 0v2.433l-.31-.31a7 7 0 00-11.712 3.138z" clip-rule="evenodd"/></svg>
                        Reset filters
                    </button>
                </div>
            </aside>

            <div class="bbh-main">
                <section class="bbh-popular" aria-labelledby="bbhPopularTitle">
                    <div class="bbh-section-head">
                        <div>
                            <p class="bbh-section-eyebrow">Editor picks</p>
                            <h2 class="bbh-section-title" id="bbhPopularTitle">Most popular toplists</h2>
                        </div>
                    </div>

                    <div class="bbh-carousel" data-bbh-carousel>
                        <ul class="bbh-carousel__viewport" id="bbhPopularTrack">
                            @foreach($popularLists as $list)
                                @include('front.brokers.partials.best_brokers_toplist_card', ['list' => $list])
                            @endforeach
                        </ul>
                        <div class="bbh-carousel__controls is-hidden">
                            <button type="button" class="bbh-carousel__btn" data-bbh-carousel-prev aria-label="Previous">
                                <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd"/></svg>
                            </button>
                            <div class="bbh-carousel__dots" data-bbh-carousel-dots></div>
                            <button type="button" class="bbh-carousel__btn" data-bbh-carousel-next aria-label="Next">
                                <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd"/></svg>
                            </button>
                        </div>
                    </div>
                </section>

                <section class="bbh-directory" aria-labelledby="bbhDirectoryTitle">
                    <div class="bbh-section-head">
                        <div>
                            <p class="bbh-section-eyebrow">Complete directory</p>
                            <h2 class="bbh-section-title bbh-section-title--spaced" id="bbhDirectoryTitle">All best broker lists</h2>
                        </div>
                        <button type="button" class="bbh-filters-toggle" id="bbhFiltersToggle" aria-expanded="false" aria-controls="bbhFiltersPanel">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 12h9.75M10.5 18h9.75M3.75 6h.008v.008H3.75V6zm0 6h.008v.008H3.75V12zm0 6h.008v.008H3.75V18z"/></svg>
                            Filters
                        </button>
                    </div>

                    <div class="bbh-type-tabs" role="tablist" aria-label="List type">
                        <button type="button" class="bbh-type-tab is-active" data-bbh-type-tab="all" aria-pressed="true">All lists</button>
                        <button type="button" class="bbh-type-tab" data-bbh-type-tab="category" aria-pressed="false">By category</button>
                        <button type="button" class="bbh-type-tab" data-bbh-type-tab="country" aria-pressed="false">By country</button>
                    </div>

                    @if($countryName && $countryListsCount > 0)
                        <p class="bbh-country-note">
                            You are viewing {{ $countryListsCount }} country-specific broker lists for
                            <button type="button" class="bbh-country-note__link" data-bbh-country-trigger>{{ $countryName }}</button>
                        </p>
                    @endif
                    <p class="bbh-results-meta" id="bbhResultsMeta" aria-live="polite"></p>

                    <ul class="bbh-grid" id="bbhAllGrid">
                        @foreach($allLists as $list)
                            @include('front.brokers.partials.best_brokers_toplist_card', ['list' => $list])
                        @endforeach
                    </ul>

                    <div class="bbh-empty is-hidden" id="bbhEmptyState">
                        <p>No broker lists match your filters. Try clearing the search or adjusting your filter selections.</p>
                    </div>

                    <div class="bbh-pagination is-hidden" id="bbhPagination">
                        <button type="button" class="bbh-pagination__btn" data-bbh-page-prev aria-label="Previous page">
                            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd"/></svg>
                        </button>
                        <span class="bbh-pagination__info" id="bbhPageInfo"></span>
                        <button type="button" class="bbh-pagination__btn" data-bbh-page-next aria-label="Next page">
                            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd"/></svg>
                        </button>
                    </div>
                </section>
            </div>
        </div>

        @if(($topRatedBrokers ?? collect())->isNotEmpty())
            <x-broker-slider
                class="bbh-top-slider"
                sectionId="bbh-top-brokers"
                :brokers="$topRatedBrokers"
                title="Top brokers"
                eyebrow="Highest rated"
                lead="Our highest-rated brokers from live review data — the same ranking used across BrokersCourt."
                :viewAllUrl="route('brokers.top.index')"
                viewAllLabel="View all top brokers"
                ctaLabel="Read review"
                limit="8"
            />
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/best-brokers-index.js') }}?v=10" defer></script>
@endpush
