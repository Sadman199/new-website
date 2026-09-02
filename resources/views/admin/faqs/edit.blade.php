@extends('admin.layout.app')
@include('admin.partials._ab_assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')
@section('heading', 'Edit FAQ')

@section('main_content')
<div class="ab-page ab-page--hub">
    <div class="ab-wrap">
        <header class="ab-header">
            <div>
                <p class="ab-header__eyebrow">Edit FAQ</p>
                <h1 class="ab-header__title">{{ $faq_data->faq_title }}</h1>
                <p class="ab-header__sub">{{ $faq_data->broker?->name ?? 'No broker' }}</p>
            </div>
            <div class="ab-header__actions">
                <a href="{{ route('admin_faq_view', $faq_data->id) }}" class="ab-btn ab-btn--ghost">View</a>
                <a href="{{ route('admin_faq_show') }}" class="ab-btn ab-btn--ghost">All FAQs</a>
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

        <form action="{{ route('admin_faq_update', $faq_data->id) }}" method="POST" novalidate>
            @csrf
            @include('admin.faqs._form')
            <div class="ab-save">
                <button type="submit" class="ab-btn ab-btn--primary">
                    <i class="fas fa-save" aria-hidden="true"></i>
                    Save changes
                </button>
                <a href="{{ route('admin_faq_show') }}" class="ab-btn ab-btn--ghost">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
