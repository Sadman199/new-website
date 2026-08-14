@extends('front.layout.app')

@section('title', 'Scam Broker List ' . date('Y') . ' | Flagged & Blacklisted Forex Brokers | BrokersCourt')
@section('meta_description', 'Verified list of scam and blacklisted forex brokers. Check flagged brokers, the reasons they were reported, and protect yourself before you deposit.')
@section('canonical', route('scam_brokers'))

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/scam-brokers-index.css') }}?v=7">
@endpush

@section('main_content')
<div class="sbi-page">
    <header class="sbi-hero">
        <div class="sbi-wrap">
            <nav class="sbi-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <span>Scam brokers</span>
            </nav>

            <p class="sbi-hero__eyebrow">High-risk warning list</p>
            <h1 class="sbi-hero__title">Scam <span class="sbi-hero__accent">broker list</span></h1>
            <p class="sbi-hero__subtitle">
                Brokers flagged for fraud, missing regulation, blocked withdrawals, or regulator warnings.
                Verify any broker here before you deposit.
            </p>

            @include('front.partials.safety_hub_nav', ['activeHub' => 'list'])

        </div>
    </header>

    <div class="sbi-wrap">
        <div class="sbi-notice">
            <strong>How we flag brokers:</strong> public regulator warnings, verified user complaints, and evidence of unregulated activity.
            <a href="{{ route('contact') }}">Report an issue</a>.
        </div>

        <div class="sbi-layout">
            <aside class="sbi-sidebar" aria-label="Filter scam brokers">
                <h2 class="sbi-sidebar__title">Filters</h2>
                <input type="search"
                       id="sbiSearchInput"
                       class="sbi-sidebar__search"
                       placeholder="Search by broker name"
                       autocomplete="off"
                       aria-label="Search flagged brokers by name">

                <div class="sbi-filter-group is-open">
                    <h3 class="sbi-filter-group__title">Warning type</h3>
                    <div class="sbi-filter-group__body">
                        @foreach($warningFilters as $key => $label)
                            <label class="sbi-filter-option">
                                <input type="checkbox"
                                       value="{{ $key }}"
                                       data-sbi-warning-filter>
                                <span>{{ $label }}</span>
                                <span class="sbi-filter-count" data-sbi-filter-count="{{ $key }}">{{ $warningCounts[$key] ?? 0 }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <button type="button" class="sbi-sidebar__clear" id="sbiClearFilters">
                    Reset filters
                </button>

                <div class="sbi-sidebar__tools">
                    <p class="sbi-sidebar__tools-title">Quick check</p>
                    <form class="sbi-checker" action="{{ route('broker.scam_checker') }}" method="get">
                        <input type="search"
                               name="q"
                               class="sbi-checker__input"
                               placeholder="Broker name…"
                               autocomplete="off"
                               aria-label="Check a broker for scam warnings">
                        <button type="submit" class="sbi-checker__btn">Check</button>
                    </form>
                    <a href="{{ route('broker.scam_checker') }}" class="sbi-sidebar__link sbi-sidebar__link--checker">
                        Open full scam checker
                    </a>
                </div>

                <div class="sbi-sidebar__links">
                    <p class="sbi-sidebar__links-title">Quick links</p>
                    <a href="{{ route('regulated_brokers') }}" class="sbi-sidebar__link sbi-sidebar__link--safe">
                        Regulated brokers
                    </a>
                    <a href="{{ route('broker.reviews.index') }}" class="sbi-sidebar__link">
                        Broker reviews
                    </a>
                    <a href="{{ route('contact') }}" class="sbi-sidebar__link">
                        Report a broker
                    </a>
                </div>
            </aside>

            <div class="sbi-main">
                <div class="sbi-main__head">
                    <h2 class="sbi-main__heading">Flagged &amp; blacklisted brokers in {{ date('Y') }}</h2>
                    <p class="sbi-results-count" id="sbiResultsCount" data-sbi-total="{{ $scamCount }}">
                        {{ $scamCount }} flagged {{ \Illuminate\Support\Str::plural('broker', $scamCount) }}
                    </p>
                </div>

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
                    <div class="sbi-empty__icon" aria-hidden="true">!</div>
                    <h3 class="sbi-empty__title" id="sbiEmptyTitle">
                        @if($brokersPayload->isEmpty())
                            No scam brokers listed
                        @else
                            No brokers match your filters
                        @endif
                    </h3>
                    <p class="sbi-empty__text" id="sbiEmptyText">
                        @if($brokersPayload->isEmpty())
                            There are currently no brokers flagged as scam in our database.
                        @else
                            Try clearing the search or warning type filters.
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
<script src="{{ asset('js/scam-brokers-index.js') }}?v=2" defer></script>
@endpush
