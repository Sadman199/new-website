@extends('admin.layout.app')
@include('admin.forex_bonuses._assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')
@section('heading', 'Forex Bonuses')

@php
    $hasFilters = $filters['q'] !== ''
        || $filters['promo_type'] !== ''
        || $filters['status'] !== ''
        || $filters['broker_id'] !== ''
        || $filters['featured'] !== ''
        || ($filters['sort'] !== '' && $filters['sort'] !== 'newest');
@endphp

@section('main_content')
<div class="ab-page ab-page--bonus">
    <div class="ab-wrap">
        <header class="ab-header">
            <div>
                <p class="ab-header__eyebrow">Marketing</p>
                <h1 class="ab-header__title">Forex Bonuses</h1>
                <p class="ab-header__sub">Manage deposit bonuses, contests, cashback, and crypto promos shown on the site.</p>
            </div>
            <div class="ab-header__actions">
                <a href="{{ route('admin_forex_bonus_create') }}" class="ab-btn ab-btn--primary">
                    <i class="fas fa-plus" aria-hidden="true"></i>
                    Add Bonus
                </a>
            </div>
        </header>

        <div class="ab-kpis">
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-gift" aria-hidden="true"></i></span>
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
                <span class="ab-kpi__icon"><i class="fas fa-bolt" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Ongoing</p>
                    <p class="ab-kpi__value">{{ number_format($stats['ongoing']) }}</p>
                </div>
            </div>
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-clock" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Expired</p>
                    <p class="ab-kpi__value">{{ number_format($stats['expired']) }}</p>
                </div>
            </div>
        </div>

        <div class="ab-panel">
            <form method="GET" action="{{ route('admin_forex_bonus_show') }}" class="ab-filters ab-filters--bonus">
                <div class="ab-field">
                    <label for="bonus-q">Search</label>
                    <input id="bonus-q" class="ab-input" type="search" name="q" value="{{ $filters['q'] }}" placeholder="Title or URL">
                </div>
                <div class="ab-field">
                    <label for="bonus-type">Type</label>
                    <select id="bonus-type" class="ab-select" name="promo_type">
                        <option value="">All types</option>
                        @foreach($promoTypes as $value => $label)
                            <option value="{{ $value }}" @selected($filters['promo_type'] === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ab-field">
                    <label for="bonus-status">Status</label>
                    <select id="bonus-status" class="ab-select" name="status">
                        <option value="">All</option>
                        @foreach($statuses as $value => $label)
                            <option value="{{ $value }}" @selected($filters['status'] === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ab-field">
                    <label for="bonus-broker">Broker</label>
                    <select id="bonus-broker" class="ab-select" name="broker_id">
                        <option value="">All brokers</option>
                        @foreach($brokers as $broker)
                            <option value="{{ $broker->id }}" @selected((string) $filters['broker_id'] === (string) $broker->id)>{{ $broker->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ab-field">
                    <label for="bonus-featured">Featured</label>
                    <select id="bonus-featured" class="ab-select" name="featured">
                        <option value="">All</option>
                        <option value="1" @selected($filters['featured'] === '1')>Featured only</option>
                        <option value="0" @selected($filters['featured'] === '0')>Not featured</option>
                    </select>
                </div>
                <div class="ab-field">
                    <label for="bonus-sort">Sort</label>
                    <select id="bonus-sort" class="ab-select" name="sort">
                        <option value="newest" @selected($filters['sort'] === 'newest')>Newest</option>
                        <option value="updated" @selected($filters['sort'] === 'updated')>Recently updated</option>
                        <option value="title" @selected($filters['sort'] === 'title')>Title A–Z</option>
                        <option value="expiry" @selected($filters['sort'] === 'expiry')>Expiry date</option>
                    </select>
                </div>
                <div class="ab-header__actions">
                    <button type="submit" class="ab-btn ab-btn--primary">Filter</button>
                    @if($hasFilters)
                        <a href="{{ route('admin_forex_bonus_show') }}" class="ab-btn ab-btn--ghost">Reset</a>
                    @endif
                </div>
            </form>

            @if($bonuses->isEmpty())
                <div class="ab-empty">
                    <h3>{{ $hasFilters ? 'No bonuses match these filters' : 'No bonuses yet' }}</h3>
                    <p>{{ $hasFilters ? 'Try a different search, or reset the filters.' : 'Add the first bonus to show it on the promotions pages.' }}</p>
                    @if($hasFilters)
                        <a href="{{ route('admin_forex_bonus_show') }}" class="ab-btn ab-btn--ghost">Reset filters</a>
                    @else
                        <a href="{{ route('admin_forex_bonus_create') }}" class="ab-btn ab-btn--primary">Add Bonus</a>
                    @endif
                </div>
            @else
                <div class="ab-table-wrap">
                    <table class="ab-table">
                        <thead>
                            <tr>
                                <th>Bonus</th>
                                <th>Broker</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Dates</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bonuses as $row)
                                @php $thumb = $row->imageUrl(); @endphp
                                <tr>
                                    <td>
                                        <div class="ab-broker">
                                            <span class="ab-logo">
                                                @if($thumb)
                                                    <img src="{{ $thumb }}" alt="">
                                                @else
                                                    <span>{{ strtoupper(substr($row->title ?: 'B', 0, 1)) }}</span>
                                                @endif
                                            </span>
                                            <div>
                                                <p class="ab-broker__name">{{ $row->title }}</p>
                                                <p class="ab-broker__meta">{{ $row->headlineOffer() }}</p>
                                                <div class="ab-pills">
                                                    @if($row->is_featured)
                                                        <span class="ab-pill ab-pill--warn">Featured</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $row->broker?->name ?? '—' }}</td>
                                    <td>{{ $row->promoTypeShort() }}</td>
                                    <td>
                                        <span class="ab-pill {{ $row->promotion_status === 'expired' ? 'ab-pill--danger' : ($row->promotion_status === 'limited-time' ? 'ab-pill--warn' : 'ab-pill--ok') }}">
                                            {{ $row->promotionStatusLabel() }}
                                        </span>
                                    </td>
                                    <td>
                                        <div>{{ $row->publish_date?->format('M j, Y') }}</div>
                                        <div class="ab-broker__meta">{{ $row->expiryLabel() ?? 'No expiry' }}</div>
                                    </td>
                                    <td>
                                        <div class="ab-actions">
                                            <a class="ab-btn ab-btn--ghost ab-btn--sm" href="{{ route('admin_forex_bonus_view', $row->id) }}">View</a>
                                            <a class="ab-btn ab-btn--ghost ab-btn--sm" href="{{ route('admin_forex_bonus_edit', $row->id) }}">Edit</a>
                                            @if($row->detailUrl())
                                                <a class="ab-btn ab-btn--ghost ab-btn--sm" href="{{ $row->detailUrl() }}" target="_blank" rel="noopener">Open live</a>
                                            @endif
                                            <form action="{{ route('admin_forex_bonus_delete', $row->id) }}" method="POST" data-ab-delete data-ab-name="{{ $row->title }}" data-ab-warn="This cannot be undone." data-ab-confirm="Delete bonus">
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
                @if($bonuses->hasPages())
                    <div class="ab-pager">{{ $bonuses->links() }}</div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
