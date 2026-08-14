@extends('front.layout.app')

@section('title', $guide->seoTitle())
@section('meta_description', $guide->seoDescription())
@section('canonical', app(\App\Services\BrokerGuideService::class)->publicUrl($guide))
@section('og_image', ($guide->broker?->ogShareImageUrl()) ?: '')
@section('og_image_width', $guide->broker ? (string) \App\Services\BrokerOgImageService::WIDTH : '')
@section('og_image_height', $guide->broker ? (string) \App\Services\BrokerOgImageService::HEIGHT : '')

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/best-broker-guide.css') }}?v=11">
    <link rel="stylesheet" href="{{ asset('css/broker-review.css') }}?v=23">
    <link rel="stylesheet" href="{{ asset('css/broker-guide-page.css') }}?v=2">
@endpush

@section('main_content')
@include('front.brokers.partials.guide_page_body')
@endsection

@push('scripts')
    <script src="{{ asset('js/best-broker-guide.js') }}?v=6" defer></script>
@endpush
