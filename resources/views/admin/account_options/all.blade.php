@extends('admin.layout.app')
@include('admin.brokers._assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')

@section('heading', 'Account Options')

@section('main_content')
<div class="ab-page">
    <div class="ab-wrap">
        <header class="ab-header">
            <div>
                <p class="ab-header__eyebrow">Brokers</p>
                <h1 class="ab-header__title">Account Options</h1>
                <p class="ab-header__sub">Every account type across the directory — filter by broker, status, or name.</p>
            </div>
            <div class="ab-header__actions">
                <a href="{{ route('admin_broker_show') }}" class="ab-btn ab-btn--ghost">
                    <i class="fas fa-briefcase" aria-hidden="true"></i>
                    Manage brokers
                </a>
            </div>
        </header>

        <div class="ab-kpis">
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-layer-group" aria-hidden="true"></i></span>
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
                <span class="ab-kpi__icon"><i class="fas fa-eye-slash" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Hidden</p>
                    <p class="ab-kpi__value">{{ number_format($stats['hidden']) }}</p>
                </div>
            </div>
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-briefcase" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Brokers</p>
                    <p class="ab-kpi__value">{{ number_format($stats['brokers']) }}</p>
                </div>
            </div>
        </div>

        <div class="ab-panel">
            <form method="GET" action="{{ route('admin_account_options_all') }}" class="ab-filters">
                <div class="ab-field">
                    <label for="ab-q">Search</label>
                    <input id="ab-q" class="ab-input" type="search" name="q" value="{{ $filters['q'] }}" placeholder="Account type, currency, broker…">
                </div>
                <div class="ab-field">
                    <label for="ab-broker">Broker</label>
                    <select id="ab-broker" class="ab-select" name="broker_id">
                        <option value="">All brokers</option>
                        @foreach($brokers as $b)
                            <option value="{{ $b->id }}" @selected((string) $filters['broker_id'] === (string) $b->id)>{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ab-field">
                    <label for="ab-status">Status</label>
                    <select id="ab-status" class="ab-select" name="status">
                        <option value="">All statuses</option>
                        <option value="active" @selected($filters['status'] === 'active')>Active</option>
                        <option value="hidden" @selected($filters['status'] === 'hidden')>Hidden</option>
                    </select>
                </div>
                <div class="ab-header__actions">
                    <button type="submit" class="ab-btn ab-btn--primary">Filter</button>
                    @if($filters['q'] !== '' || $filters['broker_id'] !== '' || $filters['status'] !== '')
                        <a href="{{ route('admin_account_options_all') }}" class="ab-btn ab-btn--ghost">Reset</a>
                    @endif
                </div>
            </form>

            @if($accountOptions->isEmpty())
                <div class="ab-empty">
                    <h3>No account options match these filters</h3>
                    <p>Try a different search, or open a broker and add the first account type.</p>
                    <a href="{{ route('admin_broker_show') }}" class="ab-btn ab-btn--primary">Open brokers</a>
                </div>
            @else
                <div class="ab-table-wrap">
                    <table class="ab-table">
                        <thead>
                            <tr>
                                <th>Account</th>
                                <th>Currency</th>
                                <th>Min. deposit</th>
                                <th>Leverage</th>
                                <th>Spread</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($accountOptions as $option)
                                <tr>
                                    <td>
                                        <p class="ab-broker__name">{{ $option->account_type }}</p>
                                        <p class="ab-broker__meta">
                                            <a href="{{ route('admin_account_options_index', $option->broker_id) }}">{{ $option->broker?->name ?? '—' }}</a>
                                            @if($option->slug) · {{ $option->slug }}@endif
                                        </p>
                                        <div class="ab-pills">
                                            @if($option->is_active)
                                                <span class="ab-pill ab-pill--ok">Active</span>
                                            @else
                                                <span class="ab-pill">Hidden</span>
                                            @endif
                                            @if($option->swap_free)<span class="ab-pill">Swap-free</span>@endif
                                            @if($option->access_to_pro_features)<span class="ab-pill ab-pill--warn">Pro</span>@endif
                                        </div>
                                    </td>
                                    <td>{{ $option->account_currency ?: '—' }}</td>
                                    <td>{{ $option->min_deposit !== null ? '$'.number_format((float) $option->min_deposit, 0) : '—' }}</td>
                                    <td>{{ $option->leverage_label ?: '—' }}</td>
                                    <td>{{ $option->spread_label ?: '—' }}</td>
                                    <td>
                                        <div class="ab-actions">
                                            <a class="ab-btn ab-btn--ghost ab-btn--sm" href="{{ route('admin_account_options_index', $option->broker_id) }}">Broker</a>
                                            <a class="ab-btn ab-btn--ghost ab-btn--sm" href="{{ route('admin_account_options_edit', [$option->broker_id, $option->id]) }}">Edit</a>
                                            <form action="{{ route('admin_account_options_delete', [$option->broker_id, $option->id]) }}" method="POST" data-ab-delete data-ab-name="{{ $option->account_type }}" data-ab-warn="This account type will be removed from {{ $option->broker?->name ?? 'this broker' }}." data-ab-confirm="Delete account">
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
                    <span class="ab-broker__meta">Showing {{ $accountOptions->firstItem() }}–{{ $accountOptions->lastItem() }} of {{ $accountOptions->total() }}</span>
                    {{ $accountOptions->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
