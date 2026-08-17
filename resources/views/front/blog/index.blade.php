@extends('front.layout.app')

@section('title', 'Financial & Broker News — Daily Market Updates | BrokersCourt')
@section('meta_description', 'Stay ahead with BrokersCourt news: broker updates, market analysis, regulation insights, and trading education from our editorial team.')
@section('canonical', route('blog'))

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/blog-index.css') }}?v=7">
@endpush

@section('main_content')
<div class="bli-page">
    <header class="bli-hero">
        <div class="container">
            <nav class="bli-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <span>Blog</span>
            </nav>

            <p class="bli-hero__eyebrow">BrokersCourt Newsroom</p>
            <h1 class="bli-hero__title">Financial &amp; Broker <span class="bli-hero__accent">News</span></h1>
            <p class="bli-hero__subtitle">
                Independent coverage on brokers, markets, regulation, and trading — researched, edited, and published by the BrokersCourt editorial team.
            </p>

            <dl class="bli-hero__stats">
                <div class="bli-stat">
                    <dt>Articles</dt>
                    <dd>{{ number_format($stats['total_posts'] ?? 0) }}</dd>
                </div>
                <div class="bli-stat">
                    <dt>Topics</dt>
                    <dd>{{ number_format($stats['subcategories'] ?? 0) }}</dd>
                </div>
                <div class="bli-stat">
                    <dt>Contributors</dt>
                    <dd>{{ number_format($stats['authors'] ?? 0) }}</dd>
                </div>
                <div class="bli-stat">
                    <dt>Total reads</dt>
                    <dd>{{ number_format($stats['total_views'] ?? 0) }}</dd>
                </div>
            </dl>
        </div>
    </header>

    <div class="container">
        @if($featured)
            <section class="bli-lead" aria-labelledby="bliLeadTitle">
                <div class="bli-lead__grid">
                    <article class="bli-feature">
                        <a href="{{ $featured['url'] }}"
                           class="bli-feature__media"
                           style="--bli-card-gradient: {{ \App\Services\BlogIndexService::insightGradient(0) }}"
                           tabindex="-1"
                           aria-hidden="true">
                            @if(!empty($featured['photo']))
                                <img src="{{ $featured['photo'] }}" alt="" width="760" height="430" decoding="async" fetchpriority="high">
                            @endif
                            <span class="bli-feature__badge">{{ $featured['category'] }}</span>
                        </a>

                        <div class="bli-feature__body">
                            <p class="bli-eyebrow" id="bliLeadTitle">Featured story</p>

                            <h2 class="bli-feature__title">
                                <a href="{{ $featured['url'] }}">{{ $featured['title'] }}</a>
                            </h2>

                            @if(!empty($featured['excerpt']))
                                <p class="bli-feature__excerpt">{{ $featured['excerpt'] }}</p>
                            @endif

                            <div class="bli-feature__footer">
                                <span class="bli-author bli-author--lg">
                                    <span class="bli-author__avatar" aria-hidden="true">
                                        @if(!empty($featured['author_photo']))
                                            <img src="{{ $featured['author_photo'] }}" alt="" loading="lazy" decoding="async">
                                        @else
                                            {{ strtoupper(substr($featured['author'], 0, 1)) }}
                                        @endif
                                    </span>
                                    <span class="bli-author__stack">
                                        @if(!empty($featured['author_url']))
                                            <a href="{{ $featured['author_url'] }}" class="bli-author__name">{{ $featured['author'] }}</a>
                                        @else
                                            <span class="bli-author__name">{{ $featured['author'] }}</span>
                                        @endif
                                        <span class="bli-author__meta">
                                            <time datetime="{{ $featured['date_iso'] }}">{{ $featured['date'] }}</time>
                                            <span class="bli-dot" aria-hidden="true"></span>
                                            {{ $featured['read_time'] }} min read
                                        </span>
                                    </span>
                                </span>

                                <a href="{{ $featured['url'] }}" class="bli-btn bli-btn--primary">
                                    Read story
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </article>

                    @if(count($topStories))
                        <aside class="bli-toplist" aria-labelledby="bliTopTitle">
                            <h2 class="bli-toplist__title" id="bliTopTitle">Top stories</h2>
                            <ol class="bli-toplist__list">
                                @foreach($topStories as $index => $story)
                                    <li>
                                        <a href="{{ $story['url'] }}" class="bli-toplist__item">
                                            <span class="bli-toplist__rank" aria-hidden="true">{{ str_pad($index + 2, 2, '0', STR_PAD_LEFT) }}</span>
                                            <span class="bli-toplist__body">
                                                <span class="bli-toplist__category">{{ $story['category'] }}</span>
                                                <span class="bli-toplist__headline">{{ $story['title'] }}</span>
                                                <span class="bli-toplist__meta">
                                                    <time datetime="{{ $story['date_iso'] }}">{{ $story['date_short'] }}</time>
                                                    <span class="bli-dot" aria-hidden="true"></span>
                                                    {{ $story['read_time'] }} min read
                                                </span>
                                            </span>
                                        </a>
                                    </li>
                                @endforeach
                            </ol>
                        </aside>
                    @endif
                </div>
            </section>
        @endif

        <nav class="bli-topics" aria-label="Article topics">
            <p class="bli-topics__label">Browse by topic</p>
            <ul class="bli-topics__list">
                @foreach($tabs as $tab)
                    <li>
                        <a href="{{ $tab['url'] }}"
                           class="bli-topic {{ $activeTab === $tab['slug'] ? 'is-active' : '' }}"
                           @if($activeTab === $tab['slug']) aria-current="page" @endif>
                            <span class="bli-topic__label">{{ $tab['name'] }}</span>
                            <span class="bli-topic__count">{{ $tab['count'] }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>

        <div class="bli-layout">
            <main class="bli-main">
                <div class="bli-section__head">
                    <div>
                        <p class="bli-eyebrow">{{ $activeTab === 'all' ? 'Latest coverage' : 'Topic archive' }}</p>
                        <h2 class="bli-section__title">{{ $activeTabName }}</h2>
                    </div>
                    <p class="bli-results-count">
                        {{ number_format($cardCount) }} {{ \Illuminate\Support\Str::plural('article', $cardCount) }}
                    </p>
                </div>

                @if(count($cards))
                    <div class="bli-grid">
                        @foreach($cards as $index => $post)
                            @include('front.blog.partials.article_card', [
                                'post' => $post,
                                'index' => $index + 1,
                            ])
                        @endforeach
                    </div>
                @elseif(!$featured)
                    <div class="bli-empty">
                        <p>No articles in this topic yet. Choose another topic or check back soon.</p>
                        <a href="{{ route('blog') }}" class="bli-btn bli-btn--ghost">View all news</a>
                    </div>
                @else
                    <div class="bli-empty bli-empty--soft">
                        <p>That is every article published in this topic so far. More coverage is on the way.</p>
                    </div>
                @endif
            </main>

            <aside class="bli-aside">
                @if(count($mostRead))
                    <section class="bli-panel" aria-labelledby="bliMostReadTitle">
                        <h2 class="bli-panel__title" id="bliMostReadTitle">Most read</h2>
                        <ol class="bli-ranklist">
                            @foreach($mostRead as $index => $post)
                                <li>
                                    <a href="{{ $post['url'] }}" class="bli-ranklist__item">
                                        <span class="bli-ranklist__rank" aria-hidden="true">{{ $index + 1 }}</span>
                                        <span class="bli-ranklist__body">
                                            <span class="bli-ranklist__headline">{{ $post['title'] }}</span>
                                            <span class="bli-ranklist__meta">
                                                {{ $post['category'] }}
                                                <span class="bli-dot" aria-hidden="true"></span>
                                                {{ $post['read_time'] }} min read
                                            </span>
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        </ol>
                    </section>
                @endif

                <section class="bli-panel" aria-labelledby="bliStandardsTitle">
                    <h2 class="bli-panel__title" id="bliStandardsTitle">How we publish</h2>
                    <ul class="bli-standards">
                        <li>
                            <span class="bli-standards__icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            </span>
                            <span>
                                <strong>Independent research</strong>
                                Written for traders comparing brokers and navigating volatile markets — never promotional copy.
                            </span>
                        </li>
                        <li>
                            <span class="bli-standards__icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </span>
                            <span>
                                <strong>Editorial review</strong>
                                Every article passes writing, editing, and fact-checking before it reaches the feed.
                            </span>
                        </li>
                        <li>
                            <span class="bli-standards__icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M12 8v5l3 2"/><circle cx="12" cy="12" r="9"/></svg>
                            </span>
                            <span>
                                <strong>Kept current</strong>
                                Market and regulation coverage is refreshed as conditions change, with visible publish dates.
                            </span>
                        </li>
                    </ul>
                    <a href="{{ route('authors') }}" class="bli-panel__link">Meet our contributors</a>
                </section>

                <section class="bli-panel bli-panel--cta" aria-labelledby="bliAsideCtaTitle">
                    <h2 class="bli-panel__title" id="bliAsideCtaTitle">Put the research to work</h2>
                    <p>Compare regulated brokers side by side using the same data behind our reporting.</p>
                    <a href="{{ route('broker.reviews.index') }}" class="bli-btn bli-btn--primary bli-btn--block">
                        Browse broker reviews
                    </a>
                </section>
            </aside>
        </div>

        @if(count($readNext))
            <section class="bli-next" aria-labelledby="bliNextTitle">
                <div class="bli-section__head">
                    <div>
                        <p class="bli-eyebrow">Keep exploring</p>
                        <h2 class="bli-section__title" id="bliNextTitle">What to read next</h2>
                    </div>
                    <a href="{{ route('blog') }}" class="bli-section__link">
                        All articles
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>

                <div class="bli-grid bli-grid--three">
                    @foreach($readNext as $index => $post)
                        @include('front.blog.partials.article_card', [
                            'post' => $post,
                            'index' => $index + 4,
                            'variant' => 'compact',
                        ])
                    @endforeach
                </div>
            </section>
        @endif

        <section class="bli-cta" aria-labelledby="bliCtaTitle">
            <div class="bli-cta__copy">
                <p class="bli-eyebrow bli-eyebrow--invert">Next step</p>
                <h2 id="bliCtaTitle">Turn market insight into a better broker choice</h2>
                <p>Our reporting and our broker data come from the same research desk. Compare regulation, fees, and platforms before you open an account.</p>
            </div>
            <div class="bli-cta__actions">
                <a href="{{ route('broker.reviews.index') }}" class="bli-btn bli-btn--solid">Compare brokers</a>
                <a href="{{ route('find_my_broker') }}" class="bli-btn bli-btn--outline">Find my broker</a>
            </div>
        </section>
    </div>
</div>
@endsection
