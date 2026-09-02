@extends('admin.layout.app')
@include('admin.cms_pages._assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')
@section('heading', 'View Page')

@section('main_content')
<div class="ab-page ab-page--cms">
    <div class="ab-wrap">
        <header class="ab-header">
            <div class="ab-identity">
                <span class="ab-logo ab-logo--lg">
                    <span>{{ strtoupper(substr($page->title ?: 'P', 0, 1)) }}</span>
                </span>
                <div>
                    <p class="ab-header__eyebrow">Page overview</p>
                    <h1 class="ab-header__title">{{ $page->title }}</h1>
                    <p class="ab-header__sub">/{{ $page->slug }} · {{ $page->templateLabel() }}</p>
                    <div class="ab-pills">
                        <span class="ab-pill {{ $page->isPublished() ? 'ab-pill--ok' : '' }}">{{ $page->statusLabel() }}</span>
                        <span class="ab-pill">{{ $page->sections->count() }} {{ \Illuminate\Support\Str::plural('block', $page->sections->count()) }}</span>
                    </div>
                </div>
            </div>
            <div class="ab-header__actions">
                @if($page->isPublished())
                    <a href="{{ $page->publicUrl() }}" class="ab-btn ab-btn--ghost" target="_blank" rel="noopener">Open live</a>
                @endif
                <a href="{{ route('admin_cms_pages_edit', $page->id) }}" class="ab-btn ab-btn--primary">Edit</a>
                <form action="{{ route('admin_cms_pages_toggle', $page->id) }}" method="POST">
                    @csrf
                    <button class="ab-btn ab-btn--ghost" type="submit">{{ $page->isPublished() ? 'Unpublish' : 'Publish' }}</button>
                </form>
                <a href="{{ route('admin_cms_pages_index') }}" class="ab-btn ab-btn--ghost">All pages</a>
            </div>
        </header>

        @unless($page->isPublished())
            <div class="cms-note">
                This page is a draft. Visitors cannot see it until you publish it.
            </div>
        @endunless

        <dl class="ab-dl">
            <div>
                <dt>Page title</dt>
                <dd>{{ $page->title }}</dd>
            </div>
            <div>
                <dt>Page URL</dt>
                <dd>/{{ $page->slug }}</dd>
            </div>
            <div>
                <dt>Layout</dt>
                <dd>{{ $page->templateLabel() }}</dd>
            </div>
            <div>
                <dt>Visibility</dt>
                <dd>{{ $page->statusLabel() }}</dd>
            </div>
            <div>
                <dt>Search title</dt>
                <dd>{{ $page->seoTitle() }}</dd>
            </div>
            <div>
                <dt>Last saved</dt>
                <dd>{{ $page->updated_at?->format('M j, Y H:i') ?? '—' }}</dd>
            </div>
        </dl>

        @if($page->meta_description)
            <section class="ab-section">
                <div class="ab-section__head">
                    <h2>Search description</h2>
                </div>
                <div class="ab-section__body">
                    <p class="mb-0">{{ $page->meta_description }}</p>
                </div>
            </section>
        @endif

        <section class="ab-section">
            <div class="ab-section__head">
                <h2>Content blocks</h2>
                <p>These blocks appear on the live page from top to bottom.</p>
            </div>
            <div class="ab-section__body">
                @forelse($page->sections as $section)
                    @php
                        $label = \App\Support\CmsSectionRegistry::label($section->section_type);
                        $data = $section->section_data ?? [];
                        $preview = $data['headline'] ?? $data['heading'] ?? $data['question'] ?? $data['title'] ?? $data['term'] ?? null;
                    @endphp
                    <div class="cms-view-block">
                        <span class="cms-view-block__index">{{ $loop->iteration }}</span>
                        <div>
                            <strong>{{ $label }}</strong>
                            @if($preview)
                                <p>{{ \Illuminate\Support\Str::limit(strip_tags((string) $preview), 90) }}</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-muted mb-0">This page has no content blocks yet. Open Edit to add some.</p>
                @endforelse
            </div>
        </section>

        <div class="ab-save">
            <a href="{{ route('admin_cms_pages_edit', $page->id) }}" class="ab-btn ab-btn--primary">Edit this page</a>
            <form action="{{ route('admin_cms_pages_destroy', $page->id) }}" method="POST" data-ab-delete data-ab-name="{{ $page->title }}" data-ab-warn="This cannot be undone. The live URL will stop working." data-ab-confirm="Delete page">
                @csrf
                @method('DELETE')
                <button class="ab-btn ab-btn--danger" type="submit">Delete page</button>
            </form>
        </div>
    </div>
</div>
@endsection
