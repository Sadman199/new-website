@extends('front.layout.app')
@section('title', 'BrokersCourt — Compare Forex Brokers & Find Your Match')
@section('meta_description', 'Independent broker research and comparison. Filter ' . ($brokerCount ?? 10) . '+ forex brokers by regulation, cost, leverage, and platform — read expert reviews and find your match.')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/homepage.css') }}?v=10">
@endpush

@section('main_content')
<div class="bc-home">
    @include('front.homepage.inc.hero_search')
    @include('front.homepage.inc.tools_strip')
    @include('front.homepage.inc.broker_picks')
    @include('front.homepage.inc.compare_preview')
    @include('front.homepage.inc.news_insights')
    @include('front.homepage.inc.bonuses_grid')
    @include('front.homepage.inc.explore_categories')
    @include('front.homepage.inc.trust_cta')
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/homepage.js') }}?v=7"></script>
@endpush
