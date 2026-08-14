@extends('admin.layout.app')

@section('heading', 'CMS Pages')

@section('button')
    <a href="{{ route('admin_cms_pages_create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> New Page
    </a>
@endsection

@section('main_content')
@php
    $stats = $stats ?? ['total' => 0, 'published' => 0, 'draft' => 0];
@endphp
<div class="section-body">
    <div class="cms-index-stats">
        <div class="cms-index-stat">
            <div class="cms-index-stat__value">{{ $stats['total'] }}</div>
            <div class="cms-index-stat__label">Total pages</div>
        </div>
        <div class="cms-index-stat">
            <div class="cms-index-stat__value">{{ $stats['published'] }}</div>
            <div class="cms-index-stat__label">Published</div>
        </div>
        <div class="cms-index-stat">
            <div class="cms-index-stat__value">{{ $stats['draft'] }}</div>
            <div class="cms-index-stat__label">Drafts</div>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h4 class="mb-0">Dynamic pages</h4>
                <small class="text-muted">Build and manage unlimited site pages with the section builder.</small>
            </div>
            <form method="GET" class="form-inline">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm mr-2" placeholder="Search title or slug">
                <select name="status" class="form-control form-control-sm mr-2">
                    <option value="">All</option>
                    <option value="published" @selected(request('status') === 'published')>Published</option>
                    <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                </select>
                <button type="submit" class="btn btn-sm btn-primary">Filter</button>
            </form>
        </div>
        <div class="card-body p-0 cms-index-table">
            @if($pages->isEmpty())
                <div class="cms-index-empty">
                    <i class="fas fa-layer-group"></i>
                    <h5>No CMS pages yet</h5>
                    <p class="text-muted mb-3">Create your first page — For Businesses, Glossary, Careers, and more.</p>
                    <a href="{{ route('admin_cms_pages_create') }}" class="btn btn-primary">Create first page</a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Page</th>
                                <th>URL</th>
                                <th>Template</th>
                                <th>Sections</th>
                                <th>Status</th>
                                <th>Updated</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pages as $page)
                                <tr>
                                    <td><strong>{{ $page->title }}</strong></td>
                                    <td class="cms-index-slug"><code>/{{ $page->slug }}</code></td>
                                    <td><span class="badge badge-light">{{ ucfirst($page->template) }}</span></td>
                                    <td>{{ $page->sections_count }}</td>
                                    <td>
                                        @if($page->status === 'published')
                                            <span class="badge badge-success">Live</span>
                                        @else
                                            <span class="badge badge-secondary">Draft</span>
                                        @endif
                                    </td>
                                    <td>{{ $page->updated_at?->format('M j, Y') }}</td>
                                    <td class="text-right cms-index-actions text-nowrap">
                                        <a href="{{ route('admin_cms_pages_edit', $page->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($page->status === 'published')
                                            <a href="{{ url('/' . $page->slug) }}" class="btn btn-sm btn-info" target="_blank" rel="noopener" title="View live">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                        @endif
                                        <form action="{{ route('admin_cms_pages_toggle', $page->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-{{ $page->status === 'published' ? 'warning' : 'success' }}" title="{{ $page->status === 'published' ? 'Unpublish' : 'Publish' }}">
                                                <i class="fas fa-{{ $page->status === 'published' ? 'eye-slash' : 'eye' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin_cms_pages_destroy', $page->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete &quot;{{ $page->title }}&quot;? This cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        @if($pages->hasPages())
            <div class="card-footer">{{ $pages->links() }}</div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/cms-page-builder.css') }}?v=3">
@endpush
