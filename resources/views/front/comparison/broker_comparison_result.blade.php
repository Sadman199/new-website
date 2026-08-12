@extends('front.layout.app')

@section('title', $comparison['broker1']['name'] . ' vs ' . $comparison['broker2']['name'] . ' | Broker Comparison | BrokersCourt')
@section('meta_description', 'Compare ' . $comparison['broker1']['name'] . ' and ' . $comparison['broker2']['name'] . ' side by side — regulation, spreads, platforms, deposits, safety scores, and more.')
@section('canonical', $shareUrl)
@section('og_image', $comparison['broker1']['logo'] ?? '')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/broker-compare.css') }}?v=6">
@endpush

@section('main_content')
@php
    $left = $comparison['broker1'];
    $right = $comparison['broker2'];
    $winner = $comparison['overall_winner'];
@endphp

<div class="bc-compare-page bc-result-page">
    @include('front.comparison.partials.result_hero')

    @include('front.comparison.partials.result_score_bars')
    @include('front.comparison.partials.result_promos')

    <div class="bc-compare-wrap">
        @if(!empty($popularComparisons))
            <section class="bc-compare-popular" aria-label="Popular comparisons">
                <p class="bc-compare-popular__label">More comparisons</p>
                <div class="bc-compare-popular__grid">
                    @foreach(array_slice($popularComparisons, 0, 8) as $pair)
                        @if(!str_contains($pair['url'], $broker1->slug . '-vs-' . $broker2->slug) && !str_contains($pair['url'], $broker2->slug . '-vs-' . $broker1->slug))
                            <a href="{{ $pair['url'] }}" class="bc-compare-popular__chip">{{ $pair['label'] }}</a>
                        @endif
                    @endforeach
                </div>
            </section>
        @endif

        <div class="bc-result-shell">
            <div class="bc-result-layout">
                @include('front.comparison.partials.result_sidebar')
                @include('front.comparison.partials.result_sections')
            </div>
        </div>

        @include('front.comparison.partials.result_footer_cta')
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/broker-compare.js') }}?v=7" defer></script>
@endpush
