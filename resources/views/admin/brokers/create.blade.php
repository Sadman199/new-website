@extends('admin.layout.app')
@include('admin.brokers._assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')

@section('heading', 'Add broker')

@section('main_content')
<div class="ab-page">
    <div class="ab-wrap">
        <header class="ab-header">
            <div>
                <p class="ab-header__eyebrow">Brokers</p>
                <h1 class="ab-header__title">Add a broker</h1>
                <p class="ab-header__sub">Start with identity, then regulation, trading conditions, SEO, and publish settings.</p>
            </div>
            <div class="ab-header__actions">
                <a href="{{ route('admin_broker_show') }}" class="ab-btn ab-btn--ghost">Back to list</a>
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

        <div class="ab-layout">
            <nav class="ab-nav" aria-label="Form sections">
                <a href="#identity" data-ab-nav class="is-active">1. Identity</a>
                <a href="#regulation" data-ab-nav>2. Regulation</a>
                <a href="#trading" data-ab-nav>3. Trading</a>
                <a href="#seo" data-ab-nav>4. SEO</a>
                <a href="#publish" data-ab-nav>5. Publish</a>
            </nav>

            <form action="{{ route('admin_broker_store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('admin.brokers._form', ['broker' => $broker, 'formOptions' => $formOptions])
            </form>
        </div>
    </div>
</div>
@endsection
