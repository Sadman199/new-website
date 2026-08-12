@extends('front.layout.app')

@section('title', $page->seoTitle() . ' | BrokersCourt')
@section('meta_description', $page->seoDescription() ?? \App\Support\SiteTheme::defaultMetaDescription())
@section('canonical', route('cms_page.show', ['slug' => $page->slug]))

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/cms-pages.css') }}?v=2">
@endpush

@section('main_content')
<div class="cms-page cms-page--landing">
    @include('front.cms._sections', ['page' => $page])
</div>
@endsection
