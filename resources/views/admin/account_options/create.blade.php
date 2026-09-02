@extends('admin.layout.app')
@include('admin.brokers._assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')

@section('heading', 'Add Account Option')

@section('main_content')
<div class="ab-page">
    <div class="ab-wrap">
        <header class="ab-header">
            <div class="ab-identity">
                <span class="ab-logo ab-logo--lg">
                    @if($broker->mediaUrl())
                        <img src="{{ $broker->mediaUrl() }}" alt="">
                    @else
                        <span>{{ strtoupper(substr($broker->name, 0, 1)) }}</span>
                    @endif
                </span>
                <div>
                    <p class="ab-header__eyebrow">New account option</p>
                    <h1 class="ab-header__title">{{ $broker->name }}</h1>
                    <p class="ab-header__sub">Add a Standard, ECN, Islamic, or other account type for this broker.</p>
                </div>
            </div>
            <div class="ab-header__actions">
                <a href="{{ route('admin_account_options_index', $broker->id) }}" class="ab-btn ab-btn--ghost">Back to accounts</a>
            </div>
        </header>

        @include('admin.brokers._tabs', ['broker' => $broker, 'activeTab' => 'account-options'])

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

        <div class="ab-layout">
            <nav class="ab-nav" aria-label="Form sections">
                <a href="#profile" data-ab-nav class="is-active">1. Profile &amp; costs</a>
                <a href="#limits" data-ab-nav>2. Limits &amp; features</a>
            </nav>

            <form action="{{ route('admin_account_options_store', $broker->id) }}" method="POST">
                @csrf
                @include('admin.account_options._form', ['broker' => $broker, 'formOptions' => $formOptions])
            </form>
        </div>
    </div>
</div>
@endsection
