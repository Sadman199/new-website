@extends('admin.layout.app')
@include('admin.partials._ab_assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')
@section('heading', 'View FAQ')

@section('main_content')
<div class="ab-page ab-page--hub">
    <div class="ab-wrap">
        <header class="ab-header">
            <div>
                <p class="ab-header__eyebrow">FAQ overview</p>
                <h1 class="ab-header__title">{{ $faq->faq_title }}</h1>
                <p class="ab-header__sub">{{ $faq->broker?->name ?? 'No broker' }} · {{ $faq->rLanguage->name ?? '—' }}</p>
            </div>
            <div class="ab-header__actions">
                <a href="{{ route('admin_faq_edit', $faq->id) }}" class="ab-btn ab-btn--primary">Edit</a>
                <a href="{{ route('admin_faq_show') }}" class="ab-btn ab-btn--ghost">All FAQs</a>
            </div>
        </header>

        <dl class="ab-dl">
            <div>
                <dt>Broker</dt>
                <dd>{{ $faq->broker?->name ?? '—' }}</dd>
            </div>
            <div>
                <dt>Language</dt>
                <dd>{{ $faq->rLanguage->name ?? '—' }}</dd>
            </div>
            <div>
                <dt>Last saved</dt>
                <dd>{{ $faq->updated_at?->format('M j, Y H:i') ?? '—' }}</dd>
            </div>
        </dl>

        <section class="ab-section">
            <div class="ab-section__head"><h2>Answer</h2></div>
            <div class="ab-section__body rich-text">{!! $faq->faq_detail !!}</div>
        </section>

        <div class="ab-save">
            <a href="{{ route('admin_faq_edit', $faq->id) }}" class="ab-btn ab-btn--primary">Edit this FAQ</a>
            <form action="{{ route('admin_faq_delete', $faq->id) }}" method="POST" data-ab-delete data-ab-name="{{ $faq->faq_title }}" data-ab-warn="This cannot be undone." data-ab-confirm="Delete FAQ">
                @csrf
                @method('DELETE')
                <button class="ab-btn ab-btn--danger" type="submit">Delete FAQ</button>
            </form>
        </div>
    </div>
</div>
@endsection
