@extends('front.layout.app')

@section('title', $guide->seoTitle())
@section('meta_description', $guide->seoDescription())
@section('canonical', app(\App\Services\BrokerGuideService::class)->publicUrl($guide))
@section('og_image', $guide->broker?->logo ?: '')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/best-broker-guide.css') }}?v=6">
    <link rel="stylesheet" href="{{ asset('css/broker-review.css') }}?v=12">
    <link rel="stylesheet" href="{{ asset('css/broker-guide-page.css') }}?v=1">
@endpush

@section('main_content')
@include('front.brokers.partials.guide_page_body')
@endsection

@push('scripts')
    <script src="{{ asset('js/best-broker-guide.js') }}?v=6" defer></script>
@endpush
