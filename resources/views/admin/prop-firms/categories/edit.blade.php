@extends('admin.layout.app')
@include('admin.brokers._assets')
@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')
@section('heading', 'Edit Category')
@section('main_content')
<div class="ab-page"><div class="ab-wrap">
    <header class="ab-header">
        <div>
            <p class="ab-header__eyebrow">Edit category</p>
            <h1 class="ab-header__title">{{ $category->name }}</h1>
            <p class="ab-header__sub">{{ $category->slug }}</p>
        </div>
        <div class="ab-header__actions"><a href="{{ route('admin_prop_firm_categories_show') }}" class="ab-btn ab-btn--ghost">All categories</a></div>
    </header>
    @include('admin.prop-firms._nav', ['active' => 'categories'])
    @if($errors->any())
        <div class="ab-banner" role="alert"><h3>Please fix {{ $errors->count() }} {{ \Illuminate\Support\Str::plural('issue', $errors->count()) }} before saving</h3><ol>@foreach($errors->all() as $message)<li>{{ $message }}</li>@endforeach</ol></div>
    @endif
    <form action="{{ route('admin_prop_firm_categories_update', $category->id) }}" method="POST">@csrf @method('PUT') @include('admin.prop-firms.categories._form')</form>
</div></div>
@endsection
