@extends('front.layout.app')

@section('title', 'Best Regulated Forex Brokers ' . date('Y') . ' | BrokersCourt')
@section('meta_description', 'Compare the best regulated forex brokers with verified licences, investor protection, and transparent trading conditions.')
@section('canonical', route('regulated_brokers'))

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/regulated-brokers-index.css') }}?v=8">
@endpush

@section('main_content')
<div class="brb-page">
    <header class="brb-hero">
        <div class="brb-wrap">
            <nav class="brb-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <span>Best regulated brokers</span>
            </nav>

            <p class="brb-hero__eyebrow">Verified regulation</p>
            <h1 class="brb-hero__title">Best <span class="brb-hero__accent">regulated brokers</span></h1>
            <p class="brb-hero__subtitle">
                Brokers with verified regulation or investor protection in our database.
                Compare licences, tiers, spreads, and safety features before you open an account.
            </p>

            @include('front.brokers.partials.country_context_hero')

        </div>
    </header>

    <div class="brb-wrap">
        <div class="brb-layout">
            <aside class="brb-filters" aria-label="Filter regulated brokers">
                <h2 class="brb-filters__title">Filters</h2>
                <input type="search"
                       id="rbiSearchInput"
                       class="brb-filters__search"
                       placeholder="Search by broker name"
                       autocomplete="off"
                       aria-label="Search regulated brokers by name">

                @if($regulatorFilters !== [])
                    <div class="brb-filter-group is-open">
                        <h3 class="brb-filter-group__title">Regulators</h3>
                        <div class="brb-filter-group__body">
                            @foreach($regulatorFilters as $slug => $label)
                                <label class="brb-filter-option">
                                    <input type="checkbox"
                                           value="{{ $slug }}"
                                           data-rbi-regulator-filter>
                                    <span>{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="brb-filter-group is-open">
                    <h3 class="brb-filter-group__title">Regulatory tier</h3>
                    <div class="brb-filter-group__body">
                        @foreach($tierFilters as $key => $label)
                            <label class="brb-filter-option">
                                <input type="checkbox"
                                       value="{{ $key }}"
                                       data-rbi-tier-filter>
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <button type="button" class="brb-filters__reset" id="rbiClearFilters">
                    Reset filters
                </button>

                <div class="brb-filters__links">
                    <p class="brb-filters__links-title">Safety tools</p>
                    <a href="{{ route('broker.scam_checker') }}" class="brb-filters__link">Scam checker</a>
                    <a href="{{ route('scam_brokers') }}" class="brb-filters__link brb-filters__link--warn">Scam broker list</a>
                    <a href="{{ route('broker.reviews.index') }}" class="brb-filters__link">All broker reviews</a>
                    <a href="{{ route('brokers.best.index') }}" class="brb-filters__link">Best broker guides</a>
                </div>
            </aside>

            <div class="brb-main">
                <div class="brb-main__head">
                    <h2 class="brb-main__heading">Regulated brokers in {{ date('Y') }}</h2>
                    <p class="brb-results-count" id="rbiResultsCount">
                        {{ $stats['regulated_count'] }} regulated {{ \Illuminate\Support\Str::plural('broker', $stats['regulated_count']) }}
                    </p>
                </div>

                @if($brokersPayload->isNotEmpty())
                    <ul class="brb-grid" id="rbiBrokerGrid">
                        @foreach($brokersPayload as $broker)
                            @include('front.brokers.partials.regulated_broker_card', ['broker' => $broker])
                        @endforeach
                    </ul>
                @endif

                <div class="brb-empty {{ $brokersPayload->isNotEmpty() ? 'is-hidden' : '' }}" id="rbiEmptyState">
                    <p>
                        @if($brokersPayload->isEmpty())
                            No regulated brokers are currently listed in our database.
                        @else
                            No brokers match your filters. Try clearing the search or regulator filters.
                        @endif
                    </p>
                </div>

                <section class="brb-trust" aria-labelledby="brbTrustTitle">
                    <h2 class="brb-trust__title" id="brbTrustTitle">Why regulation matters</h2>
                    <div class="brb-trust__grid">
                        @foreach($trustHighlights as $item)
                            <article class="brb-trust-item">
                                <h3 class="brb-trust-item__title">{{ $item['title'] }}</h3>
                                <p class="brb-trust-item__value">{{ $item['value'] }}</p>
                            </article>
                        @endforeach
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/regulated-brokers-index.js') }}?v=2" defer></script>
@endpush
