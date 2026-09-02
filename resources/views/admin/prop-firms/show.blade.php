@extends('admin.layout.app')
@include('admin.brokers._assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')

@section('heading', 'All Prop Firms')

@section('main_content')
<div class="ab-page">
    <div class="ab-wrap">
        <header class="ab-header">
            <div>
                <p class="ab-header__eyebrow">Prop firms</p>
                <h1 class="ab-header__title">All firms</h1>
                <p class="ab-header__sub">Search, filter, and keep every listing current from one place.</p>
            </div>
            <div class="ab-header__actions">
                <a href="{{ route('admin_prop_firms_dashboard') }}" class="ab-btn ab-btn--ghost">Dashboard</a>
                <a href="{{ route('admin_prop_firms_create') }}" class="ab-btn ab-btn--primary">
                    <i class="fas fa-plus" aria-hidden="true"></i>
                    Add prop firm
                </a>
            </div>
        </header>

        @include('admin.prop-firms._nav', ['active' => 'firms'])

        <div class="ab-kpis">
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-building" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Total</p>
                    <p class="ab-kpi__value">{{ number_format($stats['total']) }}</p>
                </div>
            </div>
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-check" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Active</p>
                    <p class="ab-kpi__value">{{ number_format($stats['active']) }}</p>
                </div>
            </div>
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-star" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Featured</p>
                    <p class="ab-kpi__value">{{ number_format($stats['featured']) }}</p>
                </div>
            </div>
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-shield-alt" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Verified</p>
                    <p class="ab-kpi__value">{{ number_format($stats['verified']) }}</p>
                </div>
            </div>
        </div>

        <div class="ab-panel">
            <form method="GET" action="{{ route('admin_prop_firms_show') }}" class="ab-filters ab-filters--5">
                <div class="ab-field">
                    <label for="ab-q">Search</label>
                    <input id="ab-q" class="ab-input" type="search" name="q" value="{{ $filters['q'] }}" placeholder="Name, slug, HQ…">
                </div>
                <div class="ab-field">
                    <label for="ab-category">Category</label>
                    <select id="ab-category" class="ab-select" name="category_id">
                        <option value="">All categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected((string) $filters['category_id'] === (string) $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ab-field">
                    <label for="ab-status">Status</label>
                    <select id="ab-status" class="ab-select" name="status">
                        <option value="">All firms</option>
                        <option value="active" @selected($filters['status'] === 'active')>Active</option>
                        <option value="inactive" @selected($filters['status'] === 'inactive')>Inactive</option>
                        <option value="featured" @selected($filters['status'] === 'featured')>Featured</option>
                        <option value="verified" @selected($filters['status'] === 'verified')>Verified</option>
                    </select>
                </div>
                <div class="ab-field">
                    <label for="ab-sort">Sort</label>
                    <select id="ab-sort" class="ab-select" name="sort">
                        <option value="newest" @selected($filters['sort'] === 'newest')>Newest</option>
                        <option value="name" @selected($filters['sort'] === 'name')>Name</option>
                        <option value="trust" @selected($filters['sort'] === 'trust')>Trust score</option>
                        <option value="rating" @selected($filters['sort'] === 'rating')>Rating</option>
                    </select>
                </div>
                <div class="ab-header__actions">
                    <button type="submit" class="ab-btn ab-btn--primary">Filter</button>
                    @if($filters['q'] !== '' || $filters['category_id'] !== '' || $filters['status'] !== '' || $filters['sort'] !== 'newest')
                        <a href="{{ route('admin_prop_firms_show') }}" class="ab-btn ab-btn--ghost">Reset</a>
                    @endif
                </div>
            </form>

            @if($propFirms->isEmpty())
                <div class="ab-empty">
                    <h3>No prop firms match these filters</h3>
                    <p>Try a different search, or add the first listing to the directory.</p>
                    <a href="{{ route('admin_prop_firms_create') }}" class="ab-btn ab-btn--primary">Add prop firm</a>
                </div>
            @else
                <form method="POST" action="{{ route('admin_prop_firms_bulk') }}" id="ab-bulk-form">
                    @csrf
                    <div class="ab-bulk">
                        <label class="ab-broker__name" style="display:inline-flex;align-items:center;gap:.55rem;font-size:.875rem;">
                            <input type="checkbox" id="ab-check-all">
                            Select all on this page
                        </label>
                        <div class="ab-header__actions">
                            <select name="action" class="ab-select" style="min-width:10rem;">
                                <option value="">Bulk action…</option>
                                <option value="activate">Activate</option>
                                <option value="deactivate">Deactivate</option>
                                <option value="delete">Delete</option>
                            </select>
                            <button type="submit" class="ab-btn ab-btn--ghost" onclick="return confirm('Apply this bulk action to the selected firms?')">Apply</button>
                        </div>
                    </div>
                </form>
                <div class="ab-table-wrap">
                        <table class="ab-table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Firm</th>
                                    <th>Category</th>
                                    <th>Trust</th>
                                    <th>Rating</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($propFirms as $firm)
                                    @php $logo = $firm->mediaUrl(); @endphp
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="ab-row-check" form="ab-bulk-form" name="ids[]" value="{{ $firm->id }}">
                                        </td>
                                        <td>
                                            <div class="ab-broker">
                                                <span class="ab-logo">
                                                    @if($logo)
                                                        <img src="{{ $logo }}" alt="">
                                                    @else
                                                        <span>{{ strtoupper(substr($firm->name, 0, 1)) }}</span>
                                                    @endif
                                                </span>
                                                <div>
                                                    <p class="ab-broker__name">{{ $firm->name }}</p>
                                                    <p class="ab-broker__meta">{{ $firm->slug }} · {{ $firm->programs_count }} programs</p>
                                                    <div class="ab-pills">
                                                        @if($firm->is_active)<span class="ab-pill ab-pill--ok">Active</span>@else<span class="ab-pill">Inactive</span>@endif
                                                        @if($firm->is_featured)<span class="ab-pill ab-pill--warn">Featured</span>@endif
                                                        @if($firm->is_verified)<span class="ab-pill">Verified</span>@endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $firm->category?->name ?: '—' }}</td>
                                        <td>{{ $firm->trust_score !== null ? number_format((float) $firm->trust_score, 1) : '—' }}</td>
                                        <td>{{ $firm->overall_rating !== null ? number_format((float) $firm->overall_rating, 1) : '—' }}</td>
                                        <td>
                                            <div class="ab-actions">
                                                <a class="ab-btn ab-btn--ghost ab-btn--sm" href="{{ route('admin_prop_firms_edit', $firm->id) }}">Edit</a>
                                                @if($firm->slug)
                                                    <a class="ab-btn ab-btn--ghost ab-btn--sm" href="{{ route('prop_firms.show', $firm->slug) }}" target="_blank" rel="noopener">Site</a>
                                                @endif
                                                <form action="{{ route('admin_prop_firms_delete', $firm->id) }}" method="POST" data-ab-delete data-ab-name="{{ $firm->name }}" data-ab-warn="This cannot be undone. Related programs, FAQs, and reviews will also be removed." data-ab-confirm="Delete firm">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="ab-btn ab-btn--danger ab-btn--sm">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="ab-foot">
                        <span class="ab-broker__meta">Showing {{ $propFirms->firstItem() }}–{{ $propFirms->lastItem() }} of {{ $propFirms->total() }}</span>
                        {{ $propFirms->links('pagination::bootstrap-4') }}
                    </div>
            @endif
        </div>
    </div>
</div>
@endsection
