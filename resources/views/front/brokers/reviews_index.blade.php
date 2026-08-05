@extends('front.layout.app')

@section('title', 'Broker Reviews ' . date('Y') . ' | BrokersCourt')
@section('meta_description', 'Browse independent forex broker reviews. Compare fees, regulation, platforms, and safety scores to find the right broker for your trading style.')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/broker-reviews-index.css') }}?v=1">
@endpush

@section('main_content')
@php
    $countryName = ($preferredCountry['slug'] ?? 'global') === 'global'
        ? 'worldwide'
        : ($preferredCountry['name'] ?? 'your country');
@endphp
<div class="bri-page">
    <div class="bri-wrap">
        <header class="bri-hero">
            <h1 class="bri-hero__title">Broker reviews</h1>
            <p class="bri-hero__subtitle">Find the right broker and invest on your own</p>
        </header>

        <div class="bri-layout">
            <aside class="bri-filters" aria-label="Filter brokers">
                <h2 class="bri-filters__title">Filter by name</h2>
                <input type="search"
                       id="briSearchInput"
                       class="bri-filters__search"
                       placeholder="Type broker name"
                       autocomplete="off"
                       aria-label="Search brokers by name">

                <div class="bri-filters__markets">
                    <h3 class="bri-filters__markets-title">Asset types</h3>
                    @foreach($marketFilters as $key => $label)
                        <label class="bri-filter-option">
                            <input type="checkbox"
                                   value="{{ $key }}"
                                   data-bri-market-filter>
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
                </div>

                <button type="button" class="bri-filters__clear" id="briClearFilters">
                    Clear filters
                </button>
            </aside>

            <div class="bri-main">
                <h2 class="bri-main__heading">
                    Brokers available in {{ $countryName }} in {{ date('Y') }}
                </h2>
                <p class="bri-results-count" id="briResultsCount"></p>

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
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/broker-reviews-index.js') }}?v=1"></script>
@endpush
