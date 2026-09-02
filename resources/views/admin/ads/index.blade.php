@extends('admin.layout.app')
@include('admin.partials._ab_assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')
@section('heading', 'Popup Ads')

@php
    $hasFilters = request()->filled('q') || request()->filled('type') || request()->filled('active');
@endphp

@section('main_content')
<div class="ab-page ab-page--hub">
    <div class="ab-wrap">
        @include('admin.ads._nav', ['active' => 'popups'])
        <header class="ab-header">
            <div>
                <p class="ab-header__eyebrow">Advertisements</p>
                <h1 class="ab-header__title">Popup Ads</h1>
                <p class="ab-header__sub">Timed, scroll, and stay-triggered campaigns on public pages.</p>
            </div>
            <div class="ab-header__actions">
                <a href="{{ route('admin_ads_create') }}" class="ab-btn ab-btn--primary">
                    <i class="fas fa-plus" aria-hidden="true"></i>
                    Add Popup Ad
                </a>
            </div>
        </header>

        <div class="ab-kpis">
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-bullhorn" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Total</p>
                    <p class="ab-kpi__value">{{ number_format($stats['total']) }}</p>
                </div>
            </div>
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-check-circle" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Active</p>
                    <p class="ab-kpi__value">{{ number_format($stats['active']) }}</p>
                </div>
            </div>
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-window-restore" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Popups</p>
                    <p class="ab-kpi__value">{{ number_format($stats['popups']) }}</p>
                </div>
            </div>
        </div>

        <div class="ab-panel">
            <form method="GET" action="{{ route('admin_ads_index') }}" class="ab-filters ab-filters--hub">
                <div class="ab-field">
                    <label for="ad-q">Search</label>
                    <input id="ad-q" class="ab-input" type="search" name="q" value="{{ request('q') }}" placeholder="Title or campaign">
                </div>
                <div class="ab-field">
                    <label for="ad-type">Type</label>
                    <select id="ad-type" class="ab-select" name="type">
                        <option value="">All types</option>
                        @foreach(['popup','banner','image','video','text','custom'] as $t)
                            <option value="{{ $t }}" @selected(request('type') === $t)>{{ ucfirst($t) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ab-field">
                    <label for="ad-active">Status</label>
                    <select id="ad-active" class="ab-select" name="active">
                        <option value="">All</option>
                        <option value="1" @selected(request('active') === '1')>Active</option>
                        <option value="0" @selected(request('active') === '0')>Off</option>
                    </select>
                </div>
                <div class="ab-header__actions">
                    <button type="submit" class="ab-btn ab-btn--primary">Filter</button>
                    @if($hasFilters)
                        <a href="{{ route('admin_ads_index') }}" class="ab-btn ab-btn--ghost">Reset</a>
                    @endif
                </div>
            </form>

            @if($ads->isEmpty())
                <div class="ab-empty">
                    <h3>{{ $hasFilters ? 'No ads match these filters' : 'No popup ads yet' }}</h3>
                    <p>{{ $hasFilters ? 'Try a different search, or reset the filters.' : 'Create a popup or campaign ad for public pages.' }}</p>
                    @if($hasFilters)
                        <a href="{{ route('admin_ads_index') }}" class="ab-btn ab-btn--ghost">Reset filters</a>
                    @else
                        <a href="{{ route('admin_ads_create') }}" class="ab-btn ab-btn--primary">Add Popup Ad</a>
                    @endif
                </div>
            @else
                <div class="ab-table-wrap">
                    <table class="ab-table">
                        <thead>
                            <tr>
                                <th>Ad</th>
                                <th>Type</th>
                                <th>Trigger</th>
                                <th>Dates</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ads as $ad)
                                <tr>
                                    <td>
                                        <div class="ab-broker">
                                            <span class="ab-logo">
                                                @if($ad->image_url)
                                                    <img src="{{ $ad->image_url }}" alt="">
                                                @else
                                                    <span>{{ strtoupper(substr($ad->title ?: 'A', 0, 1)) }}</span>
                                                @endif
                                            </span>
                                            <div>
                                                <p class="ab-broker__name">{{ $ad->title }}</p>
                                                <p class="ab-broker__meta">{{ $ad->category ?: 'Priority '.$ad->priority }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ ucfirst($ad->type) }}</td>
                                    <td>
                                        @if($ad->type === 'popup')
                                            {{ $ad->trigger_type }} {{ $ad->trigger_value }}{{ $ad->trigger_type === 'scroll' ? '%' : ($ad->trigger_type === 'time' ? 's' : 'm') }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>
                                        <div>{{ optional($ad->start_date)->format('M j, Y') ?? 'Anytime' }}</div>
                                        <div class="ab-broker__meta">{{ optional($ad->end_date)->format('M j, Y') ?? 'No end' }}</div>
                                    </td>
                                    <td>
                                        <span class="ab-pill {{ $ad->is_active ? 'ab-pill--ok' : '' }}">{{ $ad->is_active ? 'Active' : 'Off' }}</span>
                                    </td>
                                    <td>
                                        <div class="ab-actions">
                                            <a class="ab-btn ab-btn--ghost ab-btn--sm" href="{{ route('admin_ads_edit', $ad->id) }}">Edit</a>
                                            <form action="{{ route('admin_ads_toggle', $ad->id) }}" method="POST">
                                                @csrf
                                                <button class="ab-btn ab-btn--ghost ab-btn--sm" type="submit">{{ $ad->is_active ? 'Turn off' : 'Turn on' }}</button>
                                            </form>
                                            <form action="{{ route('admin_ads_delete', $ad->id) }}" method="POST" data-ab-delete data-ab-name="{{ $ad->title }}" data-ab-warn="This cannot be undone." data-ab-confirm="Delete ad">
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
                @if($ads->hasPages())
                    <div class="ab-pager">{{ $ads->links() }}</div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
