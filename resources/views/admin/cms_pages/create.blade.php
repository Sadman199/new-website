@extends('admin.layout.app')
@include('admin.cms_pages._assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')
@section('heading', 'Create Page')

@section('main_content')
<div class="ab-page ab-page--cms">
    <div class="ab-wrap">
        <header class="ab-header">
            <div>
                <p class="ab-header__eyebrow">CMS Pages</p>
                <h1 class="ab-header__title">Create Page</h1>
                <p class="ab-header__sub">Give the page a title and URL, add content blocks, then save. Keep it as a draft until you are ready to publish.</p>
            </div>
            <div class="ab-header__actions">
                <a href="{{ route('admin_cms_pages_index') }}" class="ab-btn ab-btn--ghost">All pages</a>
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

        <form action="{{ route('admin_cms_pages_store') }}" method="POST" id="cms-page-form" class="cms-page-form">
            @csrf
            @include('admin.cms_pages._form')
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script src="{{ asset('js/cms-page-builder.js') }}?v=6"></script>
@endpush
