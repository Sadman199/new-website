@extends('front.layout.app')

@section('title', 'Scam Broker List ' . date('Y') . ' | Flagged & Blacklisted Forex Brokers | BrokersCourt')
@section('meta_description', 'Verified list of scam and blacklisted forex brokers. Check flagged brokers, the reasons they were reported, and protect yourself before you deposit.')

@push('head')
    <link rel="canonical" href="{{ url('/scam-brokers') }}">
@endpush

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/scam-brokers-index.css') }}?v=1">
@endpush

@section('main_content')
<div class="sbi-page">
    <div class="sbi-wrap">
        <header class="sbi-hero">
            <span class="sbi-hero__badge">High-risk warning list</span>
            <h1 class="sbi-hero__title">Scam broker list</h1>
            <p class="sbi-hero__subtitle">
                Brokers flagged for fraudulent behaviour, missing regulation, blocked withdrawals, or regulator warnings.
                Always verify a broker here before you deposit.
            </p>
        </header>

        <div class="sbi-notice">
            <strong>How we flag brokers:</strong> we rely on public regulator warnings, verified user complaints,
            and evidence of unregulated activity. If you believe a listing is inaccurate,
            <a href="{{ route('contact.us') }}">contact us</a>.
        </div>

        <div class="sbi-layout">
            <aside class="sbi-sidebar" aria-label="Filter scam brokers">
                <h2 class="sbi-sidebar__title">Filter by name</h2>
                <input type="search"
                       id="sbiSearchInput"
                       class="sbi-sidebar__search"
                       placeholder="Type broker name"
                       autocomplete="off"
                       aria-label="Search flagged brokers by name">

                <div class="sbi-sidebar__section">
                    <h3 class="sbi-sidebar__section-title">Warning type</h3>
                    @foreach($warningFilters as $key => $label)
                        <label class="sbi-filter-option">
                            <input type="checkbox"
                                   value="{{ $key }}"
                                   data-sbi-warning-filter>
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
                </div>

                <button type="button" class="sbi-sidebar__clear" id="sbiClearFilters">
                    Clear filters
                </button>

                <div class="sbi-sidebar__section">
                    <h3 class="sbi-sidebar__section-title">Quick links</h3>
                    <a href="{{ route('regulated_brokers') }}" class="sbi-sidebar__link sbi-sidebar__link--safe">
                        See regulated brokers
                    </a>
                    <a href="{{ route('broker.reviews.index') }}" class="sbi-sidebar__link">
                        Broker reviews
                    </a>
                    <a href="{{ route('contact.us') }}" class="sbi-sidebar__link">
                        Report a broker
                    </a>
                </div>
            </aside>

            <div class="sbi-main">
                <h2 class="sbi-main__heading">
                    Flagged &amp; blacklisted brokers in {{ date('Y') }}
                </h2>
                <p class="sbi-results-count" id="sbiResultsCount">
                    {{ $scamCount }} flagged {{ \Illuminate\Support\Str::plural('broker', $scamCount) }}
                </p>

                @if($brokersPayload->isNotEmpty())
                    <ul class="sbi-grid" id="sbiBrokerGrid">
                        @foreach($brokersPayload as $broker)
                            @include('front.scam-brokers.partials.scam_broker_card', [
                                'broker' => $broker,
                                'warningFilters' => $warningFilters,
                            ])
                        @endforeach
                    </ul>
                @endif

                <div class="sbi-empty {{ $brokersPayload->isNotEmpty() ? 'is-hidden' : '' }}" id="sbiEmptyState">
                    <div class="sbi-empty__icon" aria-hidden="true">✓</div>
                    <h3 class="sbi-empty__title">No scam brokers found</h3>
                    <p class="sbi-empty__text">
                        @if($brokersPayload->isEmpty())
                            There are currently no brokers flagged as scam in our database.
                        @else
                            No brokers match your filters. Try clearing the search or warning types.
                        @endif
                    </p>
                </div>

                <section class="sbi-tips" aria-labelledby="sbiTipsTitle">
                    <h2 class="sbi-tips__title" id="sbiTipsTitle">How to spot a scam broker</h2>
                    <div class="sbi-tips__grid">
                        @foreach($warningSigns as $sign)
                            <article class="sbi-tip">
                                <h3 class="sbi-tip__title">{{ $sign['title'] }}</h3>
                                <p class="sbi-tip__text">{{ $sign['description'] }}</p>
                            </article>
                        @endforeach
                    </div>
                    <a href="{{ route('regulated_brokers') }}" class="sbi-tips__cta">
                        See regulated &amp; trusted brokers
                    </a>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/scam-brokers-index.js') }}?v=1"></script>
@endpush
