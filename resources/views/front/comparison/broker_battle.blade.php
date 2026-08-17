@extends('front.layout.app')

@php
    $left = $battle['broker1'];
    $right = $battle['broker2'];
    $year = $battle['year'] ?? date('Y');
@endphp

@section('title', $left['name'] . ' vs ' . $right['name'] . ' ' . $year . ' – Broker Battle | BrokersCourt')
@section('meta_description', 'Compare ' . $left['name'] . ' and ' . $right['name'] . ' across regulation, spreads, fees, platforms, trading conditions and more. See which broker wins the BrokersCourt Battle.')
@section('canonical', $shareUrl)
@section('og_image', $left['og_image'] ?? ($left['logo'] ?? ''))
@section('og_image_width', (string) \App\Services\BrokerOgImageService::WIDTH)
@section('og_image_height', (string) \App\Services\BrokerOgImageService::HEIGHT)

@push('json_ld')
    <script type="application/ld+json">@json($comparisonJsonLd)</script>
@endpush

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/broker-compare.css') }}?v=9">
    <link rel="stylesheet" href="{{ asset('css/broker-compare-results.css') }}?v=1">
    <link rel="stylesheet" href="{{ asset('css/broker-battle.css') }}?v=3">
@endpush

@section('main_content')
<div class="bc-compare-page bc-result-page bc-battle-page">
    @include('front.comparison.partials.battle.hero')
    @include('front.comparison.partials.battle.scoreboard')

    <div class="container">
        @include('front.comparison.partials.battle.rounds')
        @include('front.comparison.partials.battle.summary')
        @include('front.comparison.partials.battle.winner')
        @include('front.comparison.partials.battle.share')
        @include('front.comparison.partials.battle.restart')

        @if(!empty($popularComparisons))
            <section class="bc-compare-popular" aria-label="Popular comparisons">
                <p class="bc-compare-popular__label">More broker matchups</p>
                <div class="bc-compare-popular__grid">
                    @foreach(array_slice($popularComparisons, 0, 8) as $pair)
                        @php
                            $battleHref = str_replace('/brokers/compare/', '/broker-battle/', $pair['url']);
                        @endphp
                        <a href="{{ $battleHref }}" class="bc-compare-popular__chip">{{ $pair['label'] }}</a>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    window.BROKER_BATTLE = {
        brokers: @json($brokersPayload),
        battleBase: @json(url('/broker-battle')),
        shareUrl: @json($shareUrl),
        shareTitle: @json($battle['share_title'] ?? ($left['name'] . ' vs ' . $right['name'])),
        leftSlug: @json($left['slug']),
        rightSlug: @json($right['slug'])
    };
</script>
<script src="{{ asset('js/broker-battle.js') }}?v=1" defer></script>
@endpush
