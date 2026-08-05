@extends('front.layout.app')

@php
    $pageTitle = $guidePage['guide']['meta_title'] ?? $guidePage['guide']['title'];
    $metaDescription = $guidePage['guide']['meta_description'] ?? '';
@endphp

@section('title', $pageTitle)
@section('meta_description', $metaDescription)

@push('head')
    <link rel="stylesheet" href="{{ asset('css/best-broker-guide.css') }}?v=2">
@endpush

@section('main_content')
<div class="bbg-page">
    <div class="bbg-container">
        <nav class="bbg-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span aria-hidden="true">/</span>
            <a href="{{ route('brokers.best.index') }}">Best brokers</a>
            <span aria-hidden="true">/</span>
            <span>{{ $guidePage['guide']['breadcrumb'] ?? $guidePage['label'] }}</span>
        </nav>

        <header class="bbg-hero">
            <p class="bbg-hero__eyebrow">Independent broker research · Updated {{ $guidePage['updated_at'] }}</p>
            <h1 class="bbg-hero__title">{{ $guidePage['guide']['title'] }}</h1>
            @if($guidePage['is_empty'])
                <p class="bbg-hero__lead">We are updating broker matches for {{ $guidePage['label'] }}. Use our broker finder to compare regulated platforms by fees, regulation, and platform.</p>
            @else
                <p class="bbg-hero__lead">{{ $guidePage['guide']['winner_intro'] }}</p>
            @endif
            <a href="{{ route('methodology') }}" class="bbg-hero__method-link">Learn about our methodology</a>
        </header>

        <div class="bbg-layout">
            @if(! $guidePage['is_empty'])
                <aside class="bbg-sidebar" aria-label="Page sections">
                    <div class="bbg-sidebar__inner">
                        <p class="bbg-sidebar__label">On this page</p>
                        <ul class="bbg-toc">
                            @foreach($guidePage['toc'] as $item)
                                <li>
                                    <a href="#{{ $item['id'] }}" class="bbg-toc__link">{{ $item['label'] }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </aside>
            @endif

            <main class="bbg-main @if($guidePage['is_empty']) bbg-main--full @endif">
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

                <section class="bbg-section" id="methodology">
                    <h2 class="bbg-section__title">{{ $guidePage['guide']['methodology']['title'] }}</h2>
                    <p class="bbg-section__text">{{ $guidePage['guide']['methodology']['intro'] }}</p>
                    <ul class="bbg-checklist">
                        @foreach($guidePage['guide']['methodology']['points'] as $point)
                            <li>{{ $point }}</li>
                        @endforeach
                    </ul>
                </section>

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
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/best-broker-guide.js') }}?v=2" defer></script>
@endpush
