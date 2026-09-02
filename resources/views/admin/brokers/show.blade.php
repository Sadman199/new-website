@extends('admin.layout.app')
@include('admin.brokers._assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')

@section('heading', 'Brokers')

@section('main_content')
<div class="ab-page">
    <div class="ab-wrap">
        <header class="ab-header">
            <div>
                <p class="ab-header__eyebrow">Directory</p>
                <h1 class="ab-header__title">Brokers</h1>
                <p class="ab-header__sub">Search, filter, and keep every listing current from one place.</p>
            </div>
            <div class="ab-header__actions">
                <a href="{{ route('admin_broker_scam') }}" class="ab-btn ab-btn--ghost">
                    <i class="fas fa-exclamation-triangle" aria-hidden="true"></i>
                    Scam list
                </a>
                <a href="{{ route('admin_broker_create') }}" class="ab-btn ab-btn--primary">
                    <i class="fas fa-plus" aria-hidden="true"></i>
                    Add broker
                </a>
            </div>
        </header>

        <div class="ab-kpis">
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-briefcase" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Total</p>
                    <p class="ab-kpi__value">{{ number_format($stats['total']) }}</p>
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
                <span class="ab-kpi__icon"><i class="fas fa-flag" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Scam flagged</p>
                    <p class="ab-kpi__value">{{ number_format($stats['scam']) }}</p>
                </div>
            </div>
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-image" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Missing logo</p>
                    <p class="ab-kpi__value">{{ number_format($stats['missing_logo']) }}</p>
                </div>
            </div>
        </div>

        <div class="ab-panel">
            <form method="GET" action="{{ route('admin_broker_show') }}" class="ab-filters">
                <div class="ab-field">
                    <label for="ab-q">Search</label>
                    <input id="ab-q" class="ab-input" type="search" name="q" value="{{ $filters['q'] }}" placeholder="Name, slug, country…">
                </div>
                <div class="ab-field">
                    <label for="ab-status">Status</label>
                    <select id="ab-status" class="ab-select" name="status">
                        <option value="">All brokers</option>
                        <option value="live" @selected($filters['status'] === 'live')>Live</option>
                        <option value="featured" @selected($filters['status'] === 'featured')>Featured</option>
                        <option value="scam" @selected($filters['status'] === 'scam')>Scam flagged</option>
                        <option value="no-logo" @selected($filters['status'] === 'no-logo')>Missing logo</option>
                    </select>
                </div>
                <div class="ab-field">
                    <label for="ab-sort">Sort</label>
                    <select id="ab-sort" class="ab-select" name="sort">
                        <option value="newest" @selected($filters['sort'] === 'newest')>Newest</option>
                        <option value="name" @selected($filters['sort'] === 'name')>Name</option>
                        <option value="rating" @selected($filters['sort'] === 'rating')>Rating</option>
                        <option value="trust" @selected($filters['sort'] === 'trust')>Trust score</option>
                    </select>
                </div>
                <div class="ab-header__actions">
                    <button type="submit" class="ab-btn ab-btn--primary">Filter</button>
                    @if($filters['q'] !== '' || $filters['status'] !== '' || $filters['sort'] !== 'newest')
                        <a href="{{ route('admin_broker_show') }}" class="ab-btn ab-btn--ghost">Reset</a>
                    @endif
                </div>
            </form>

            @if($brokers->isEmpty())
                <div class="ab-empty">
                    <h3>No brokers match these filters</h3>
                    <p>Try a different search, or add the first listing to the directory.</p>
                    <a href="{{ route('admin_broker_create') }}" class="ab-btn ab-btn--primary">Add broker</a>
                </div>
            @else
                <div class="ab-table-wrap">
                    <table class="ab-table">
                        <thead>
                            <tr>
                                <th>Broker</th>
                                <th>HQ</th>
                                <th>Rating</th>
                                <th>Trust</th>
                                <th>Min. deposit</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($brokers as $broker)
                                @php $logo = $broker->mediaUrl(); @endphp
                                <tr>
                                    <td>
                                        <div class="ab-broker">
                                            <span class="ab-logo">
                                                @if($logo)
                                                    <img src="{{ $logo }}" alt="">
                                                @else
                                                    <span>{{ strtoupper(substr($broker->name, 0, 1)) }}</span>
                                                @endif
                                            </span>
                                            <div>
                                                <p class="ab-broker__name">{{ $broker->name }}</p>
                                                <p class="ab-broker__meta">{{ $broker->slug }} · {{ $broker->account_options_count }} accounts</p>
                                                <div class="ab-pills">
                                                    @if($broker->featured_broker)<span class="ab-pill ab-pill--warn">Featured</span>@endif
                                                    @if($broker->is_scam)<span class="ab-pill ab-pill--danger">Scam</span>@endif
                                                    @if($broker->top_broker)<span class="ab-pill">Top #{{ $broker->top_broker }}</span>@endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $broker->country ?: '—' }}</td>
                                    <td>{{ $broker->rating ? number_format((float) $broker->rating, 1).'/5' : '—' }}</td>
                                    <td>{{ $broker->trust_score ? $broker->trust_score.'/99' : '—' }}</td>
                                    <td>{{ $broker->minimum_deposit !== null ? '$'.number_format((float) $broker->minimum_deposit, 0) : '—' }}</td>
                                    <td>
                                        <div class="ab-actions">
                                            <a class="ab-btn ab-btn--ghost ab-btn--sm" href="{{ route('admin_broker_view', $broker->id) }}">View</a>
                                            <a class="ab-btn ab-btn--ghost ab-btn--sm" href="{{ route('admin_broker_edit', $broker->id) }}">Edit</a>
                                            @if($broker->slug)
                                                <a class="ab-btn ab-btn--ghost ab-btn--sm" href="{{ route('broker_detail', $broker->slug) }}" target="_blank" rel="noopener">Site</a>
                                            @endif
                                            <form action="{{ route('admin_broker_delete', $broker->id) }}" method="POST" data-ab-delete data-ab-name="{{ $broker->name }}" data-ab-warn="This cannot be undone. Related account options will also be removed." data-ab-confirm="Delete broker">
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
                    <span class="ab-broker__meta">Showing {{ $brokers->firstItem() }}–{{ $brokers->lastItem() }} of {{ $brokers->total() }}</span>
                    {{ $brokers->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
