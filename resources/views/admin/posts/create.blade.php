@extends('admin.layout.app')
@include('admin.posts._assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')

@section('heading', 'Create Blog')

@section('main_content')
<div class="ab-page ab-page--blog">
    <div class="ab-wrap">
        @include('admin.posts._nav', ['active' => 'blogs'])
        <header class="ab-header">
            <div>
                <p class="ab-header__eyebrow">Blog</p>
                <h1 class="ab-header__title">Create Blog</h1>
                <p class="ab-header__sub">Write, classify, attach brokers, and publish without leaving this page.</p>
            </div>
            <div class="ab-header__actions">
                <a href="{{ route('admin_post_show') }}" class="ab-btn ab-btn--ghost">Back to list</a>
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

        <form class="ab-blog-form" action="{{ route('admin_post_store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.posts._form', ['post' => $post, 'formOptions' => $formOptions])
        </form>
    </div>
</div>
@endsection
