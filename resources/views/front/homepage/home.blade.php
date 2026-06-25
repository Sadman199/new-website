@extends('front.layout.app')
@section('title', 'BrokersCourt | Find, Compare, and Connect with Top Brokers')

@section('main_content')
@php
$current_short_name = session()->get('session_short_name', $global_short_name);
$current_language_id = \App\Models\Language::where('short_name', $current_short_name)->first()->id;
@endphp

@if($setting_data->news_ticker_status == "Show")
    <!-- Content here -->
@endif

    @include("front.homepage.inc.hero") 
    @include("front.homepage.inc.bonuses") 
    @include("front.homepage.inc.best_brokers") 
    @include("front.homepage.inc.all_broker") 
    @include("front.homepage.inc.avoid_broker") 
    @include("front.homepage.inc.regulated_broker") 


@endsection
