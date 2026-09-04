@extends('admin.layout.app')
@include('admin.partials._ab_assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')
@section('heading', 'Trading Tools')

@section('main_content')
<div class="ab-page ab-page--hub">
    <div class="ab-wrap">
        <header class="ab-header">
            <div>
                <p class="ab-header__eyebrow">Marketing</p>
                <h1 class="ab-header__title">Trading Tools</h1>
                <p class="ab-header__sub">Manage names, categories, SEO, FAQs, and related tools or brokers. Calculator math stays in the site code so public URLs do not change.</p>
            </div>
        </header>

        <div class="ab-kpis">
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-calculator" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Total</p>
                    <p class="ab-kpi__value">{{ number_format($stats['total']) }}</p>
                </div>
            </div>
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-eye" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Visible</p>
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
        </div>

        <div class="ab-panel">
            @if($tools->isEmpty())
                <div class="ab-empty">
                    <h3>No trading tools found</h3>
                    <p>Seed the trading tools table so they appear on the public dashboard.</p>
                </div>
            @else
                <div class="ab-table-wrap">
                    <table class="ab-table">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Tool</th>
                                <th>Category</th>
                                <th>Slug</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tools as $tool)
                                <tr>
                                    <td>{{ $tool->sort_order }}</td>
                                    <td>
                                        <p class="ab-broker__name"><i class="{{ $tool->icon }}" aria-hidden="true"></i> {{ $tool->name }}</p>
                                        <p class="ab-broker__meta">{{ $tool->short_description }}</p>
                                    </td>
                                    <td>{{ \App\Support\TradingToolCategories::label($tool->categoryKey()) }}</td>
                                    <td>{{ $tool->slug }}</td>
                                    <td>
                                        <span class="ab-pill {{ $tool->is_active ? 'ab-pill--ok' : '' }}">{{ $tool->is_active ? 'Visible' : 'Hidden' }}</span>
                                    </td>
                                    <td>
                                        <div class="ab-actions">
                                            <a class="ab-btn ab-btn--ghost ab-btn--sm" href="{{ route('admin_trading_tools_edit', $tool->id) }}">Edit</a>
                                            <form action="{{ route('admin_trading_tools_toggle', $tool->id) }}" method="POST">
                                                @csrf
                                                <button class="ab-btn ab-btn--ghost ab-btn--sm" type="submit">{{ $tool->is_active ? 'Hide' : 'Show' }}</button>
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
