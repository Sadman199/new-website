@extends('admin.layout.app')

@section('heading', 'Edit: ' . $page->title)

@section('button')
    <a href="{{ route('admin_cms_pages_index') }}" class="btn btn-light">
        <i class="fas fa-arrow-left"></i> All pages
    </a>
@endsection

@section('main_content')
<div class="section-body">
    <form action="{{ route('admin_cms_pages_update', $page->id) }}" method="POST" id="cms-page-form">
        @csrf
        @method('PUT')
        @include('admin.cms_pages._form')
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script src="{{ asset('js/cms-page-builder.js') }}?v=3"></script>
@endpush

@push('styles')
<link rel="stylesheet" href="{{ asset('css/cms-page-builder.css') }}?v=3">
@endpush
