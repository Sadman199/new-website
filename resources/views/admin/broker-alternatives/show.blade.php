@extends('admin.layout.app')
@include('admin.partials._ab_assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')
@section('heading', 'Broker Alternatives')

@php
    $hasFilters = $filters['q'] !== '' || $filters['status'] !== '';
@endphp

@section('main_content')
<div class="ab-page ab-page--hub">
    <div class="ab-wrap">
        <header class="ab-header">
            <div>
                <p class="ab-header__eyebrow">Brokers</p>
                <h1 class="ab-header__title">Broker Alternatives</h1>
                <p class="ab-header__sub">Enable a public alternatives page for a catalog broker and optionally curate the recommended list.</p>
            </div>
            <div class="ab-header__actions">
                <a href="{{ route('admin_broker_alternatives_create') }}" class="ab-btn ab-btn--primary">
                    <i class="fas fa-plus" aria-hidden="true"></i>
                    Add page
                </a>
            </div>
        </header>

        <form method="get" class="ab-filters" action="{{ route('admin_broker_alternatives_show') }}">
            <div class="ab-field">
                <label for="alt-q">Search</label>
                <input id="alt-q" type="search" name="q" class="form-control" value="{{ $filters['q'] }}" placeholder="Broker name or slug">
            </div>
            <div class="ab-field">
                <label for="alt-status">Status</label>
                <select id="alt-status" name="status" class="ab-select">
                    <option value="">All</option>
                    <option value="published" @selected($filters['status'] === 'published')>Published</option>
                    <option value="draft" @selected($filters['status'] === 'draft')>Unpublished</option>
                </select>
            </div>
            <div class="ab-header__actions">
                <button type="submit" class="ab-btn ab-btn--primary">Filter</button>
                @if($hasFilters)
                    <a href="{{ route('admin_broker_alternatives_show') }}" class="ab-btn ab-btn--ghost">Reset</a>
                @endif
            </div>
        </form>

        <div class="ab-table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Broker</th>
                        <th>Status</th>
                        <th>Curated alternatives</th>
                        <th>Updated</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pages as $page)
                        <tr>
                            <td>
                                <strong>{{ $page->broker->name ?? '—' }}</strong>
                                @if($page->broker)
                                    <div class="text-muted small">{{ $page->broker->slug }}</div>
                                @endif
                            </td>
                            <td>{{ $page->is_published ? 'Published' : 'Unpublished' }}</td>
                            <td>{{ $page->items->count() }}</td>
                            <td>{{ optional($page->updated_at)->format('M j, Y') }}</td>
                            <td class="text-right">
                                @if($page->broker && $page->is_published)
                                    <a href="{{ route('broker.alternatives.show', ['slug' => $page->broker->slug]) }}" target="_blank" rel="noopener">View</a>
                                @endif
                                <a href="{{ route('admin_broker_alternatives_edit', $page->id) }}">Edit</a>
                                <form action="{{ route('admin_broker_alternatives_toggle', $page->id) }}" method="post" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-link p-0">{{ $page->is_published ? 'Unpublish' : 'Publish' }}</button>
                                </form>
                                <form action="{{ route('admin_broker_alternatives_delete', $page->id) }}" method="post" class="d-inline" onsubmit="return confirm('Delete this alternatives page?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link text-danger p-0">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No alternatives pages yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $pages->links() }}
    </div>
</div>
@endsection
