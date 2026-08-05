@extends('front.layout.app')

@section('title', 'Regulated Forex Brokers ' . date('Y') . ' | BrokersCourt')
@section('meta_description', 'Compare regulated forex brokers with verified licences, investor protection, and transparent trading conditions.')

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/regulated-brokers-index.css') }}?v=1">
@endpush

@section('main_content')
<div class="rbi-page">
    <div class="rbi-wrap">
        <header class="rbi-hero">
            <span class="rbi-hero__badge">Verified regulation</span>
            <h1 class="rbi-hero__title">Regulated brokers</h1>
            <p class="rbi-hero__subtitle">
                Brokers with verified regulation or investor protection in our database.
                Compare licences, tiers, spreads, and safety features before you open an account.
            </p>
        </header>

        <div class="rbi-stats">
            <div class="rbi-stat">
                <span class="rbi-stat__value">{{ $stats['regulated_count'] }}</span>
                <span class="rbi-stat__label">Regulated brokers</span>
            </div>
            <div class="rbi-stat">
                <span class="rbi-stat__value">{{ $stats['tier_one_count'] }}</span>
                <span class="rbi-stat__label">Tier 1 brokers</span>
            </div>
            <div class="rbi-stat">
                <span class="rbi-stat__value">{{ $stats['investor_protection_count'] }}</span>
                <span class="rbi-stat__label">Investor protection</span>
            </div>
            <div class="rbi-stat">
                <span class="rbi-stat__value">{{ number_format($stats['average_rating'], 1) }}</span>
                <span class="rbi-stat__label">Avg. rating /5</span>
            </div>
        </div>

        <div class="rbi-layout">
            <aside class="rbi-sidebar" aria-label="Filter regulated brokers">
                <h2 class="rbi-sidebar__title">Filter by name</h2>
                <input type="search"
                       id="rbiSearchInput"
                       class="rbi-sidebar__search"
                       placeholder="Type broker name"
                       autocomplete="off"
                       aria-label="Search regulated brokers by name">

                @if($regulatorFilters !== [])
                    <div class="rbi-sidebar__section">
                        <h3 class="rbi-sidebar__section-title">Regulators</h3>
                        @foreach($regulatorFilters as $slug => $label)
                            <label class="rbi-filter-option">
                                <input type="checkbox"
                                       value="{{ $slug }}"
                                       data-rbi-regulator-filter>
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                @endif

                <div class="rbi-sidebar__section">
                    <h3 class="rbi-sidebar__section-title">Regulatory tier</h3>
                    @foreach($tierFilters as $key => $label)
                        <label class="rbi-filter-option">
                            <input type="checkbox"
                                   value="{{ $key }}"
                                   data-rbi-tier-filter>
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
                </div>

                <button type="button" class="rbi-sidebar__clear" id="rbiClearFilters">
                    Clear filters
                </button>

                <div class="rbi-sidebar__section">
                    <h3 class="rbi-sidebar__section-title">Quick links</h3>
                    <a href="{{ route('broker.reviews.index') }}" class="rbi-sidebar__link">All broker reviews</a>
                    <a href="{{ route('brokers.best.index') }}" class="rbi-sidebar__link">Best broker guides</a>
                    <a href="{{ route('scam_brokers') }}" class="rbi-sidebar__link">Scam broker warnings</a>
                </div>
            </aside>

            <div class="rbi-main">
                <h2 class="rbi-main__heading">Regulated brokers in {{ date('Y') }}</h2>
                <p class="rbi-results-count" id="rbiResultsCount">
                    {{ $stats['regulated_count'] }} regulated {{ \Illuminate\Support\Str::plural('broker', $stats['regulated_count']) }}
                </p>

                @if($brokersPayload->isNotEmpty())
                    <ul class="rbi-grid" id="rbiBrokerGrid">
                        @foreach($brokersPayload as $broker)
                            @include('front.brokers.partials.regulated_broker_card', ['broker' => $broker])
                        @endforeach
                    </ul>
                @endif

                <div class="rbi-empty {{ $brokersPayload->isNotEmpty() ? 'is-hidden' : '' }}" id="rbiEmptyState">
                    <p>
                        @if($brokersPayload->isEmpty())
                            No regulated brokers are currently listed in our database.
                        @else
                            No brokers match your filters. Try clearing the search or regulator filters.
                        @endif
                    </p>
                </div>

                <section class="rbi-trust" aria-labelledby="rbiTrustTitle">
                    <h2 class="rbi-trust__title" id="rbiTrustTitle">Why regulation matters</h2>
                    <div class="rbi-trust__grid">
                        @foreach($trustHighlights as $item)
                            <article class="rbi-trust-item">
                                <h3 class="rbi-trust-item__title">{{ $item['title'] }}</h3>
                                <p class="rbi-trust-item__value">{{ $item['value'] }}</p>
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
<script src="{{ asset('js/regulated-brokers-index.js') }}?v=1"></script>
@endpush
