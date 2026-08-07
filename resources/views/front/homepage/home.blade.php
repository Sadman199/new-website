@extends('front.layout.app')
@section('title', 'BrokersCourt — Compare Forex Brokers & Find Your Match')
@section('meta_description', 'Independent broker research and comparison. Filter ' . ($brokerCount ?? 10) . '+ forex brokers by regulation, cost, leverage, and platform — read expert reviews and find your match.')

@push('head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="{{ asset('css/insight-cards.css') }}?v=1">
    <link rel="stylesheet" href="{{ asset('css/live-markets.css') }}?v=1">
    <link rel="stylesheet" href="{{ asset('css/homepage.css') }}?v=34">
@endpush

@section('main_content')
<div class="bc-home">
    @include('front.homepage.inc.hero_search')
    @include('front.homepage.inc.broker_picks')
    @include('front.homepage.inc.broker_sentiment')
    @include('front.homepage.inc.live_markets')
    @include('front.homepage.inc.news_insights')
    @include('front.homepage.inc.explore_categories')

  
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/homepage.js') }}?v=11"></script>
<script src="{{ asset('js/live-markets.js') }}?v=1"></script>
@endpush
