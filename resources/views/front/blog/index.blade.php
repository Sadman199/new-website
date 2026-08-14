@extends('front.layout.app')

@section('title', 'Financial & Broker News — Daily Market Updates | BrokersCourt')
@section('meta_description', 'Stay ahead with BrokersCourt news: broker updates, market analysis, regulation insights, and trading education from our editorial team.')
@section('canonical', route('blog'))

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/blog-index.css') }}?v=6">
    <link rel="stylesheet" href="{{ asset('css/insight-cards.css') }}?v=3">
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

            <p class="bli-hero__eyebrow">BrokersCourt News</p>
            <h1 class="bli-hero__title">Financial &amp; Broker <span class="bli-hero__accent">News</span></h1>
            <p class="bli-hero__subtitle">
                Independent coverage on brokers, markets, regulation, and trading — updated by the BrokersCourt editorial team.
            </p>

        </div>
    </header>

    <div class="container">
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

        <section class="bli-section" aria-labelledby="bliNewsTitle">
            <div class="bli-section__head">
                <h2 class="bli-section__title" id="bliNewsTitle">{{ $activeTabName }}</h2>
                <p class="bli-results-count" id="bliResultsCount">
                    Showing {{ $cardCount }} of {{ $cardLimit }} {{ \Illuminate\Support\Str::plural('article', $cardLimit) }}
                </p>
            </div>

            @if(count($cards))
                <div class="bc-insights__grid" id="bliPostGrid">
                    @foreach($cards as $index => $post)
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
                    <div class="bli-trust__icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                    <h3>Independent research</h3>
                    <p>Articles are written for traders comparing brokers and navigating volatile markets — not promotional copy.</p>
                </article>
                <article class="bli-trust__card">
                    <div class="bli-trust__icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </div>
                    <h3>Editorial review</h3>
                    <p>Content goes through writing, editing, and fact-checking workflows before it reaches the news feed.</p>
                </article>
                <article class="bli-trust__card">
                    <div class="bli-trust__icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                    </div>
                    <h3>Topic-driven tabs</h3>
                    <p>Browse by subcategory to jump straight to broker news, market analysis, regulation, or platform guides.</p>
                </article>
            </div>
        </section>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/blog-index.js') }}?v=3" defer></script>
@endpush
