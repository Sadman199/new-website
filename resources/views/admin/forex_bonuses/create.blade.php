@extends('admin.layout.app')
@include('admin.forex_bonuses._assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')
@section('heading', 'Add Bonus')

@section('main_content')
<div class="ab-page ab-page--bonus">
    <div class="ab-wrap">
        <header class="ab-header">
            <div>
                <p class="ab-header__eyebrow">Forex Bonuses</p>
                <h1 class="ab-header__title">Add Bonus</h1>
                <p class="ab-header__sub">Fill in the offer details, then save. Featured bonuses appear on the homepage.</p>
            </div>
            <div class="ab-header__actions">
                <a href="{{ route('admin_forex_bonus_show') }}" class="ab-btn ab-btn--ghost">All bonuses</a>
            </div>
        </header>

        @if($errors->any())
            <div class="ab-banner" role="alert">
                <h3>Please fix {{ $errors->count() }} {{ \Illuminate\Support\Str::plural('issue', $errors->count()) }} before saving</h3>
                <ol>
                    @foreach($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ol>
            </div>
        @endif

        <form action="{{ route('admin_forex_bonus_store') }}" method="POST" enctype="multipart/form-data" class="ab-bonus-form" novalidate>
            @csrf
            @include('admin.forex_bonuses._form')
        </form>
    </div>
</div>
@endsection
