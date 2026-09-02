@extends('admin.layout.app')
@include('admin.brokers._assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')

@section('heading', 'Add Prop Firm')

@section('main_content')
<div class="ab-page">
    <div class="ab-wrap">
        <header class="ab-header">
            <div>
                <p class="ab-header__eyebrow">Prop firms</p>
                <h1 class="ab-header__title">Add a prop firm</h1>
                <p class="ab-header__sub">Start with identity, then funding, ratings, programs, FAQs, and publish settings.</p>
            </div>
            <div class="ab-header__actions">
                <a href="{{ route('admin_prop_firms_show') }}" class="ab-btn ab-btn--ghost">Back to list</a>
            </div>
        </header>

        @include('admin.prop-firms._nav', ['active' => 'firms'])

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
                <a href="#funding" data-ab-nav>2. Funding</a>
                <a href="#ratings" data-ab-nav>3. Ratings</a>
                <a href="#programs" data-ab-nav>4. Programs</a>
                <a href="#attributes" data-ab-nav>5. Attributes</a>
                <a href="#faqs" data-ab-nav>6. FAQs</a>
                <a href="#seo" data-ab-nav>7. SEO</a>
                <a href="#publish" data-ab-nav>8. Publish</a>
            </nav>

            <form action="{{ route('admin_prop_firms_store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('admin.prop-firms._form')
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@include('admin.prop-firms._form_scripts')
@endpush
