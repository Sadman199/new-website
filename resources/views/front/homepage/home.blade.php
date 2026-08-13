@extends('front.layout.app')
@section('title', 'BrokersCourt — Compare Forex Brokers & Find Your Match')
@section('meta_description', 'Independent broker research and comparison. Filter ' . ($brokerCount ?? 10) . '+ forex brokers by regulation, cost, leverage, and platform — read expert reviews and find your match.')
@section('canonical', route('home'))

@push('head')
    <link rel="stylesheet" href="{{ asset('css/insight-cards.css') }}?v=1">
    <link rel="stylesheet" href="{{ asset('css/broker-match-quiz.css') }}?v=8">
    <link rel="stylesheet" href="{{ asset('css/homepage.css') }}?v=46">
@endpush

@section('main_content')
<div class="bc-home">
    @include('front.homepage.inc.hero_search')
    @include('front.homepage.inc.personalized_home')
    @include('front.homepage.inc.broker_picks')
    @include('front.homepage.inc.broker_match_quiz')
    @include('front.homepage.inc.broker_sentiment')
    @include('front.homepage.inc.news_insights')
    @include('front.homepage.inc.explore_categories')
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/homepage.js') }}?v=14"></script>
<script src="{{ asset('js/broker-match-quiz.js') }}?v=6"></script>
@endpush
