@extends('admin.layout.app')
@include('admin.partials._ab_assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')
@section('heading', 'Add Author')

@section('main_content')
<div class="ab-page ab-page--hub">
    <div class="ab-wrap">
        <header class="ab-header">
            <div>
                <p class="ab-header__eyebrow">Authors</p>
                <h1 class="ab-header__title">Add Author</h1>
                <p class="ab-header__sub">Create a login and choose which editorial credits they can receive.</p>
            </div>
            <div class="ab-header__actions">
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

        <form action="{{ route('admin_author_store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.authors._form')
            <div class="ab-save">
                <button type="submit" class="ab-btn ab-btn--primary">
                    <i class="fas fa-save" aria-hidden="true"></i>
                    Create Author
                </button>
                <a href="{{ route('admin_author_show') }}" class="ab-btn ab-btn--ghost">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
