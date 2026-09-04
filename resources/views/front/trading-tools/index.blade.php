@extends('front.layout.app')

@section('title', 'Trading Tools | Free Forex Calculators | BrokersCourt')
@section('meta_description', 'Free forex trading calculators — pip value, position size, profit/loss, margin, risk, pivot points, Fibonacci, currency converter, and live market widgets. Each tool has its own dedicated page.')
@section('canonical', route('trading.tools'))

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/trading-tools.css') }}?v=8">
    <link rel="stylesheet" href="{{ asset('css/insight-cards.css') }}?v=3">
@endpush

@section('main_content')
@php
    $calculatorTools = $tools->filter(fn ($tool) => $tool->slug !== 'live-markets')->values();
    $widgetTool = $tools->firstWhere('slug', 'live-markets');
    $toolCount = $tools->count();
    $featuredTools = $calculatorTools->take(4);
    $toc = collect([
        ['id' => 'tool-directory', 'label' => 'Calculator directory'],
        ['id' => 'market-widgets', 'label' => 'Live market widgets'],
        ['id' => 'why-choose-tools', 'label' => 'Why choose our tools'],
        ['id' => 'top-rated-brokers', 'label' => 'Top rated brokers'],
        ['id' => 'related-blog', 'label' => 'Related blog'],
    ])->filter(fn ($item) => $item['id'] !== 'related-blog' || ! empty($latestPosts))->values();
