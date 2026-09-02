@extends('admin.layout.app')
@include('admin.partials._ab_assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')
@section('heading', 'FAQ Section')

@php
    $hasFilters = $filters['q'] !== ''
        || $filters['broker_id'] !== ''
        || ($filters['sort'] !== '' && $filters['sort'] !== 'newest');
@endphp

@section('main_content')
<div class="ab-page ab-page--hub">
    <div class="ab-wrap">
        <header class="ab-header">
            <div>
                <p class="ab-header__eyebrow">Content</p>
                <h1 class="ab-header__title">FAQ Section</h1>
                <p class="ab-header__sub">Questions and answers shown on broker pages.</p>
            </div>
            <div class="ab-header__actions">
                <a href="{{ route('admin_faq_create') }}" class="ab-btn ab-btn--primary">
                    <i class="fas fa-plus" aria-hidden="true"></i>
                    Add FAQ
                </a>
            </div>
        </header>

        <div class="ab-kpis">
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-question-circle" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Total</p>
                    <p class="ab-kpi__value">{{ number_format($stats['total']) }}</p>
                </div>
            </div>
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-briefcase" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Brokers with FAQs</p>
                    <p class="ab-kpi__value">{{ number_format($stats['brokers']) }}</p>
                </div>
            </div>
        </div>

        <div class="ab-panel">
            <form method="GET" action="{{ route('admin_faq_show') }}" class="ab-filters ab-filters--hub">
                <div class="ab-field">
                    <label for="faq-q">Search</label>
                    <input id="faq-q" class="ab-input" type="search" name="q" value="{{ $filters['q'] }}" placeholder="Question">
                </div>
                <div class="ab-field">
                    <label for="faq-broker">Broker</label>
                    <select id="faq-broker" class="ab-select" name="broker_id">
                        <option value="">All brokers</option>
                        @foreach($brokers as $broker)
                            <option value="{{ $broker->id }}" @selected((string) $filters['broker_id'] === (string) $broker->id)>{{ $broker->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ab-field">
                    <label for="faq-sort">Sort</label>
                    <select id="faq-sort" class="ab-select" name="sort">
                        <option value="newest" @selected($filters['sort'] === 'newest')>Newest</option>
                        <option value="title" @selected($filters['sort'] === 'title')>Title A–Z</option>
                    </select>
                </div>
                <div class="ab-header__actions">
                    <button type="submit" class="ab-btn ab-btn--primary">Filter</button>
                    @if($hasFilters)
                        <a href="{{ route('admin_faq_show') }}" class="ab-btn ab-btn--ghost">Reset</a>
                    @endif
                </div>
            </form>

            @if($faq_data->isEmpty())
                <div class="ab-empty">
                    <h3>{{ $hasFilters ? 'No FAQs match these filters' : 'No FAQs yet' }}</h3>
                    <p>{{ $hasFilters ? 'Try a different search, or reset the filters.' : 'Add the first question for a broker page.' }}</p>
                    @if($hasFilters)
                        <a href="{{ route('admin_faq_show') }}" class="ab-btn ab-btn--ghost">Reset filters</a>
                    @else
                        <a href="{{ route('admin_faq_create') }}" class="ab-btn ab-btn--primary">Add FAQ</a>
                    @endif
                </div>
            @else
                <div class="ab-table-wrap">
                    <table class="ab-table">
                        <thead>
                            <tr>
                                <th>Question</th>
                                <th>Broker</th>
                                <th>Language</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($faq_data as $row)
                                <tr>
                                    <td>
                                        <p class="ab-broker__name">{{ $row->faq_title }}</p>
                                    </td>
                                    <td>{{ $row->broker?->name ?? '—' }}</td>
                                    <td>{{ $row->rLanguage->name ?? '—' }}</td>
                                    <td>
                                        <div class="ab-actions">
                                            <a class="ab-btn ab-btn--ghost ab-btn--sm" href="{{ route('admin_faq_view', $row->id) }}">View</a>
                                            <a class="ab-btn ab-btn--ghost ab-btn--sm" href="{{ route('admin_faq_edit', $row->id) }}">Edit</a>
                                            <form action="{{ route('admin_faq_delete', $row->id) }}" method="POST" data-ab-delete data-ab-name="{{ $row->faq_title }}" data-ab-warn="This cannot be undone." data-ab-confirm="Delete FAQ">
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
                @if($faq_data->hasPages())
                    <div class="ab-pager">{{ $faq_data->links() }}</div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
