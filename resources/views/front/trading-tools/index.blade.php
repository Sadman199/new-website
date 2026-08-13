@extends('front.layout.app')

@section('title', 'Trading Tools | Free Forex Calculators | BrokersCourt')
@section('meta_description', 'Free forex trading calculators — pip value, position size, profit/loss, margin, risk, pivot points, Fibonacci, currency converter, and live market widgets. Each tool has its own dedicated page.')
@section('canonical', route('trading.tools'))

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/trading-tools.css') }}?v=4">
@endpush

@section('main_content')
<div class="tt-page">
    <header class="tt-hero">
        <div class="tt-hero__bg" aria-hidden="true"></div>
        <div class="tt-wrap">
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

            @include('front.partials.hero_metrics', [
                'stats' => [
                    ['label' => 'Tools', 'value' => $tools->count(), 'tone' => 'highlight'],
                    ['label' => 'Results', 'value' => 'Instant'],
                    ['label' => 'Cost', 'value' => 'Free'],
                    ['label' => 'Updated', 'value' => date('Y')],
                ],
            ])
        </div>
    </header>

    <div class="tt-body">
        <div class="container tt-wrap">
            <div class="tt-hub">
                @foreach($tools as $tool)
                    <a href="{{ route('trading.tools.show', ['slug' => $tool->route_slug]) }}" class="tt-hub-card">
                        <span class="tt-hub-card__icon" aria-hidden="true">
                            <i class="{{ $tool->icon }}"></i>
                        </span>
                        <span class="tt-hub-card__body">
                            <strong class="tt-hub-card__title">{{ $tool->page_title ?? $tool->name }}</strong>
                            <span class="tt-hub-card__desc">{{ $tool->page_about ?? $tool->short_description }}</span>
                        </span>
                        <span class="tt-hub-card__arrow" aria-hidden="true">
                            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/></svg>
                        </span>
                    </a>
                @endforeach
            </div>

            <p class="tt-disclaimer">
                Tools use standard forex formulas with reference FX rates for planning. They are educational and do not
                constitute trading advice. Always verify with your broker’s contract specifications.
            </p>
        </div>
    </div>
</div>
@endsection
