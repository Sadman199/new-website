@extends('admin.layout.app')
@include('admin.partials._ab_assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')
@section('heading', 'Banners')

@php
    $hasFilters = $filters['q'] !== ''
        || $filters['placement'] !== ''
        || $filters['targeting'] !== ''
        || $filters['banner_type'] !== ''
        || $filters['status'] !== ''
        || $filters['broker_id'] !== '';
@endphp

@section('main_content')
<div class="ab-page ab-page--hub">
    <div class="ab-wrap">
        <header class="ab-header">
            <div>
                <p class="ab-header__eyebrow">Marketing</p>
                <h1 class="ab-header__title">Banners</h1>
                <p class="ab-header__sub">Manage promotional banners by placement, broker targeting, and schedule.</p>
            </div>
            <div class="ab-header__actions">
                <a href="{{ route('admin_banners_create') }}" class="ab-btn ab-btn--primary">
                    <i class="fas fa-plus" aria-hidden="true"></i>
                    Add Banner
                </a>
            </div>
        </header>

        <div class="ab-kpis">
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-image" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Total</p>
                    <p class="ab-kpi__value">{{ number_format($stats['total']) }}</p>
                </div>
            </div>
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-check-circle" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Live now</p>
                    <p class="ab-kpi__value">{{ number_format($stats['live']) }}</p>
                </div>
            </div>
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-clock" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Scheduled</p>
                    <p class="ab-kpi__value">{{ number_format($stats['scheduled']) }}</p>
                </div>
            </div>
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-calendar-times" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Expired</p>
                    <p class="ab-kpi__value">{{ number_format($stats['expired']) }}</p>
                </div>
            </div>
        </div>

        <div class="ab-panel">
            <form method="GET" action="{{ route('admin_banners_index') }}" class="ab-filters ab-filters--hub">
                <div class="ab-field">
                    <label for="banner-q">Search</label>
                    <input id="banner-q" class="ab-input" type="search" name="q" value="{{ $filters['q'] }}" placeholder="Title or URL">
                </div>
                <div class="ab-field">
                    <label for="banner-placement">Placement</label>
                    <select id="banner-placement" class="ab-select" name="placement">
                        <option value="">All placements</option>
                        @foreach(($placementGroups ?? ['Pages' => $placements]) as $group => $items)
                            <optgroup label="{{ $group }}">
                                @foreach($items as $value => $label)
                                    <option value="{{ $value }}" @selected($filters['placement'] === $value)>{{ $label }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
                <div class="ab-field">
                    <label for="banner-type">Type</label>
                    <select id="banner-type" class="ab-select" name="banner_type">
                        <option value="">All types</option>
                        @foreach($types as $value => $label)
                            <option value="{{ $value }}" @selected($filters['banner_type'] === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ab-field">
                    <label for="banner-targeting">Targeting</label>
                    <select id="banner-targeting" class="ab-select" name="targeting">
                        <option value="">All targeting</option>
                        @foreach($targetingOptions as $value => $label)
                            <option value="{{ $value }}" @selected($filters['targeting'] === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ab-field">
                    <label for="banner-status">Status</label>
                    <select id="banner-status" class="ab-select" name="status">
                        <option value="">All</option>
                        <option value="active" @selected($filters['status'] === 'active')>Live now</option>
                        <option value="scheduled" @selected($filters['status'] === 'scheduled')>Scheduled</option>
                        <option value="expired" @selected($filters['status'] === 'expired')>Expired</option>
                        <option value="inactive" @selected($filters['status'] === 'inactive')>Inactive</option>
                    </select>
                </div>
                <div class="ab-field">
                    <label for="banner-broker">Broker</label>
                    <select id="banner-broker" class="ab-select" name="broker_id">
                        <option value="">All brokers</option>
                        @foreach($brokers as $broker)
                            <option value="{{ $broker->id }}" @selected((string) $filters['broker_id'] === (string) $broker->id)>{{ $broker->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ab-header__actions">
                    <button type="submit" class="ab-btn ab-btn--primary">Filter</button>
                    @if($hasFilters)
                        <a href="{{ route('admin_banners_index') }}" class="ab-btn ab-btn--ghost">Reset</a>
                    @endif
                </div>
            </form>

            @if($banners->isEmpty())
                <div class="ab-empty">
                    <h3>{{ $hasFilters ? 'No banners match these filters' : 'No banners yet' }}</h3>
                    <p>{{ $hasFilters ? 'Try a different search, or reset the filters.' : 'Add a banner to show promotions across the site.' }}</p>
                    @if($hasFilters)
                        <a href="{{ route('admin_banners_index') }}" class="ab-btn ab-btn--ghost">Reset filters</a>
                    @else
                        <a href="{{ route('admin_banners_create') }}" class="ab-btn ab-btn--primary">Add Banner</a>
                    @endif
                </div>
            @else
                <div class="ab-table-wrap">
                    <table class="ab-table">
                        <thead>
                            <tr>
                                <th>Banner</th>
                                <th>Broker(s)</th>
                                <th>Placement</th>
                                <th>Start</th>
                                <th>End</th>
                                <th>Status</th>
                                <th>Priority</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($banners as $row)
                                @php
                                    $thumb = $row->previewImageUrl();
                                    $status = $row->scheduleStatus();
                                    $statusClass = match ($status) {
                                        'active' => 'ab-pill--ok',
                                        'scheduled' => 'ab-pill--warn',
                                        'expired' => 'ab-pill--danger',
                                        default => '',
                                    };
                                @endphp
                                <tr>
                                    <td>
                                        <div class="ab-broker">
                                            <span class="ab-logo">
                                                @if($row->isHtmlCreative())
                                                    <span>&lt;/&gt;</span>
                                                @elseif($thumb)
                                                    <img src="{{ $thumb }}" alt="">
                                                @else
                                                    <span>{{ strtoupper(substr($row->title ?: 'B', 0, 1)) }}</span>
                                                @endif
                                            </span>
                                            <div>
                                                <p class="ab-broker__name">{{ $row->title }}</p>
                                                <p class="ab-broker__meta">{{ $row->formatLabel() }} · {{ $row->typeLabel() }} · {{ $row->targetingLabel() }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($row->brokers->isNotEmpty())
                                            {{ $row->brokers->pluck('name')->join(', ') }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>{{ $row->placementLabel() }}</td>
                                    <td>{{ $row->start_date?->format('M j, Y') }}</td>
                                    <td>{{ $row->end_date?->format('M j, Y') }}</td>
                                    <td>
                                        <span class="ab-pill {{ $statusClass }}">{{ $row->scheduleStatusLabel() }}</span>
                                    </td>
                                    <td>{{ $row->priority }}</td>
                                    <td>
                                        <div class="ab-actions">
                                            <a class="ab-btn ab-btn--ghost ab-btn--sm" href="{{ route('admin_banners_edit', $row->id) }}">Edit</a>
                                            <form action="{{ route('admin_banners_toggle', $row->id) }}" method="POST">
                                                @csrf
                                                <button class="ab-btn ab-btn--ghost ab-btn--sm" type="submit">{{ $row->is_active ? 'Turn off' : 'Turn on' }}</button>
                                            </form>
                                            <form action="{{ route('admin_banners_delete', $row->id) }}" method="POST" data-ab-delete data-ab-name="{{ $row->title }}" data-ab-warn="This cannot be undone." data-ab-confirm="Delete banner">
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
                @if($banners->hasPages())
                    <div class="ab-pager">{{ $banners->links() }}</div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
