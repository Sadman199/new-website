@extends('admin.layout.app')
@include('admin.brokers._assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')

@section('heading', 'Account Options')

@section('main_content')
<div class="ab-page">
    <div class="ab-wrap">
        <header class="ab-header">
            <div class="ab-identity">
                <span class="ab-logo ab-logo--lg">
                    @if($broker->mediaUrl())
                        <img src="{{ $broker->mediaUrl() }}" alt="">
                    @else
                        <span>{{ strtoupper(substr($broker->name, 0, 1)) }}</span>
                    @endif
                </span>
                <div>
                    <p class="ab-header__eyebrow">Account Options</p>
                    <h1 class="ab-header__title">{{ $broker->name }}</h1>
                    <p class="ab-header__sub">{{ $broker->slug }} @if($broker->country) · {{ $broker->country }} @endif</p>
                </div>
            </div>
            <div class="ab-header__actions">
                <a href="{{ route('admin_account_options_all') }}" class="ab-btn ab-btn--ghost">All accounts</a>
                <a href="{{ route('admin_account_options_create', $broker->id) }}" class="ab-btn ab-btn--primary">
                    <i class="fas fa-plus" aria-hidden="true"></i>
                    Add account option
                </a>
            </div>
        </header>

        @include('admin.brokers._tabs', ['broker' => $broker, 'activeTab' => 'account-options'])

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
                <span class="ab-kpi__icon"><i class="fas fa-moon" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Swap-free</p>
                    <p class="ab-kpi__value">{{ number_format($stats['swap_free']) }}</p>
                </div>
            </div>
        </div>

        <div class="ab-panel">
            @if($accountOptions->isEmpty())
                <div class="ab-empty">
                    <h3>No account options yet</h3>
                    <p>Add Standard, ECN, Islamic, or any other account type this broker offers.</p>
                    <a href="{{ route('admin_account_options_create', $broker->id) }}" class="ab-btn ab-btn--primary">Add the first one</a>
                </div>
            @else
                <div class="ab-table-wrap">
                    <table class="ab-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Account type</th>
                                <th>Currency</th>
                                <th>Min. deposit</th>
                                <th>Leverage</th>
                                <th>Spread</th>
                                <th>Commission</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($accountOptions as $option)
                                <tr>
                                    <td>{{ $option->sort_order ?: $loop->iteration }}</td>
                                    <td>
                                        <p class="ab-broker__name">{{ $option->account_type }}</p>
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
                                    <td>{{ $option->commission_display ?: 'None' }}</td>
                                    <td>
                                        <div class="ab-actions">
                                            <a class="ab-btn ab-btn--ghost ab-btn--sm" href="{{ route('admin_account_options_edit', [$broker->id, $option->id]) }}">Edit</a>
                                            <form action="{{ route('admin_account_options_delete', [$broker->id, $option->id]) }}" method="POST" data-ab-delete data-ab-name="{{ $option->account_type }}" data-ab-warn="This account type will be removed from {{ $broker->name }}." data-ab-confirm="Delete account">
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
            @endif
        </div>
    </div>
</div>
@endsection
