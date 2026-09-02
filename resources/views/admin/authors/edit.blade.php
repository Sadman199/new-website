@extends('admin.layout.app')
@include('admin.partials._ab_assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')
@section('heading', 'Edit Author')

@section('main_content')
<div class="ab-page ab-page--hub">
    <div class="ab-wrap">
        <header class="ab-header">
            <div>
                <p class="ab-header__eyebrow">Edit Author</p>
                <h1 class="ab-header__title">{{ $author->name }}</h1>
                <p class="ab-header__sub">{{ $author->email }}</p>
            </div>
            <div class="ab-header__actions">
                <a href="{{ route('admin_author_view', $author->id) }}" class="ab-btn ab-btn--ghost">View</a>
                <a href="{{ $author->profileUrl() }}" class="ab-btn ab-btn--ghost" target="_blank" rel="noopener">Open live</a>
                <a href="{{ route('admin_author_show') }}" class="ab-btn ab-btn--ghost">All authors</a>
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

        <form action="{{ route('admin_author_update', $author->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.authors._form')
            <div class="ab-save">
                <button type="submit" class="ab-btn ab-btn--primary">
                    <i class="fas fa-save" aria-hidden="true"></i>
                    Save changes
                </button>
                <a href="{{ route('admin_author_show') }}" class="ab-btn ab-btn--ghost">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
