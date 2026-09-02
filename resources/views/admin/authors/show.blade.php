@extends('admin.layout.app')
@include('admin.partials._ab_assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')
@section('heading', 'Authors')

@php
    $hasFilters = $filters['q'] !== ''
        || $filters['role'] !== ''
        || ($filters['sort'] !== '' && $filters['sort'] !== 'name');
@endphp

@section('main_content')
<div class="ab-page ab-page--hub">
    <div class="ab-wrap">
        <header class="ab-header">
            <div>
                <p class="ab-header__eyebrow">Content</p>
                <h1 class="ab-header__title">Authors</h1>
                <p class="ab-header__sub">People who can be credited as writers, editors, or fact-checkers on published content.</p>
            </div>
            <div class="ab-header__actions">
                <a href="{{ route('admin_author_create') }}" class="ab-btn ab-btn--primary">
                    <i class="fas fa-plus" aria-hidden="true"></i>
                    Add Author
                </a>
            </div>
        </header>

        <div class="ab-kpis">
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-user-edit" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Total</p>
                    <p class="ab-kpi__value">{{ number_format($stats['total']) }}</p>
                </div>
            </div>
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-pen" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Written</p>
                    <p class="ab-kpi__value">{{ number_format($stats['writers']) }}</p>
                </div>
            </div>
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-check" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Edited</p>
                    <p class="ab-kpi__value">{{ number_format($stats['editors']) }}</p>
                </div>
            </div>
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-search" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Fact-Checked</p>
                    <p class="ab-kpi__value">{{ number_format($stats['fact']) }}</p>
                </div>
            </div>
        </div>

        <div class="ab-panel">
            <form method="GET" action="{{ route('admin_author_show') }}" class="ab-filters ab-filters--hub">
                <div class="ab-field">
                    <label for="author-q">Search</label>
                    <input id="author-q" class="ab-input" type="search" name="q" value="{{ $filters['q'] }}" placeholder="Name or email">
                </div>
                <div class="ab-field">
                    <label for="author-role">Role</label>
                    <select id="author-role" class="ab-select" name="role">
                        <option value="">All roles</option>
                        <option value="write" @selected($filters['role'] === 'write')>Written</option>
                        <option value="edit" @selected($filters['role'] === 'edit')>Edited</option>
                        <option value="fact" @selected($filters['role'] === 'fact')>Fact-Checked</option>
                    </select>
                </div>
                <div class="ab-field">
                    <label for="author-sort">Sort</label>
                    <select id="author-sort" class="ab-select" name="sort">
                        <option value="name" @selected($filters['sort'] === 'name')>Name A–Z</option>
                        <option value="newest" @selected($filters['sort'] === 'newest')>Newest</option>
                        <option value="written" @selected($filters['sort'] === 'written')>Most written</option>
                    </select>
                </div>
                <div class="ab-header__actions">
                    <button type="submit" class="ab-btn ab-btn--primary">Filter</button>
                    @if($hasFilters)
                        <a href="{{ route('admin_author_show') }}" class="ab-btn ab-btn--ghost">Reset</a>
                    @endif
                </div>
            </form>

            @if($authors->isEmpty())
                <div class="ab-empty">
                    <h3>{{ $hasFilters ? 'No authors match these filters' : 'No authors yet' }}</h3>
                    <p>{{ $hasFilters ? 'Try a different search, or reset the filters.' : 'Add an author so they can be credited on blog posts.' }}</p>
                    @if($hasFilters)
                        <a href="{{ route('admin_author_show') }}" class="ab-btn ab-btn--ghost">Reset filters</a>
                    @else
                        <a href="{{ route('admin_author_create') }}" class="ab-btn ab-btn--primary">Add Author</a>
                    @endif
                </div>
            @else
                <div class="ab-table-wrap">
                    <table class="ab-table">
                        <thead>
                            <tr>
                                <th>Author</th>
                                <th>Email</th>
                                <th>Roles</th>
                                <th>Contributions</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($authors as $row)
                                <tr>
                                    <td>
                                        <div class="ab-broker">
                                            <span class="ab-logo">
                                                <img src="{{ $row->photoUrl() }}" alt="">
                                            </span>
                                            <div>
                                                <p class="ab-broker__name">{{ $row->name }}</p>
                                                @if($row->bio)
                                                    <p class="ab-broker__meta">{{ \Illuminate\Support\Str::limit($row->bio, 72) }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $row->email }}</td>
                                    <td>
                                        <div class="ab-pills">
                                            @if($row->can_write)<span class="ab-pill ab-pill--ok">Written</span>@endif
                                            @if($row->can_edit)<span class="ab-pill">Edited</span>@endif
                                            @if($row->can_fact_check)<span class="ab-pill ab-pill--warn">Fact-Checked</span>@endif
                                            @if(! $row->can_write && ! $row->can_edit && ! $row->can_fact_check)
                                                <span class="ab-broker__meta">No roles</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="ab-broker__meta">Written {{ $row->written_posts_count ?? 0 }}</div>
                                        <div class="ab-broker__meta">Edited {{ $row->edited_posts_count ?? 0 }}</div>
                                        <div class="ab-broker__meta">Fact-Checked {{ $row->fact_checked_posts_count ?? 0 }}</div>
                                    </td>
                                    <td>
                                        <div class="ab-actions">
                                            <a class="ab-btn ab-btn--ghost ab-btn--sm" href="{{ route('admin_author_view', $row->id) }}">View</a>
                                            <a class="ab-btn ab-btn--ghost ab-btn--sm" href="{{ route('admin_author_edit', $row->id) }}">Edit</a>
                                            <form action="{{ route('admin_author_delete', $row->id) }}" method="POST" data-ab-delete data-ab-name="{{ $row->name }}" data-ab-warn="Editorial credits on posts will be cleared." data-ab-confirm="Delete author">
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
                @if($authors->hasPages())
                    <div class="ab-pager">{{ $authors->links() }}</div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
