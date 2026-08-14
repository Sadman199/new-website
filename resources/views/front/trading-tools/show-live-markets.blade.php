@extends('front.layout.app')

@section('title', ($meta['title'] ?? $tool->name) . ' | Trading Tools | BrokersCourt')
@section('meta_description', $meta['meta'] ?? $tool->short_description)
@section('canonical', route('trading.tools.show', ['slug' => $slug]))

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/live-markets.css') }}?v=3">
    <link rel="stylesheet" href="{{ asset('css/trading-tools.css') }}?v=6">
@endpush

@section('main_content')
<div class="tt-page">
    <header class="tt-hero tt-hero--compact">
        <div class="tt-hero__bg" aria-hidden="true"></div>
        <div class="tt-wrap">
            <nav class="tt-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <a href="{{ route('trading.tools') }}">Trading tools</a>
                <span aria-hidden="true">/</span>
                <span>{{ $meta['title'] ?? $tool->name }}</span>
            </nav>

            <p class="tt-hero__eyebrow">
                <i class="{{ $tool->icon }}" aria-hidden="true"></i>
                Market data
            </p>
            <h1 class="tt-hero__title">{{ $meta['title'] ?? $tool->name }}</h1>
            <p class="tt-hero__subtitle">{{ $meta['about'] ?? $tool->short_description }}</p>
        </div>
    </header>

    <div class="tt-body">
        <div class="tt-wrap">
            <div class="tt-layout">
                @include('front.trading-tools.partials.sidebar', [
                    'tools' => $tools,
                    'activeSlug' => $toolKey,
                ])

                <div class="tt-tool">
                    <div class="tt-tool__panel tt-tool__panel--markets">
                        @include('front.partials.live_markets_board')
                    </div>
                </div>
            </div>

            <p class="tt-disclaimer">
                Market data is provided by TradingView for informational purposes. Rates and calendar events are
                indicative — verify with your broker before trading.
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/live-markets.js') }}?v=2" defer></script>
@endpush
