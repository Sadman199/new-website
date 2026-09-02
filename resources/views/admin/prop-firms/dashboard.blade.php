@extends('admin.layout.app')
@include('admin.brokers._assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')

@section('heading', 'Prop Firms Dashboard')

@section('main_content')
<div class="ab-page">
    <div class="ab-wrap">
        <header class="ab-header">
            <div>
                <p class="ab-header__eyebrow">Prop firms</p>
                <h1 class="ab-header__title">Dashboard</h1>
                <p class="ab-header__sub">Track listings, programs, reviews, and jump into editing from one place.</p>
            </div>
            <div class="ab-header__actions">
                <a href="{{ route('prop_firms.index') }}" class="ab-btn ab-btn--ghost" target="_blank" rel="noopener">View on site</a>
                <a href="{{ route('admin_prop_firms_create') }}" class="ab-btn ab-btn--primary">
                    <i class="fas fa-plus" aria-hidden="true"></i>
                    Add prop firm
                </a>
            </div>
        </header>

        @include('admin.prop-firms._nav', ['active' => 'dashboard'])

        <div class="ab-kpis">
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-building" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Total firms</p>
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
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-layer-group" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Programs</p>
                    <p class="ab-kpi__value">{{ number_format($stats['programs']) }}</p>
                </div>
            </div>
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-comments" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Reviews</p>
                    <p class="ab-kpi__value">{{ number_format($stats['reviews']) }}</p>
                </div>
            </div>
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-question-circle" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">FAQs</p>
                    <p class="ab-kpi__value">{{ number_format($stats['faqs']) }}</p>
                </div>
            </div>
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-tags" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Categories</p>
                    <p class="ab-kpi__value">{{ number_format($stats['categories']) }}</p>
                </div>
            </div>
        </div>

        <div class="ab-shortcuts">
            <a class="ab-shortcut" href="{{ route('admin_prop_firms_show') }}">
                <span class="ab-kpi__icon"><i class="fas fa-building" aria-hidden="true"></i></span>
                <div>
                    <strong>All firms</strong>
                    <span>Search, filter, and bulk manage listings.</span>
                </div>
            </a>
            <a class="ab-shortcut" href="{{ route('admin_prop_firm_programs_show') }}">
                <span class="ab-kpi__icon"><i class="fas fa-layer-group" aria-hidden="true"></i></span>
                <div>
                    <strong>Programs</strong>
                    <span>{{ number_format($stats['programs']) }} funding programs on file.</span>
                </div>
            </a>
            <a class="ab-shortcut" href="{{ route('admin_prop_firm_reviews_show') }}">
                <span class="ab-kpi__icon"><i class="fas fa-comments" aria-hidden="true"></i></span>
                <div>
                    <strong>Reviews</strong>
                    <span>Moderate {{ number_format($stats['reviews']) }} user and editor reviews.</span>
                </div>
            </a>
            <a class="ab-shortcut" href="{{ route('admin_prop_firm_settings_edit') }}">
                <span class="ab-kpi__icon"><i class="fas fa-cog" aria-hidden="true"></i></span>
                <div>
                    <strong>Settings</strong>
                    <span>Default sort, reviews, FAQs, and programs.</span>
                </div>
            </a>
        </div>

        <div class="ab-panel">
            <div class="ab-section__head">
                <h2>Recently added</h2>
                <p>Jump back into editing from the latest listings.</p>
            </div>

            @if($recent->isEmpty())
                <div class="ab-empty">
                    <h3>No prop firms yet</h3>
                    <p>Add the first listing to start building the directory.</p>
                    <a href="{{ route('admin_prop_firms_create') }}" class="ab-btn ab-btn--primary">Add prop firm</a>
                </div>
            @else
                <div class="ab-table-wrap">
                    <table class="ab-table">
                        <thead>
                            <tr>
                                <th>Firm</th>
                                <th>Category</th>
                                <th>Trust</th>
                                <th>Programs</th>
                                <th>Added</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recent as $firm)
                                @php $logo = $firm->mediaUrl(); @endphp
                                <tr>
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
                                                <p class="ab-broker__meta">{{ $firm->slug }}</p>
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
                                    <td>{{ number_format($firm->programs_count) }}</td>
                                    <td>{{ $firm->created_at?->format('M d, Y') ?: '—' }}</td>
                                    <td>
                                        <div class="ab-actions">
                                            <a class="ab-btn ab-btn--ghost ab-btn--sm" href="{{ route('admin_prop_firms_edit', $firm->id) }}">Edit</a>
                                            @if($firm->slug)
                                                <a class="ab-btn ab-btn--ghost ab-btn--sm" href="{{ route('prop_firms.show', $firm->slug) }}" target="_blank" rel="noopener">Site</a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="ab-foot">
                    <span class="ab-broker__meta">Showing the {{ $recent->count() }} most recent listings</span>
                    <a href="{{ route('admin_prop_firms_show') }}" class="ab-btn ab-btn--ghost ab-btn--sm">View all firms</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
