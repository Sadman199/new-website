@extends('front.layout.app')

@section('title', 'Forex Trading Tools | Calculators & Market Data | BrokersCourt')
@section('meta_description', 'Free forex trading tools to calculate pip value, position size, profit, margin, risk, and trading costs, plus pivot points, Fibonacci levels, currency conversion, and live market data.')
@section('canonical', route('calculators.index'))

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/calculators.css') }}?v=10">
@endpush

@push('json_ld')
    <script type="application/ld+json">@json($hubJsonLd ?? [])</script>
@endpush

@php
    $hubToolCount = collect($toolGroups ?? [])->sum(fn ($group) => $group['tools']->count());
    $hubCategoryCount = count($toolGroups ?? []);
@endphp

@section('main_content')
<div class="calc-page calc-page--hub">
    <header class="calc-hero">
        <div class="calc-hero__bg" aria-hidden="true"></div>
        <div class="calc-wrap">
            <nav class="calc-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <span>Forex trading tools</span>
            </nav>

            <div class="calc-hero__inner">
                <div class="calc-hero__copy">
                    <p class="calc-hero__eyebrow">
                        <i class="fas fa-calculator" aria-hidden="true"></i>
                        BrokersCourt tools
                    </p>
                    <h1 class="calc-hero__title">Forex <span class="calc-hero__accent">Trading Tools</span></h1>
                    <p class="calc-hero__lead">
                        Calculate trading costs, position size, risk, and margin, then map technical levels
                        and convert currencies before you compare brokers.
                    </p>
                    <div class="calc-hero__stats" aria-label="Tools at a glance">
                        <div class="calc-stat">
                            <strong>{{ $hubToolCount }}</strong>
                            <span>{{ \Illuminate\Support\Str::plural('tool', $hubToolCount) }}</span>
                        </div>
                        <div class="calc-stat">
                            <strong>{{ $hubCategoryCount }}</strong>
                            <span>{{ \Illuminate\Support\Str::plural('category', $hubCategoryCount) }}</span>
                        </div>
                        <div class="calc-stat">
                            <strong>{{ ($widgetTool ?? null) ? 'Live' : '—' }}</strong>
                            <span>market widgets</span>
                        </div>
                    </div>
                    <div class="calc-hero__links">
                        <a href="{{ route('broker.comparison') }}" class="bc-btn bc-btn--ghost">Compare brokers</a>
                        <a href="{{ route('broker.alternatives.index') }}" class="bc-btn bc-btn--ghost">Broker alternatives</a>
                        <a href="{{ route('brokers.best.index') }}" class="bc-btn bc-btn--ghost">Best broker guides</a>
                    </div>
                </div>

                <div class="calc-hero__orb" aria-hidden="true">
                    <div class="calc-hero__orb-ring"></div>
                    <i class="fas fa-calculator calc-hero__orb-icon"></i>
                </div>
            </div>
        </div>
    </header>

    <div class="calc-wrap calc-main">
        @if($hubToolCount > 0)
            <div class="calc-toolbar">
                <div class="calc-toolbar__search">
                    <label class="visually-hidden" for="calcHubSearch">Search trading tools</label>
                    <i class="fas fa-search" aria-hidden="true"></i>
                    <input type="search"
                           id="calcHubSearch"
                           placeholder="Search calculators and market tools…"
                           autocomplete="off">
                </div>
                <nav class="calc-toolbar__jumps" aria-label="Tool categories">
                    @foreach($toolGroups as $group)
                        <a href="#calc-cat-{{ $group['key'] }}">{{ $group['label'] }}</a>
                    @endforeach
                </nav>
                <p class="calc-toolbar__count" id="calcHubCount">{{ $hubToolCount }} {{ \Illuminate\Support\Str::plural('tool', $hubToolCount) }}</p>
            </div>
        @endif

        @forelse($toolGroups as $group)
            <section class="calc-category" data-calc-category aria-labelledby="calc-cat-{{ $group['key'] }}">
                <div class="calc-category__head">
                    <span class="calc-category__icon" aria-hidden="true">
                        <i class="{{ $group['icon'] }}"></i>
                    </span>
                    <div>
                        <p class="calc-category__eyebrow">{{ $group['tools']->count() }} {{ \Illuminate\Support\Str::plural('tool', $group['tools']->count()) }}</p>
                        <h2 class="calc-category__title" id="calc-cat-{{ $group['key'] }}">{{ $group['label'] }}</h2>
                        <p class="calc-category__intro">{{ $group['intro'] }}</p>
                    </div>
                </div>
                <div class="calc-grid">
                    @foreach($group['tools'] as $index => $calculator)
                        @include('front.calculators.partials.card', [
                            'calculator' => $calculator,
                            'index' => $index,
                        ])
                    @endforeach
                </div>
            </section>
        @empty
            <div class="calc-empty-state">
                <div class="calc-empty-state__icon" aria-hidden="true">
                    <i class="fas fa-calculator"></i>
                </div>
                <h2 class="calc-empty-state__title">No tools available at the moment</h2>
                <p class="calc-empty-state__text">Check back soon — we are preparing new trading tools for you.</p>
            </div>
        @endforelse

        <div class="calc-empty-state calc-empty-state--filter is-hidden" id="calcHubEmpty">
            <div class="calc-empty-state__icon" aria-hidden="true">
                <i class="fas fa-search"></i>
            </div>
            <h2 class="calc-empty-state__title">No tools match your search</h2>
            <p class="calc-empty-state__text">Try a different keyword, or clear the search to see every calculator again.</p>
        </div>

        <section class="calc-journey" aria-labelledby="calc-journey-title">
            <div class="calc-journey__copy">
                <p class="calc-journey__eyebrow">Trade workflow</p>
                <h2 class="calc-journey__title" id="calc-journey-title">From calculation to broker comparison</h2>
                <p class="calc-journey__text">
                    After you have a pip value, position size, or estimated cost, compare published spreads and
                    minimum deposits on live broker profiles. Missing figures stay marked unavailable.
                </p>
                <div class="calc-journey__actions">
                    <a href="{{ route('broker.reviews.index') }}" class="bc-btn bc-btn--primary">Browse broker reviews</a>
                    <a href="{{ route('broker.comparison') }}" class="bc-btn bc-btn--ghost">Open comparison tool</a>
                </div>
            </div>
            <div class="calc-journey__steps">
                <article class="calc-step">
                    <span>01</span>
                    <h3>Plan the trade</h3>
                    <p>Size the position, check margin, and estimate cost before you enter.</p>
                </article>
                <article class="calc-step">
                    <span>02</span>
                    <h3>Map the levels</h3>
                    <p>Use pivots and Fibonacci to mark support, resistance, and invalidation.</p>
                </article>
                <article class="calc-step">
                    <span>03</span>
                    <h3>Compare brokers</h3>
                    <p>Match the plan with spreads, deposits, and platforms on live reviews.</p>
                </article>
            </div>
        </section>

        <p class="calc-disclaimer">
            Calculators use standard forex formulas with reference rates for planning. They are educational and do not
            constitute trading advice. Always verify with your broker’s contract specifications.
        </p>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/calculators-hub.js') }}?v=1" defer></script>
@endpush
