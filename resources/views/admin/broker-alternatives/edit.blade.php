@extends('admin.layout.app')
@include('admin.partials._ab_assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')
@section('heading', 'Edit alternatives page')

@section('main_content')
<div class="ab-page ab-page--hub">
    <div class="ab-wrap">
        <header class="ab-header">
            <div>
                <p class="ab-header__eyebrow">Broker Alternatives</p>
                <h1 class="ab-header__title">{{ $page->broker->name ?? 'Alternatives page' }}</h1>
                <p class="ab-header__sub">Update SEO, curated picks, and whether the public page is live.</p>
            </div>
            <div class="ab-header__actions">
                @if($page->broker && $page->is_published)
                    <a href="{{ route('broker.alternatives.show', ['slug' => $page->broker->slug]) }}" class="ab-btn ab-btn--ghost" target="_blank" rel="noopener">Open live</a>
                @endif
                <a href="{{ route('admin_broker_alternatives_show') }}" class="ab-btn ab-btn--ghost">All pages</a>
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

        <form action="{{ route('admin_broker_alternatives_update', $page->id) }}" method="POST" novalidate>
            @csrf
            @method('PUT')
            @include('admin.broker-alternatives._form')
        </form>
    </div>
</div>
@endsection
