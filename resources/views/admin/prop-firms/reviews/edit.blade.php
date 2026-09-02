@extends('admin.layout.app')
@include('admin.brokers._assets')
@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')
@section('heading', 'Edit Review')
@section('main_content')
<div class="ab-page"><div class="ab-wrap">
    <header class="ab-header"><div><p class="ab-header__eyebrow">Edit review</p><h1 class="ab-header__title">{{ $review->title }}</h1><p class="ab-header__sub">{{ $review->propFirm?->name }}</p></div><div class="ab-header__actions"><a href="{{ route('admin_prop_firm_reviews_show') }}" class="ab-btn ab-btn--ghost">All reviews</a></div></header>
    @include('admin.prop-firms._nav', ['active' => 'reviews'])
    @if($errors->any())<div class="ab-banner" role="alert"><h3>Please fix {{ $errors->count() }} {{ \Illuminate\Support\Str::plural('issue', $errors->count()) }} before saving</h3><ol>@foreach($errors->all() as $message)<li>{{ $message }}</li>@endforeach</ol></div>@endif
    <form action="{{ route('admin_prop_firm_reviews_update', $review->id) }}" method="POST">@csrf @method('PUT') @include('admin.prop-firms.reviews._form')</form>
</div></div>
@endsection
