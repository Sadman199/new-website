@extends('admin.layout.app')
@include('admin.brokers._assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')

@section('heading', 'Add Guide Topic')

@section('main_content')
<div class="ab-page">
    <div class="ab-wrap">
        <header class="ab-header">
            <div>
                <p class="ab-header__eyebrow">Guides</p>
                <h1 class="ab-header__title">Add a guide topic</h1>
                <p class="ab-header__sub">Active topics are synced as drafts to every broker review.</p>
            </div>
            <div class="ab-header__actions">
                <a href="{{ route('admin_broker_guide_topics_index') }}" class="ab-btn ab-btn--ghost">All topics</a>
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
                <a href="#content" data-ab-nav class="is-active">1. Content</a>
                <a href="#display" data-ab-nav>2. Display &amp; sync</a>
            </nav>

            <form action="{{ route('admin_broker_guide_topics_store') }}" method="POST">
                @csrf
                @include('admin.broker_guide_topics._form', ['topic' => $topic, 'contextProfiles' => $contextProfiles])
            </form>
        </div>
    </div>
</div>
@endsection
