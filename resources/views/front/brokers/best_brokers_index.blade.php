@extends('front.layout.app')

@section('title', 'Best brokers for every need in ' . date('Y') . ' | BrokersCourt')
@section('meta_description', 'Explore editor-picked best broker rankings by trading style, platform, asset class, and country. Find the ideal broker for your goals.')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/best-brokers-index.css') }}?v=5">
@endpush

@section('main_content')
@php
    $popularLists = collect($toplists)->where('popular', true)->values();
    $allLists = collect($toplists)->where('popular', false)->values();
    $countryListsCount = collect($toplists)->where('type', 'country')->count();
    $countryName = ($preferredCountry['slug'] ?? 'global') === 'global'
        ? null
        : ($preferredCountry['name'] ?? null);
    $awardsSpotlight = [
        'title' => 'Meet the Best of the Best',
        'description' => 'BrokersCourt Awards ' . date('Y') . ' winners are here!',
        'url' => route('awards.index'),
        'filters' => [],
    ];
@endphp
<div class="bbh-page">
    <header class="bbh-hero">
        <div class="bbh-wrap">
            <nav class="bbh-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <span>Best brokers</span>
            </nav>

            <p class="bbh-hero__eyebrow">Independent broker research</p>
            <h1 class="bbh-hero__title">Explore the <span class="bbh-hero__accent">top brokers</span> for every need</h1>
            <p class="bbh-hero__subtitle">Browse our editor-picked rankings to find the ideal broker for your goals</p>

            <div class="bbh-hero__search-wrap">
                <svg class="bbh-hero__search-icon" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd"/>
                </svg>
                <input type="search"
                       id="bbhHeroSearchInput"
                       class="bbh-hero__search"
                       placeholder="Search by keyword e.g. 'low fees'"
                       autocomplete="off"
                       aria-label="Search broker lists">
            </div>
        </div>
    </header>

    <div class="bbh-wrap">
        <div class="bbh-layout">
            <aside class="bbh-filters" aria-label="Filter broker lists">
                <h2 class="bbh-filters__title">Filters</h2>
                <input type="search"
                       id="bbhSearchInput"
                       class="bbh-filters__search bbh-filters__search--desktop"
                       placeholder="Search by keyword e.g. 'low fees'"
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
            </aside>

            <div class="bbh-main">
                <h2 class="bbh-section-title">Most popular toplists</h2>
                <div class="bbh-carousel" data-bbh-carousel>
                    <ul class="bbh-carousel__viewport" id="bbhPopularTrack">
                        @include('front.brokers.partials.best_brokers_toplist_card', [
                            'list' => $awardsSpotlight,
                            'spotlight' => true,
                            'ctaLabel' => 'View winners list',
                        ])
                        @foreach($popularLists as $list)
                            @include('front.brokers.partials.best_brokers_toplist_card', ['list' => $list])
                        @endforeach
                    </ul>
                    <div class="bbh-carousel__controls">
                        <button type="button" class="bbh-carousel__btn" data-bbh-carousel-prev aria-label="Previous">
                            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd"/></svg>
                        </button>
                        <div class="bbh-carousel__dots" data-bbh-carousel-dots></div>
                        <button type="button" class="bbh-carousel__btn" data-bbh-carousel-next aria-label="Next">
                            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd"/></svg>
                        </button>
                    </div>
                </div>

                <h2 class="bbh-section-title bbh-section-title--spaced">All best broker lists</h2>
                @if($countryName && $countryListsCount > 0)
                    <p class="bbh-country-note">
                        You are viewing {{ $countryListsCount }} country-specific broker lists for
                        <button type="button" class="bbh-country-note__link" data-bbh-country-trigger>{{ $countryName }}</button>
                    </p>
                @endif
                <p class="bbh-results-meta" id="bbhResultsMeta"></p>

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
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/best-brokers-index.js') }}?v=5" defer></script>
@endpush
