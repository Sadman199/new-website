@extends('admin.layout.app')
@include('admin.cms_pages._assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')
@section('heading', 'CMS Pages')

@php
    $hasFilters = $filters['q'] !== ''
        || $filters['status'] !== ''
        || $filters['template'] !== ''
        || ($filters['sort'] !== '' && $filters['sort'] !== 'updated');
@endphp

@section('main_content')
<div class="ab-page ab-page--cms">
    <div class="ab-wrap">
        <header class="ab-header">
            <div>
                <p class="ab-header__eyebrow">Site content</p>
                <h1 class="ab-header__title">CMS Pages</h1>
                <p class="ab-header__sub">Create and manage standalone pages such as About, Careers, or Glossary. Visitors see published pages; drafts stay private.</p>
            </div>
            <div class="ab-header__actions">
                <a href="{{ route('admin_cms_pages_create') }}" class="ab-btn ab-btn--primary">
                    <i class="fas fa-plus" aria-hidden="true"></i>
                    Create Page
                </a>
            </div>
        </header>

        <div class="ab-kpis">
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-layer-group" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Total pages</p>
                    <p class="ab-kpi__value">{{ number_format($stats['total']) }}</p>
                </div>
            </div>
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-check-circle" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Published</p>
                    <p class="ab-kpi__value">{{ number_format($stats['published']) }}</p>
                </div>
            </div>
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-pencil-alt" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Drafts</p>
                    <p class="ab-kpi__value">{{ number_format($stats['draft']) }}</p>
                </div>
            </div>
        </div>

        <div class="ab-panel">
            <form method="GET" action="{{ route('admin_cms_pages_index') }}" class="ab-filters ab-filters--cms">
                <div class="ab-field">
                    <label for="cms-q">Search</label>
                    <input id="cms-q" class="ab-input" type="search" name="q" value="{{ $filters['q'] }}" placeholder="Title or URL">
                </div>
                <div class="ab-field">
                    <label for="cms-status">Status</label>
                    <select id="cms-status" class="ab-select" name="status">
                        <option value="">All</option>
                        <option value="published" @selected($filters['status'] === 'published')>Published</option>
                        <option value="draft" @selected($filters['status'] === 'draft')>Draft</option>
                    </select>
                </div>
                <div class="ab-field">
                    <label for="cms-template">Layout</label>
                    <select id="cms-template" class="ab-select" name="template">
                        <option value="">All layouts</option>
                        @foreach($templates as $value => $label)
                            <option value="{{ $value }}" @selected($filters['template'] === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ab-field">
                    <label for="cms-sort">Sort</label>
                    <select id="cms-sort" class="ab-select" name="sort">
                        <option value="updated" @selected($filters['sort'] === 'updated')>Recently updated</option>
                        <option value="newest" @selected($filters['sort'] === 'newest')>Newest</option>
                        <option value="title" @selected($filters['sort'] === 'title')>Title A–Z</option>
                        <option value="sections" @selected($filters['sort'] === 'sections')>Most sections</option>
                    </select>
                </div>
                <div class="ab-header__actions">
                    <button type="submit" class="ab-btn ab-btn--primary">Filter</button>
                    @if($hasFilters)
                        <a href="{{ route('admin_cms_pages_index') }}" class="ab-btn ab-btn--ghost">Reset</a>
                    @endif
                </div>
            </form>

            @if($pages->isEmpty())
                <div class="ab-empty">
                    <h3>{{ $hasFilters ? 'No pages match these filters' : 'No pages yet' }}</h3>
                    <p>{{ $hasFilters ? 'Try a different search, or reset the filters.' : 'Create a page, add content blocks, then publish it when it is ready.' }}</p>
                    @if($hasFilters)
                        <a href="{{ route('admin_cms_pages_index') }}" class="ab-btn ab-btn--ghost">Reset filters</a>
                    @else
                        <a href="{{ route('admin_cms_pages_create') }}" class="ab-btn ab-btn--primary">Create Page</a>
                    @endif
                </div>
            @else
                <div class="ab-table-wrap">
                    <table class="ab-table">
                        <thead>
                            <tr>
                                <th>Page</th>
                                <th>Layout</th>
                                <th>Sections</th>
                                <th>Status</th>
                                <th>Updated</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pages as $row)
                                <tr>
                                    <td>
                                        <p class="ab-broker__name">{{ $row->title }}</p>
                                        <p class="ab-broker__meta">/{{ $row->slug }}</p>
                                    </td>
                                    <td>{{ $row->templateLabel() }}</td>
                                    <td>{{ number_format($row->sections_count) }}</td>
                                    <td>
                                        <span class="ab-pill {{ $row->isPublished() ? 'ab-pill--ok' : '' }}">{{ $row->statusLabel() }}</span>
                                    </td>
                                    <td>{{ $row->updated_at?->format('M j, Y') }}</td>
                                    <td>
                                        <div class="ab-actions">
                                            <a class="ab-btn ab-btn--ghost ab-btn--sm" href="{{ route('admin_cms_pages_view', $row->id) }}">View</a>
                                            <a class="ab-btn ab-btn--ghost ab-btn--sm" href="{{ route('admin_cms_pages_edit', $row->id) }}">Edit</a>
                                            @if($row->isPublished())
                                                <a class="ab-btn ab-btn--ghost ab-btn--sm" href="{{ $row->publicUrl() }}" target="_blank" rel="noopener">Open live</a>
                                            @endif
                                            <form action="{{ route('admin_cms_pages_toggle', $row->id) }}" method="POST">
                                                @csrf
                                                <button class="ab-btn ab-btn--ghost ab-btn--sm" type="submit">
                                                    {{ $row->isPublished() ? 'Unpublish' : 'Publish' }}
                                                </button>
                                            </form>
                                            <form action="{{ route('admin_cms_pages_destroy', $row->id) }}" method="POST" data-ab-delete data-ab-name="{{ $row->title }}" data-ab-warn="This cannot be undone. The live URL will stop working." data-ab-confirm="Delete page">
                                                @csrf
                                                @method('DELETE')
                                                <button class="ab-btn ab-btn--ghost ab-btn--sm" type="submit">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($pages->hasPages())
                    <div class="ab-pager">{{ $pages->links() }}</div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
