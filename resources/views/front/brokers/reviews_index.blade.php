@extends('front.layout.app')

@section('title', 'Broker Reviews ' . date('Y') . ' | BrokersCourt')
@section('meta_description', 'Browse independent forex broker reviews. Compare fees, regulation, platforms, and safety scores to find the right broker for your trading style.')
@section('canonical', route('broker.reviews.index'))

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/broker-reviews-index.css') }}?v=8">
@endpush

@section('main_content')
<div class="bri-page">
    <header class="bri-hero">
        <div class="container">
            <nav class="bri-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <span>Broker reviews</span>
            </nav>

            <p class="bri-hero__eyebrow">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                Independent research
            </p>
            <h1 class="bri-hero__title">Broker <span class="bri-hero__accent">reviews</span></h1>
            <p class="bri-hero__subtitle">Find the right broker and invest on your own — compare regulation, fees, and platforms before you open an account.</p>

            @include('front.brokers.partials.country_context_hero')
        </div>
    </header>

    <div class="container">
        <div class="row g-4">
            <aside class="col-12 col-lg-3 bri-filters" id="briFiltersPanel" aria-label="Filter brokers">
                <div class="bri-filters__inner">
                    <div class="bri-filters__head">
                        <h2 class="bri-filters__title">Filters</h2>
                        <button type="button" class="bri-filters__close" id="briFiltersClose" aria-label="Close filters">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <label class="bri-filters__label" for="briSearchInput">Search by name</label>
                    <input type="search"
                           id="briSearchInput"
                           class="bri-filters__search"
                           placeholder="Type broker name"
                           autocomplete="off"
                           aria-label="Search brokers by name">

                    <div class="bri-filters__markets">
                        <h3 class="bri-filters__markets-title">Asset types</h3>
                        <div class="bri-filters__options">
                            @foreach($marketFilters as $key => $label)
                                <label class="bri-filter-option">
                                    <input type="checkbox"
                                           value="{{ $key }}"
                                           data-bri-market-filter>
                                    <span>{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <button type="button" class="bri-filters__clear" id="briClearFilters">
                        Clear filters
                    </button>
                </div>
            </aside>

            <div class="col-12 col-lg-9 bri-main">
                <div class="bri-main__toolbar">
                    <div class="bri-main__toolbar-text">
                        <h2 class="bri-main__heading">
                            @if(($preferredCountry['slug'] ?? 'global') === 'global')
                                Brokers available worldwide in {{ date('Y') }}
                            @else
                                Filter results for {{ $preferredCountry['name'] ?? 'your region' }}
                            @endif
                        </h2>
                        <p class="bri-results-count" id="briResultsCount"></p>
                    </div>
                    <button type="button" class="bri-main__filter-toggle" id="briFiltersToggle" aria-expanded="false" aria-controls="briFiltersPanel">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 12h9.75M10.5 18h9.75M3.75 6h.008v.008H3.75V6zm0 6h.008v.008H3.75V12zm0 6h.008v.008H3.75V18z"/></svg>
                        Filters
                    </button>
                </div>

                <ul class="bri-grid" id="briBrokerGrid">
                    @foreach($brokersPayload as $broker)
                        @include('front.brokers.partials.reviews_index_card', ['broker' => $broker])
                    @endforeach
                </ul>

                <div class="bri-empty is-hidden" id="briEmptyState">
                    <p>No brokers match your filters. Try clearing the search or selecting different asset types.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bri-filters-backdrop is-hidden" id="briFiltersBackdrop" aria-hidden="true"></div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/broker-reviews-index.js') }}?v=6" defer></script>
@endpush
