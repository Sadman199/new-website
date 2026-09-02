@extends('admin.layout.app')
@include('admin.posts._assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')

@section('heading', 'Edit Blog')

@section('main_content')
<div class="ab-page ab-page--blog">
    <div class="ab-wrap">
        @include('admin.posts._nav', ['active' => 'blogs'])
        <header class="ab-header">
            <div>
                <p class="ab-header__eyebrow">Edit Blog</p>
                <h1 class="ab-header__title">{{ $post->post_title }}</h1>
                <p class="ab-header__sub">{{ $post->slug }} · {{ $post->statusLabel() }}</p>
            </div>
            <div class="ab-header__actions">
                <a href="{{ route('admin_post_view', $post->id) }}" class="ab-btn ab-btn--ghost">Preview</a>
                @if($post->publicUrl() && $post->isPubliclyVisible())
                    <a href="{{ $post->publicUrl() }}" class="ab-btn ab-btn--ghost" target="_blank" rel="noopener">View on site</a>
                @endif
                <a href="{{ route('admin_post_show') }}" class="ab-btn ab-btn--ghost">All blogs</a>
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

        <form class="ab-blog-form" action="{{ route('admin_post_update', $post->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.posts._form', ['post' => $post, 'formOptions' => $formOptions])
        </form>
    </div>
</div>
@endsection
