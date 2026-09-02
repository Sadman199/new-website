@extends('admin.layout.app')
@include('admin.partials._ab_assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')
@section('heading', 'Users')

@php
    $hasFilters = ($search ?? '') !== '' || ($filter ?? '') !== '';
@endphp

@section('main_content')
<div class="ab-page ab-page--hub">
    <div class="ab-wrap">
        <header class="ab-header">
            <div>
                <p class="ab-header__eyebrow">Community</p>
                <h1 class="ab-header__title">Users</h1>
                <p class="ab-header__sub">Registered visitors who can write reviews and save brokers.</p>
            </div>
        </header>

        <div class="ab-kpis">
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-users" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Total</p>
                    <p class="ab-kpi__value">{{ number_format($counts['total']) }}</p>
                </div>
            </div>
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-user-check" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Verified</p>
                    <p class="ab-kpi__value">{{ number_format($counts['verified']) }}</p>
                </div>
            </div>
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-user-clock" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Pending</p>
                    <p class="ab-kpi__value">{{ number_format($counts['unverified']) }}</p>
                </div>
            </div>
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-ban" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Suspended</p>
                    <p class="ab-kpi__value">{{ number_format($counts['banned'] ?? 0) }}</p>
                </div>
            </div>
        </div>

        <div class="ab-panel">
            <form method="GET" action="{{ route('admin_users_index') }}" class="ab-filters ab-filters--hub">
                <div class="ab-field">
                    <label for="user-q">Search</label>
                    <input id="user-q" class="ab-input" type="search" name="q" value="{{ $search }}" placeholder="Name or email">
                </div>
                <div class="ab-field">
                    <label for="user-filter">Status</label>
                    <select id="user-filter" class="ab-select" name="filter">
                        <option value="">All</option>
                        <option value="verified" @selected($filter === 'verified')>Verified</option>
                        <option value="unverified" @selected($filter === 'unverified')>Unverified</option>
                        <option value="banned" @selected($filter === 'banned')>Suspended</option>
                    </select>
                </div>
                <div class="ab-header__actions">
                    <button type="submit" class="ab-btn ab-btn--primary">Filter</button>
                    @if($hasFilters)
                        <a href="{{ route('admin_users_index') }}" class="ab-btn ab-btn--ghost">Reset</a>
                    @endif
                </div>
            </form>

            @if($users->isEmpty())
                <div class="ab-empty">
                    <h3>{{ $hasFilters ? 'No users match these filters' : 'No users yet' }}</h3>
                    <p>{{ $hasFilters ? 'Try a different search, or reset the filters.' : 'Registered visitors will appear here.' }}</p>
                    @if($hasFilters)
                        <a href="{{ route('admin_users_index') }}" class="ab-btn ab-btn--ghost">Reset filters</a>
                    @endif
                </div>
            @else
                <div class="ab-table-wrap">
                    <table class="ab-table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Country</th>
                                <th>Reviews</th>
                                <th>Status</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>
                                        <div class="ab-broker">
                                            <span class="ab-logo">
                                                <img src="{{ $user->avatar_url }}" alt="">
                                            </span>
                                            <div>
                                                <p class="ab-broker__name">{{ $user->name }}</p>
                                                <p class="ab-broker__meta">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->country ?? '—' }}</td>
                                    <td>{{ number_format($user->reviews_count) }}</td>
                                    <td>
                                        <div class="ab-pills">
                                            @if($user->is_verified)
                                                <span class="ab-pill ab-pill--ok">Verified</span>
                                            @else
                                                <span class="ab-pill ab-pill--warn">Pending</span>
                                            @endif
                                            @if($user->status === 'banned')
                                                <span class="ab-pill ab-pill--danger">Suspended</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $user->created_at?->format('M j, Y') }}</td>
                                    <td>
                                        <div class="ab-actions">
                                            <a class="ab-btn ab-btn--ghost ab-btn--sm" href="{{ route('admin_users_show', $user->id) }}">View</a>
                                            @if($user->is_verified)
                                                <form action="{{ route('admin_users_unverify', $user->id) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <button class="ab-btn ab-btn--ghost ab-btn--sm" type="submit">Unverify</button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin_users_verify', $user->id) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <button class="ab-btn ab-btn--ghost ab-btn--sm" type="submit">Verify</button>
                                                </form>
                                            @endif
                                            <form action="{{ route('admin_users_toggle_status', $user->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <button class="ab-btn ab-btn--ghost ab-btn--sm" type="submit">{{ $user->status === 'banned' ? 'Reactivate' : 'Suspend' }}</button>
                                            </form>
                                            <form action="{{ route('admin_users_delete', $user->id) }}" method="POST" data-ab-delete data-ab-name="{{ $user->name }}" data-ab-warn="This cannot be undone." data-ab-confirm="Delete user">
                                                @csrf @method('DELETE')
                                                <button class="ab-btn ab-btn--ghost ab-btn--sm" type="submit">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($users->hasPages())
                    <div class="ab-pager">{{ $users->links() }}</div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
