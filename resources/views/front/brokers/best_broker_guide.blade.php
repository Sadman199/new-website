@extends('front.layout.app')

@php
    $pageTitle = $guidePage['guide']['meta_title'] ?? $guidePage['guide']['title'];
    $metaDescription = $guidePage['guide']['meta_description'] ?? '';
@endphp

@section('title', $pageTitle)
@section('meta_description', $metaDescription)

@push('head')
    <link rel="stylesheet" href="{{ asset('css/best-broker-guide.css') }}?v=6">
    <link rel="stylesheet" href="{{ asset('css/insight-cards.css') }}?v=1">
@endpush

@section('main_content')
<div class="bbg-page">
    <header class="bbg-hero">
        <div class="bbg-container">
            <nav class="bbg-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <a href="{{ route('brokers.best.index') }}">Best brokers</a>
                <span aria-hidden="true">/</span>
                <span>{{ $guidePage['guide']['breadcrumb'] ?? $guidePage['label'] }}</span>
            </nav>

            <p class="bbg-hero__eyebrow">Independent broker research</p>
            <h1 class="bbg-hero__title">{{ $guidePage['guide']['title'] }}</h1>

            @include('front.brokers.partials.best_guide_hero_author', [
                'editorialTeam' => $guidePage['editorial_team'] ?? [],
                'guidePage' => $guidePage,
            ])

            @if($guidePage['is_empty'])
                <p class="bbg-hero__note">We are updating broker matches for {{ $guidePage['label'] }}. Use our broker finder to compare regulated platforms by fees, regulation, and platform.</p>
            @endif

            <div class="bbg-hero__actions">
                <a href="{{ route('methodology') }}" class="bbg-hero__method-link">Our methodology</a>
            </div>
        </div>
    </header>

    <div class="bbg-container">
        <div class="bbg-layout">
            @if(! $guidePage['is_empty'])
                <aside class="bbg-sidebar" aria-label="Page sections">
                    <div class="bbg-sidebar__inner">
                        <p class="bbg-sidebar__label">On this page</p>
                        <nav class="bbg-toc" aria-label="Table of contents">
                            <ul class="bbg-toc__list">
                                @foreach($guidePage['toc'] as $item)
                                    <li>
                                        <a href="#{{ $item['id'] }}" class="bbg-toc__link">{{ $item['label'] }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </nav>
                    </div>
                </aside>
            @endif

            <main class="bbg-main @if($guidePage['is_empty']) bbg-main--full @endif">
                @if(! $guidePage['is_empty'])
                    <div class="bbg-mobile-toc" aria-label="Jump to section">
                        <label for="bbg-mobile-toc-select" class="bbg-sr-only">Jump to section</label>
                        <select id="bbg-mobile-toc-select" class="bbg-mobile-toc__select">
                            @foreach($guidePage['toc'] as $item)
                                <option value="{{ $item['id'] }}">{{ $item['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                @if($guidePage['is_empty'])
                    <section class="bbg-empty">
                        <h2 class="bbg-section__title">No matching brokers yet</h2>
                        <p class="bbg-section__text">We could not find brokers tagged for this listing in our current database. Try our advanced search or browse all broker reviews.</p>
                        <div class="bbg-spotlight__actions">
                            <a href="{{ route('find_my_broker') }}" class="bbg-btn bbg-btn--primary">Find my broker</a>
                            <a href="{{ route('broker.reviews.index') }}" class="bbg-btn bbg-btn--ghost">All broker reviews</a>
                        </div>
                    </section>
                @else
                    @include('front.brokers.partials.best_guide_content')
                @endif

                @include('front.brokers.partials.best_guide_methodology', ['guidePage' => $guidePage])

                <section class="bbg-section bbg-faq" id="faq">
                    <h2 class="bbg-section__title">FAQ</h2>
                    <div class="bbg-accordion">
                        @foreach($guidePage['guide']['faqs'] as $index => $faq)
                            <details class="bbg-accordion__item" @if($index === 0) open @endif>
                                <summary>{{ $faq['question'] }}</summary>
                                <p>{{ $faq['answer'] }}</p>
                            </details>
                        @endforeach
                    </div>
                </section>

                <p class="bbg-disclaimer">Everything on BrokersCourt is based on verified broker data and independent research. We may receive compensation from brokers we feature. Trading forex and CFDs involves significant risk.</p>
            </main>
        </div>

        @if(!empty($latestPosts))
            <section class="bbg-blog" id="latest-blog">
                <div class="bli-section__head">
                    <h2 class="bli-section__title">Latest blog</h2>
                    <a href="{{ route('blog') }}" class="bbg-blog__link">View all articles</a>
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
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/best-broker-guide.js') }}?v=6" defer></script>
@endpush
