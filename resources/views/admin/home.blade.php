@extends('admin.layout.app')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')

@section('main_content')
@php
    $firstName = explode(' ', $adminName)[0];
    $hour = now()->hour;
    $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
    $today = now()->format('l, F j');
@endphp

<div class="adm-dash">
    <div class="adm-wrap">

        {{-- ── Header ─────────────────────────────────────────────── --}}
        <header class="adm-header">
            <div class="adm-header__left">
                <p class="adm-header__date">{{ $today }}</p>
                <h1 class="adm-header__title">{{ $greeting }}, {{ $firstName }}</h1>
                <p class="adm-header__sub">Here's what's happening across your platform today.</p>
            </div>
            <div class="adm-header__actions">
                <a href="{{ route('home') }}" target="_blank" rel="noopener" class="adm-btn adm-btn--ghost">
                    <i class="fas fa-external-link-alt"></i>
                    View site
                </a>
                <a href="{{ route('admin_broker_create') }}" class="adm-btn adm-btn--primary">
                    <i class="fas fa-plus"></i>
                    New broker
                </a>
            </div>
        </header>

        {{-- ── Action alerts ──────────────────────────────────────── --}}
        @if($stats['pending_reviews'] > 0 || $stats['contact_new'] > 0)
        <div class="adm-alerts">
            @if($stats['pending_reviews'] > 0)
            <div class="adm-alert adm-alert--warn">
                <span class="adm-alert__dot adm-alert__dot--warn"></span>
                <span class="adm-alert__text">
                    <strong>{{ $stats['pending_reviews'] }}</strong>
                    {{ \Illuminate\Support\Str::plural('review', $stats['pending_reviews']) }} pending moderation
                </span>
                <a href="{{ route('reviews.pending') }}" class="adm-alert__action">Moderate now</a>
            </div>
            @endif
            @if($stats['contact_new'] > 0)
            <div class="adm-alert adm-alert--info">
                <span class="adm-alert__dot adm-alert__dot--info"></span>
                <span class="adm-alert__text">
                    <strong>{{ $stats['contact_new'] }}</strong> new
                    {{ \Illuminate\Support\Str::plural('inquiry', $stats['contact_new']) }} in inbox
                </span>
                <a href="{{ route('admin_contact_inquiries.index') }}" class="adm-alert__action">Open inbox</a>
            </div>
            @endif
        </div>
        @endif

        {{-- ── KPI strip ───────────────────────────────────────────── --}}
        <div class="adm-kpis">
            <div class="adm-kpi">
                <div class="adm-kpi__icon adm-kpi__icon--blue">
                    <i class="fas fa-briefcase"></i>
                </div>
                <div class="adm-kpi__body">
                    <p class="adm-kpi__label">Live brokers</p>
                    <p class="adm-kpi__value">{{ number_format($stats['brokers']) }}</p>
                    <p class="adm-kpi__sub">{{ number_format($stats['scam_brokers']) }} flagged as scam</p>
                </div>
            </div>
            <div class="adm-kpi">
                <div class="adm-kpi__icon adm-kpi__icon--amber">
                    <i class="fas fa-star-half-alt"></i>
                </div>
                <div class="adm-kpi__body">
                    <p class="adm-kpi__label">Reviews</p>
                    <p class="adm-kpi__value">{{ number_format($stats['reviews']) }}</p>
                    @if($stats['pending_reviews'] > 0)
                        <p class="adm-kpi__sub adm-kpi__sub--warn">{{ $stats['pending_reviews'] }} awaiting approval</p>
                    @else
                        <p class="adm-kpi__sub">All approved</p>
                    @endif
                </div>
            </div>
            <div class="adm-kpi">
                <div class="adm-kpi__icon adm-kpi__icon--green">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="adm-kpi__body">
                    <p class="adm-kpi__label">Inquiries</p>
                    <p class="adm-kpi__value">{{ number_format($stats['contact_new']) }}</p>
                    <p class="adm-kpi__sub">Unread messages</p>
                </div>
            </div>
            <div class="adm-kpi">
                <div class="adm-kpi__icon adm-kpi__icon--violet">
                    <i class="fas fa-gift"></i>
                </div>
                <div class="adm-kpi__body">
                    <p class="adm-kpi__label">Promotions</p>
                    <p class="adm-kpi__value">{{ number_format($stats['bonuses']) }}</p>
                    <p class="adm-kpi__sub">Active forex bonuses</p>
                </div>
            </div>
        </div>

        {{-- ── Main grid: panels + actions ────────────────────────── --}}
        <div class="adm-grid">

            {{-- Pending reviews --}}
            <div class="adm-panel">
                <div class="adm-panel__head">
                    <div>
                        <p class="adm-panel__eyebrow">Queue</p>
                        <h2 class="adm-panel__title">Pending reviews</h2>
                    </div>
                    <a href="{{ route('reviews.pending') }}" class="adm-panel__viewall">View all</a>
                </div>
                @if($pendingReviews->isEmpty())
                    <div class="adm-empty">
                        <i class="fas fa-check-circle"></i>
                        <p>No reviews pending — you're all caught up.</p>
                    </div>
                @else
                    <ul class="adm-list">
                        @foreach($pendingReviews as $review)
                        <li class="adm-list__row">
                            <div class="adm-list__avatar adm-list__avatar--amber">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="adm-list__info">
                                <p class="adm-list__name">{{ $review->name }}</p>
                                <p class="adm-list__meta">{{ $review->broker->name ?? '—' }} &middot; {{ $review->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="adm-rating">
                                {{ number_format($review->rating, 1) }}
                                <i class="fas fa-star adm-rating__star"></i>
                            </span>
                        </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            {{-- Recent inquiries --}}
            <div class="adm-panel">
                <div class="adm-panel__head">
                    <div>
                        <p class="adm-panel__eyebrow">Inbox</p>
                        <h2 class="adm-panel__title">Recent inquiries</h2>
                    </div>
                    <a href="{{ route('admin_contact_inquiries.index') }}" class="adm-panel__viewall">View all</a>
                </div>
                @if($recentInquiries->isEmpty())
                    <div class="adm-empty">
                        <i class="fas fa-inbox"></i>
                        <p>No inquiries yet.</p>
                    </div>
                @else
                    <ul class="adm-list">
                        @foreach($recentInquiries as $inq)
                        <li class="adm-list__row">
                            <div class="adm-list__avatar adm-list__avatar--blue">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="adm-list__info">
                                <p class="adm-list__name">{{ $inq->name }}</p>
                                <p class="adm-list__meta">{{ Str::limit($inq->subject, 36) }} &middot; {{ $inq->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="adm-badge adm-badge--{{ $inq->status === 'new' ? 'new' : 'read' }}">{{ $inq->status }}</span>
                        </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        {{-- ── Bottom grid: recent posts + quick actions ───────────── --}}
        <div class="adm-grid adm-grid--3">

            {{-- Recent posts --}}
            <div class="adm-panel adm-panel--span2">
                <div class="adm-panel__head">
                    <div>
                        <p class="adm-panel__eyebrow">Content</p>
                        <h2 class="adm-panel__title">Recent posts</h2>
                    </div>
                    <a href="{{ route('admin_post_show') }}" class="adm-panel__viewall">View all</a>
                </div>
                @if($recentPosts->isEmpty())
                    <div class="adm-empty">
                        <i class="fas fa-file-alt"></i>
                        <p>No posts yet. Write your first article.</p>
                    </div>
                @else
                    <ul class="adm-list">
                        @foreach($recentPosts as $post)
                        <li class="adm-list__row">
                            <div class="adm-list__avatar adm-list__avatar--violet">
                                <i class="fas fa-pen"></i>
                            </div>
                            <div class="adm-list__info">
                                <p class="adm-list__name">{{ Str::limit($post->post_title, 52) }}</p>
                                <p class="adm-list__meta">{{ $post->created_at->format('M j, Y') }}</p>
                            </div>
                            <a href="{{ route('admin_post_edit', $post->id) }}" class="adm-link-btn">Edit</a>
                        </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            {{-- Quick actions --}}
            <div class="adm-panel">
                <div class="adm-panel__head">
                    <div>
                        <p class="adm-panel__eyebrow">Shortcuts</p>
                        <h2 class="adm-panel__title">Quick actions</h2>
                    </div>
                </div>
                <div class="adm-actions">
                    @foreach($quickActions as $action)
                    <a href="{{ route($action['route']) }}" class="adm-action">
                        <span class="adm-action__icon">
                            <i class="fas fa-{{ $action['icon'] }}"></i>
                        </span>
                        <span class="adm-action__label">{{ $action['label'] }}</span>
                    </a>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- ── Site snapshot ────────────────────────────────────────── --}}
        <div class="adm-snapshot">
            <div class="adm-snapshot__head">
                <p class="adm-panel__eyebrow">Site overview</p>
                <h2 class="adm-panel__title">Platform snapshot</h2>
            </div>
            <div class="adm-snapshot__grid">
                @foreach([
                    ['label' => 'Posts',         'value' => $stats['posts'],         'icon' => 'file-alt'],
                    ['label' => 'Categories',    'value' => $stats['categories'],    'icon' => 'folder'],
                    ['label' => 'Prop firms',    'value' => $stats['prop_firms'],    'icon' => 'building'],
                    ['label' => 'FAQs',          'value' => $stats['faqs'],          'icon' => 'question-circle'],
                    ['label' => 'CMS pages',     'value' => $stats['cms_pages'],     'icon' => 'layer-group'],
                    ['label' => 'Subcategories', 'value' => $stats['subcategories'], 'icon' => 'tags'],
                ] as $item)
                <div class="adm-snap">
                    <i class="fas fa-{{ $item['icon'] }} adm-snap__icon"></i>
                    <span class="adm-snap__val">{{ number_format($item['value']) }}</span>
                    <span class="adm-snap__lbl">{{ $item['label'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
@endsection