@endphp
<div class="tt-page tt-page--hub">
    <header class="tt-hero">
        <div class="tt-hero__bg" aria-hidden="true"></div>
        <div class="tt-wrap tt-hero__layout">
            <div class="tt-hero__content">
            <nav class="tt-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <span>Trading tools</span>
            </nav>

            <p class="tt-hero__eyebrow">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25V12zm0 2.25h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25V18zm2.498-6.75h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V12zm0 2.25h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V18zm2.504-6.75h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V12zm0 2.25h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V18z"/>
                </svg>
                Free forex calculators
            </p>
            <h1 class="tt-hero__title">Trading <span class="tt-hero__accent">tools</span></h1>
            <p class="tt-hero__subtitle">
                Professional forex calculators for pip value, position sizing, margin, risk/reward, pivot points,
                Fibonacci levels, currency conversion, and live market data — each with its own dedicated page.
            </p>
            <div class="tt-hero__actions">
                <a class="tt-hero__primary-action" href="#tool-directory">
                    Explore calculators
                    <span aria-hidden="true">→</span>
                </a>
                @if($widgetTool)
                    <a class="tt-hero__secondary-action" href="{{ route('trading.tools.show', ['slug' => $widgetTool->route_slug]) }}">
                        View live markets
                    </a>
                @endif
            </div>
            <div class="tt-hero__stats" aria-label="Trading tools summary">
                <div class="tt-stat">
                    <strong>{{ $toolCount }}</strong>
                    <span>active tools</span>
                </div>
                <div class="tt-stat">
                    <strong>{{ $calculatorTools->count() }}</strong>
                    <span>calculators</span>
                </div>
                <div class="tt-stat">
                    <strong>{{ $widgetTool ? 'Live' : '0' }}</strong>
                    <span>{{ $widgetTool ? 'market widgets' : 'widgets' }}</span>
                </div>
            </div>
            </div>

            <aside class="tt-hero__navigator" aria-label="Start your trade plan">
                <div class="tt-hero__navigator-head">
                    <span class="tt-hero__navigator-kicker">Plan a trade</span>
                    <strong>Start with the essentials</strong>
                    <p>Move from risk to execution in three quick steps.</p>
                </div>
                <div class="tt-hero__navigator-list">
                    @foreach($featuredTools->take(3) as $index => $tool)
                        @php
                            $navigatorSummary = trim((string) ($tool->tool_summary ?? $tool->short_description ?? $tool->tool_description ?? $tool->page_about));
                        @endphp
                        <a href="{{ route('trading.tools.show', ['slug' => $tool->route_slug]) }}" class="tt-hero__navigator-item">
                            <span class="tt-hero__navigator-number">0{{ $index + 1 }}</span>
                            <span>
                                <strong>{{ $tool->page_title ?? $tool->name }}</strong>
                                @if($navigatorSummary !== '')
                                    <small>{{ $navigatorSummary }}</small>
                                @endif
                            </span>
                            <span class="tt-hero__navigator-arrow" aria-hidden="true">↗</span>
                        </a>
                    @endforeach
                </div>
                <a class="tt-hero__navigator-footer" href="#tool-directory">Browse all calculators <span aria-hidden="true">→</span></a>
            </aside>
        </div>
    </header>

    <div class="tt-body">
        <div class="tt-wrap">
            <section class="tt-toc" aria-labelledby="tt-whats-inside-title">
                <div class="tt-toc__head">
                    <p class="tt-section__eyebrow">What's inside</p>
                    <h2 class="tt-section__title" id="tt-whats-inside-title">Everything you need, in one place</h2>
                    <p class="tt-section__lead">
                        Explore calculators, live market data, broker research, and relevant trading education.
                    </p>
                </div>
                <div class="tt-toc__grid">
                    @foreach($toc as $item)
                        <a href="#{{ $item['id'] }}" class="tt-toc__card">{{ $item['label'] }}</a>
                    @endforeach
                </div>
            </section>

            <section class="tt-section" id="tool-directory" aria-labelledby="tt-directory-title">
                <div class="tt-section__head">
                    <div>
                        <p class="tt-section__eyebrow">Calculator directory</p>
                        <h2 class="tt-section__title" id="tt-directory-title">Our free calculators</h2>
                    </div>
                </div>

                <div class="tt-hub">
                    @foreach($calculatorTools as $tool)
                        <a href="{{ route('trading.tools.show', ['slug' => $tool->route_slug]) }}" class="tt-hub-card tt-hub-card--simple">
                            <span class="tt-hub-card__icon" aria-hidden="true">
                                <i class="{{ $tool->icon }}"></i>
                            </span>
                            <span class="tt-hub-card__body">
                                <strong class="tt-hub-card__title">{{ $tool->page_title ?? $tool->name }}</strong>
                            </span>
                            <span class="tt-hub-card__arrow" aria-hidden="true">
                                <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/></svg>
                            </span>
                        </a>
                    @endforeach
                </div>
            </section>

            @if($widgetTool)
                <section class="tt-section tt-market-feature" id="market-widgets" aria-labelledby="tt-market-title">
                    <div class="tt-market-feature__card">
                        <div class="tt-market-feature__copy">
                            <p class="tt-section__eyebrow">Live market tools</p>
                            <h2 class="tt-section__title" id="tt-market-title">{{ $widgetTool->page_title ?? $widgetTool->name }}</h2>
                            <p class="tt-section__lead">{{ $widgetTool->tool_description ?? $widgetTool->page_about }}</p>
                            <div class="tt-prose">
                                <p>
                                    Market widgets help traders avoid operating in a vacuum. Check real-time cross rates, heatmap strength,
                                    and the economic calendar before entering or holding positions through volatile sessions.
                                </p>
                            </div>
                            <a href="{{ route('trading.tools.show', ['slug' => $widgetTool->route_slug]) }}" class="tt-section__button">Open live market tools</a>
                        </div>
                        <div class="tt-market-feature__points">
                            <article class="tt-feature-point">
                                <h3>Cross-rate overview</h3>
                                <p>See where major and minor FX pairs are moving without flipping through multiple charts.</p>
                            </article>
                            <article class="tt-feature-point">
                                <h3>Heatmap context</h3>
                                <p>Spot relative currency strength quickly when scanning for momentum or reversal setups.</p>
                            </article>
                            <article class="tt-feature-point">
                                <h3>Economic calendar</h3>
                                <p>Stay ahead of scheduled events so your trade plan is not disrupted by avoidable news volatility.</p>
                            </article>
                        </div>
                    </div>
                </section>
            @endif

            <section class="tt-benefits" id="why-choose-tools" aria-labelledby="tt-benefits-title">
                <div class="tt-benefits__top">
                    <div class="tt-benefits__intro">
                        <p class="tt-benefits__eyebrow">Why choose our tools</p>
                        <h2 class="tt-benefits__title" id="tt-benefits-title">Built for clearer decisions, not more complexity</h2>
                        <p class="tt-benefits__lead">
                            A focused set of calculators and live market tools, designed to help you plan each trade with confidence.
                        </p>
                    </div>
                    <div class="tt-benefits__stats" aria-label="Trading tools at a glance">
                        <div class="tt-benefits__stat">
                            <strong>{{ $calculatorTools->count() }}</strong>
                            <span>calculators</span>
                        </div>
                        <div class="tt-benefits__stat">
                            <strong>{{ $widgetTool ? 'Live' : '—' }}</strong>
                            <span>market data</span>
                        </div>
                    </div>
                </div>
                <div class="tt-benefits__grid">
                    <article class="tt-benefit-card tt-benefit-card--one">
                        <span class="tt-benefit-card__number">01</span>
                        <h3>Cleaner content hierarchy</h3>
                        <p>The page is structured more like a real trading tools guide, with sections that explain purpose, usage, and workflow instead of only listing links.</p>
                    </article>
                    <article class="tt-benefit-card tt-benefit-card--two">
                        <span class="tt-benefit-card__number">02</span>
                        <h3>Dynamic descriptions</h3>
                        <p>Tool cards still pull live copy from your existing configuration, so the design looks editorial while remaining easy to manage from your current setup.</p>
                    </article>
                    <article class="tt-benefit-card tt-benefit-card--three">
                        <span class="tt-benefit-card__number">03</span>
                        <h3>Connected decision-making</h3>
                        <p>We link calculators with broker reviews and related blog content so traders can move from raw numbers to broker selection and deeper learning in one place.</p>
                    </article>
                </div>
            </section>

            <x-broker-slider
                sectionId="top-rated-brokers"
                :brokers="$topRatedBrokers"
                title="Top Rated Brokers"
                eyebrow="Broker research"
                lead="Compare highly rated brokers after using the calculators, so you can match your trade plan with a platform that fits your style."
                :viewAllUrl="route('broker.reviews.index')"
                viewAllLabel="View all broker reviews"
                limit="6"
                class="tt-broker-slider"
            />

            @if(!empty($latestPosts))
                <section class="tt-blog" id="related-blog" aria-labelledby="tt-related-blog-title">
                    <div class="tt-section__head">
                        <div>
                            <p class="tt-section__eyebrow">Related blog</p>
                            <h2 class="tt-section__title" id="tt-related-blog-title">Keep learning after the calculation</h2>
                        </div>
                        <a href="{{ route('blog') }}" class="tt-section__link">View all articles</a>
                    </div>

                    <div class="bc-insights__grid">
                        @foreach($latestPosts as $index => $post)
                            @include('front.partials.insight_card', [
                                'index' => $index,
                                'url' => $post['url'],
                                'title' => $post['title'],
                                'photo' => $post['photo'],
                                'category' => $post['category'],
                                'date' => $post['date'],
                                'dateIso' => $post['date_iso'],
                                'readMinutes' => $post['read_time'],
                                'authorName' => $post['author'],
                                'authorPhoto' => $post['author_photo'],
                            ])
                        @endforeach
                    </div>
                </section>
            @endif

            <p class="tt-disclaimer">
                Tools use standard forex formulas with reference FX rates for planning. They are educational and do not
                constitute trading advice. Always verify with your broker’s contract specifications.
            </p>
        </div>
    </div>
</div>
@endsection
