@extends('front.layout.app')

@section('title', 'Broker Awards ' . date('Y') . ' | BrokersCourt')
@section('meta_description', 'Explore BrokersCourt award categories and discover top-rated forex brokers recognized for trust, execution, spreads, and platform quality.')

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/awards-index.css') }}?v=1">
@endpush

@section('main_content')
<div class="awi-page">
    <div class="awi-wrap">
        <header class="awi-hero">
            <span class="awi-hero__badge">BrokersCourt Awards {{ date('Y') }}</span>
            <h1 class="awi-hero__title">Broker awards</h1>
            <p class="awi-hero__subtitle">
                Award categories are built from our broker database — regulation, ratings, categories,
                and trading conditions — so each list reflects real, current broker data.
            </p>
        </header>

        <div class="awi-stats">
            <div class="awi-stat">
                <span class="awi-stat__value">{{ $stats['total_brokers'] }}</span>
                <span class="awi-stat__label">Brokers reviewed</span>
            </div>
            <div class="awi-stat">
                <span class="awi-stat__value">{{ $stats['featured_brokers'] }}</span>
                <span class="awi-stat__label">Featured picks</span>
            </div>
            <div class="awi-stat">
                <span class="awi-stat__value">{{ $stats['award_categories'] }}</span>
                <span class="awi-stat__label">Award categories</span>
            </div>
            <div class="awi-stat">
                <span class="awi-stat__value">{{ number_format($stats['average_rating'], 1) }}</span>
                <span class="awi-stat__label">Avg. rating /5</span>
            </div>
        </div>

        <div class="awi-layout">
            <aside class="awi-sidebar" aria-label="Filter awards">
                <h2 class="awi-sidebar__title">Search awards</h2>
                <input type="search"
                       id="awiSearchInput"
                       class="awi-sidebar__search"
                       placeholder="Type award name"
                       autocomplete="off"
                       aria-label="Search award categories">

                <div class="awi-sidebar__section">
                    <h3 class="awi-sidebar__title">Quick links</h3>
                    <a href="{{ route('broker.reviews.index') }}" class="awi-sidebar__link">All broker reviews</a>
                    <a href="{{ route('brokers.best.index') }}" class="awi-sidebar__link">Best broker guides</a>
                    <a href="{{ route('regulated_brokers') }}" class="awi-sidebar__link">Regulated brokers</a>
                </div>
            </aside>

            <div class="awi-main">
                <h2 class="awi-main__heading">Explore award categories</h2>
                <p class="awi-results-count" id="awiResultsCount">
                    {{ count($awardCards) }} award {{ \Illuminate\Support\Str::plural('category', count($awardCards)) }}
                </p>

                @if(count($awardCards))
                    <ul class="awi-grid" id="awiAwardGrid">
                        @foreach($awardCards as $award)
                            @include('front.awards.partials.award_card', ['award' => $award])
                        @endforeach
                    </ul>
                @endif

                <div class="awi-empty {{ count($awardCards) ? 'is-hidden' : '' }}" id="awiEmptyState">
                    <p>No award categories match your search.</p>
                </div>

                <section class="awi-methodology" aria-labelledby="awiMethodologyTitle">
                    <h2 class="awi-methodology__title" id="awiMethodologyTitle">How we evaluate brokers</h2>
                    <div class="awi-methodology__grid">
                        @foreach($evaluationPillars as $pillar)
                            <article class="awi-pillar">
                                <h3 class="awi-pillar__title">{{ $pillar['title'] }}</h3>
                                <p class="awi-pillar__text">{{ $pillar['description'] }}</p>
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
<script src="{{ asset('js/awards-index.js') }}?v=1"></script>
@endpush
