@extends('front.layout.app')

@section('title', 'Financial & Broker News — Daily Market Updates | BrokersCourt')
@section('meta_description', 'Stay ahead with BrokersCourt news: broker updates, market analysis, regulation insights, and trading education from our editorial team.')

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/blog-index.css') }}?v=2">
@endpush

@section('main_content')
<div class="bli-page">
    <div class="bli-wrap">
        <header class="bli-hero">
            <span class="bli-hero__badge">BrokersCourt News</span>
            <h1 class="bli-hero__title">Financial &amp; Broker News</h1>
            <p class="bli-hero__subtitle">
                Independent coverage on brokers, markets, regulation, and trading — updated by the BrokersCourt editorial team.
            </p>
        </header>

        <nav class="bli-tabs" aria-label="Blog subcategories" id="bliTabs">
            <div class="bli-tabs__scroll">
                @foreach($tabs as $tab)
                    <a href="{{ $tab['url'] }}"
                       class="bli-tab {{ $activeTab === $tab['slug'] ? 'is-active' : '' }}"
                       data-bli-tab="{{ $tab['slug'] }}"
                       @if($activeTab === $tab['slug']) aria-current="page" @endif>
                        <span class="bli-tab__label">{{ $tab['name'] }}</span>
                        <span class="bli-tab__count">{{ $tab['count'] }}</span>
                    </a>
                @endforeach
            </div>
        </nav>

        @if($latestHeadline)
            <div class="bli-ticker" aria-label="Latest headline">
                <span class="bli-ticker__badge">Latest</span>
                <a href="{{ $latestHeadline['url'] }}" class="bli-ticker__link">
                    {{ $latestHeadline['title'] }}
                </a>
            </div>
        @endif

        <div class="bli-stats">
            <div class="bli-stat">
                <span class="bli-stat__value">{{ number_format($stats['total_posts']) }}</span>
                <span class="bli-stat__label">Articles</span>
            </div>
            <div class="bli-stat">
                <span class="bli-stat__value">{{ $stats['subcategories'] }}</span>
                <span class="bli-stat__label">Topics</span>
            </div>
            <div class="bli-stat">
                <span class="bli-stat__value">{{ number_format($stats['total_views']) }}</span>
                <span class="bli-stat__label">Total reads</span>
            </div>
            <div class="bli-stat">
                <span class="bli-stat__value">{{ $stats['authors'] }}</span>
                <span class="bli-stat__label">Contributors</span>
            </div>
        </div>

        <section class="bli-section" aria-labelledby="bliNewsTitle">
            <div class="bli-section__head">
                <h2 class="bli-section__title" id="bliNewsTitle">{{ $activeTabName }}</h2>
                <p class="bli-results-count" id="bliResultsCount">
                    Showing {{ $cardCount }} of {{ $cardLimit }} {{ \Illuminate\Support\Str::plural('article', $cardLimit) }}
                </p>
            </div>

            @if(count($cards))
                <div class="bli-grid" id="bliPostGrid">
                    @foreach($cards as $post)
                        @include('front.blog.partials.post_card', ['post' => $post, 'variant' => 'grid'])
                    @endforeach
                </div>
            @else
                <div class="bli-empty">
                    <p>No articles in this category yet. Try another tab or check back soon.</p>
                </div>
            @endif
        </section>

        <section class="bli-trust" aria-labelledby="bliTrustTitle">
            <h2 class="bli-trust__title" id="bliTrustTitle">How we publish</h2>
            <div class="bli-trust__grid">
                <article class="bli-trust__card">
                    <h3>Independent research</h3>
                    <p>Articles are written for traders comparing brokers and navigating volatile markets — not promotional copy.</p>
                </article>
                <article class="bli-trust__card">
                    <h3>Editorial review</h3>
                    <p>Content goes through writing, editing, and fact-checking workflows before it reaches the news feed.</p>
                </article>
                <article class="bli-trust__card">
                    <h3>Topic-driven tabs</h3>
                    <p>Browse by subcategory to jump straight to broker news, market analysis, regulation, or platform guides.</p>
                </article>
            </div>
        </section>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/blog-index.js') }}?v=2" defer></script>
@endpush
