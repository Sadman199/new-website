@extends('admin.layout.app')
@include('admin.brokers._assets')
@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')
@section('heading', 'Add Attribute')
@section('main_content')
<div class="ab-page"><div class="ab-wrap">
    <header class="ab-header"><div><p class="ab-header__eyebrow">Prop firms</p><h1 class="ab-header__title">Add an attribute</h1><p class="ab-header__sub">Filter tags assigned to firms.</p></div><div class="ab-header__actions"><a href="{{ route('admin_prop_firm_attributes_show') }}" class="ab-btn ab-btn--ghost">All attributes</a></div></header>
    @include('admin.prop-firms._nav', ['active' => 'attributes'])
    @if($errors->any())<div class="ab-banner" role="alert"><h3>Please fix {{ $errors->count() }} {{ \Illuminate\Support\Str::plural('issue', $errors->count()) }} before saving</h3><ol>@foreach($errors->all() as $message)<li>{{ $message }}</li>@endforeach</ol></div>@endif
    <form action="{{ route('admin_prop_firm_attributes_store') }}" method="POST">@csrf @include('admin.prop-firms.attributes._form')</form>
</div></div>
@endsection
