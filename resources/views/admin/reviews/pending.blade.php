@extends('admin.layout.app')
@include('admin.partials._ab_assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')
@section('heading', 'User Reviews')

@php
    $hasFilters = $filters['q'] !== ''
        || $filters['kind'] !== ''
        || $filters['broker_id'] !== ''
        || $filters['status'] !== 'pending';
    $statusLabel = ['pending' => 'Pending', 'approved' => 'Approved', 'declined' => 'Declined'][$filters['status']] ?? 'Pending';
@endphp

@section('main_content')
<div class="ab-page ab-page--hub">
    <div class="ab-wrap">
        <header class="ab-header">
            <div>
                <p class="ab-header__eyebrow">Moderation</p>
                <h1 class="ab-header__title">User Reviews</h1>
                <p class="ab-header__sub">Approve accurate reviews and replies. Decline spam or policy issues.</p>
            </div>
        </header>

        <nav class="ab-tabs" aria-label="Review status">
            <a href="{{ route('reviews.pending') }}" class="{{ $filters['status'] === 'pending' ? 'is-active' : '' }}">
                Pending <span class="ab-count">{{ number_format($stats['pending']) }}</span>
            </a>
            <a href="{{ route('reviews.pending', ['status' => 'approved']) }}" class="{{ $filters['status'] === 'approved' ? 'is-active' : '' }}">
                Approved <span class="ab-count">{{ number_format($stats['approved']) }}</span>
            </a>
            <a href="{{ route('reviews.pending', ['status' => 'declined']) }}" class="{{ $filters['status'] === 'declined' ? 'is-active' : '' }}">
                Declined <span class="ab-count">{{ number_format($stats['declined']) }}</span>
            </a>
        </nav>

        <div class="ab-kpis">
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-clock" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Pending</p>
                    <p class="ab-kpi__value">{{ number_format($stats['pending']) }}</p>
                </div>
            </div>
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-check" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Approved</p>
                    <p class="ab-kpi__value">{{ number_format($stats['approved']) }}</p>
                </div>
            </div>
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-times" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Declined</p>
                    <p class="ab-kpi__value">{{ number_format($stats['declined']) }}</p>
                </div>
            </div>
        </div>

        <div class="ab-panel" style="margin-bottom:1.15rem">
            <form method="GET" action="{{ route('reviews.pending') }}" class="ab-filters ab-filters--hub">
                <input type="hidden" name="status" value="{{ $filters['status'] }}">
                <div class="ab-field">
                    <label for="review-q">Search</label>
                    <input id="review-q" class="ab-input" type="search" name="q" value="{{ $filters['q'] }}" placeholder="Name, email, or text">
                </div>
                <div class="ab-field">
                    <label for="review-kind">Type</label>
                    <select id="review-kind" class="ab-select" name="kind">
                        <option value="">Reviews and replies</option>
                        <option value="review" @selected($filters['kind'] === 'review')>Reviews only</option>
                        <option value="reply" @selected($filters['kind'] === 'reply')>Replies only</option>
                    </select>
                </div>
                <div class="ab-field">
                    <label for="review-broker">Broker</label>
                    <select id="review-broker" class="ab-select" name="broker_id">
                        <option value="">All brokers</option>
                        @foreach($brokers as $broker)
                            <option value="{{ $broker->id }}" @selected((string) $filters['broker_id'] === (string) $broker->id)>{{ $broker->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ab-header__actions">
                    <button type="submit" class="ab-btn ab-btn--primary">Filter</button>
                    @if($hasFilters)
                        <a href="{{ route('reviews.pending', $filters['status'] === 'pending' ? [] : ['status' => $filters['status']]) }}" class="ab-btn ab-btn--ghost">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        @if($reviews->isEmpty())
            <div class="ab-empty">
                <h3>{{ $hasFilters ? 'No reviews match these filters' : 'No '.$statusLabel.' reviews' }}</h3>
                <p>{{ $hasFilters ? 'Try a different search, or reset the filters.' : 'New submissions will appear here for moderation.' }}</p>
            </div>
        @else
            <div class="ab-review-grid">
                @foreach($reviews as $review)
                    <article class="ab-review-card">
                        <div class="ab-review-card__top">
                            <div>
                                <p class="ab-broker__name mb-0">{{ $review->name }}</p>
                                <p class="ab-note mb-0">{{ $review->email }}</p>
                            </div>
                            <div class="ab-pills">
                                @if($review->isReply())
                                    <span class="ab-pill">Reply</span>
                                @else
                                    <span class="ab-pill">Review</span>
                                    @if($review->rating)
                                        <span class="ab-pill ab-pill--warn">{{ $review->rating }}/5</span>
                                    @endif
                                @endif
                            </div>
                        </div>

                        <dl class="ab-dl" style="margin:0">
                            <div>
                                <dt>Broker</dt>
                                <dd>{{ $review->broker->name ?? '—' }}</dd>
                            </div>
                            <div>
                                <dt>Country</dt>
                                <dd>{{ $review->country ?? '—' }}</dd>
                            </div>
                        </dl>

                        @if($review->isReply() && $review->parent)
                            <p class="ab-note mb-0"><strong>In reply to:</strong> {{ \Illuminate\Support\Str::limit($review->parent->description, 140) }}</p>
                        @endif

                        @unless($review->isReply())
                            <div class="ab-pills">
                                @if($review->rating_cost)<span class="ab-pill">Cost {{ $review->rating_cost }}/5</span>@endif
                                @if($review->rating_platforms)<span class="ab-pill">Platforms {{ $review->rating_platforms }}/5</span>@endif
                                @if($review->rating_customer_support)<span class="ab-pill">Support {{ $review->rating_customer_support }}/5</span>@endif
                                @if($review->lengthOfUseLabel())<span class="ab-pill">{{ $review->lengthOfUseLabel() }}</span>@endif
                                @if($review->account_type)<span class="ab-pill">{{ $review->account_type }}</span>@endif
                            </div>
                        @endunless

                        <p class="mb-0">{{ $review->description }}</p>

                        @if($filters['status'] === 'pending')
                            <div class="ab-actions" style="justify-content:flex-start">
                                <form action="{{ route('reviews.approve', $review->id) }}" method="POST">
                                    @csrf
                                    <button class="ab-btn ab-btn--primary ab-btn--sm" type="submit">Approve</button>
                                </form>
                                <form action="{{ route('reviews.decline', $review->id) }}" method="POST">
                                    @csrf
                                    <button class="ab-btn ab-btn--ghost ab-btn--sm" type="submit">Decline</button>
                                </form>
                            </div>
                        @endif
                    </article>
                @endforeach
            </div>
            @if($reviews->hasPages())
                <div class="ab-pager">{{ $reviews->links() }}</div>
            @endif
        @endif
    </div>
</div>
@endsection
