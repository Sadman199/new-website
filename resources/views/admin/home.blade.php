@extends('admin.layout.app')
@include('admin.partials._ab_assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')
@section('heading', 'Dashboard')

@php
    $firstName = explode(' ', $adminName)[0];
    $hour = now()->hour;
    $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
    $today = now()->format('l, F j');
@endphp

@section('main_content')
<div class="ab-page ab-page--hub ab-page--dash">
    <div class="ab-wrap">
        <header class="ab-header">
            <div>
                <p class="ab-header__eyebrow">{{ $today }}</p>
                <h1 class="ab-header__title">{{ $greeting }}, {{ $firstName }}</h1>
                <p class="ab-header__sub">What needs attention across brokers, content, and community.</p>
            </div>
            <div class="ab-header__actions">
                <a href="{{ route('home') }}" target="_blank" rel="noopener" class="ab-btn ab-btn--ghost">
                    <i class="fas fa-external-link-alt" aria-hidden="true"></i>
                    View site
                </a>
                <a href="{{ route('admin_broker_create') }}" class="ab-btn ab-btn--primary">
                    <i class="fas fa-plus" aria-hidden="true"></i>
                    New broker
                </a>
            </div>
        </header>

        @if($stats['pending_reviews'] > 0 || $stats['contact_new'] > 0)
            <div class="ab-alerts">
                @if($stats['pending_reviews'] > 0)
                    <div class="ab-alert ab-alert--warn">
                        <strong>{{ number_format($stats['pending_reviews']) }}</strong>
                        {{ \Illuminate\Support\Str::plural('review', $stats['pending_reviews']) }} waiting for moderation
                        <a href="{{ route('reviews.pending') }}">Moderate now</a>
                    </div>
                @endif
                @if($stats['contact_new'] > 0)
                    <div class="ab-alert ab-alert--info">
                        <strong>{{ number_format($stats['contact_new']) }}</strong>
                        new {{ \Illuminate\Support\Str::plural('inquiry', $stats['contact_new']) }} in the inbox
                        <a href="{{ route('admin_contact_inquiries.index') }}">Open inbox</a>
                    </div>
                @endif
            </div>
        @endif

        <div class="ab-kpis">
            <a href="{{ route('admin_broker_show') }}" class="ab-kpi ab-kpi--link">
                <span class="ab-kpi__icon"><i class="fas fa-briefcase" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Live brokers</p>
                    <p class="ab-kpi__value">{{ number_format($stats['brokers']) }}</p>
                    <p class="ab-kpi__sub">{{ number_format($stats['scam_brokers']) }} flagged as scam</p>
                </div>
            </a>
            <a href="{{ route('reviews.pending') }}" class="ab-kpi ab-kpi--link">
                <span class="ab-kpi__icon"><i class="fas fa-star" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Reviews</p>
                    <p class="ab-kpi__value">{{ number_format($stats['reviews']) }}</p>
                    @if($stats['pending_reviews'] > 0)
                        <p class="ab-kpi__sub ab-kpi__sub--warn">{{ number_format($stats['pending_reviews']) }} awaiting approval</p>
                    @else
                        <p class="ab-kpi__sub">Queue is clear</p>
                    @endif
                </div>
            </a>
            <a href="{{ route('admin_contact_inquiries.index') }}" class="ab-kpi ab-kpi--link">
                <span class="ab-kpi__icon"><i class="fas fa-envelope" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Inquiries</p>
                    <p class="ab-kpi__value">{{ number_format($stats['contact_new']) }}</p>
                    <p class="ab-kpi__sub">Unread messages</p>
                </div>
            </a>
            <a href="{{ route('admin_forex_bonus_show') }}" class="ab-kpi ab-kpi--link">
                <span class="ab-kpi__icon"><i class="fas fa-gift" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Promotions</p>
                    <p class="ab-kpi__value">{{ number_format($stats['bonuses']) }}</p>
                    <p class="ab-kpi__sub">Forex bonuses</p>
                </div>
            </a>
        </div>

        <div class="ab-dash-grid">
            <section class="ab-section">
                <div class="ab-section__head">
                    <h2>Pending reviews</h2>
                    <p>Approve or decline user submissions.</p>
                </div>
                @if($pendingReviews->isEmpty())
                    <div class="ab-empty">
                        <h3>Queue is clear</h3>
                        <p>No reviews are waiting for moderation.</p>
                    </div>
                @else
                    <ul class="ab-feed">
                        @foreach($pendingReviews as $review)
                            <li>
                                <a class="ab-feed__row" href="{{ route('reviews.pending') }}">
                                    <span class="ab-feed__icon"><i class="fas fa-star" aria-hidden="true"></i></span>
                                    <div class="ab-feed__info">
                                        <p class="ab-feed__name">{{ $review->name }}</p>
                                        <p class="ab-feed__meta">{{ $review->broker?->name ?? '—' }} · {{ $review->created_at?->diffForHumans() }}</p>
                                    </div>
                                    <span class="ab-pill ab-pill--warn">{{ number_format((float) $review->rating, 1) }}/5</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="ab-foot">
                        <a href="{{ route('reviews.pending') }}" class="ab-btn ab-btn--ghost ab-btn--sm">View all</a>
                    </div>
                @endif
            </section>

            <section class="ab-section">
                <div class="ab-section__head">
                    <h2>Recent inquiries</h2>
                    <p>New messages from the contact form.</p>
                </div>
                @if($recentInquiries->isEmpty())
                    <div class="ab-empty">
                        <h3>Inbox is empty</h3>
                        <p>No contact inquiries yet.</p>
                    </div>
                @else
                    <ul class="ab-feed">
                        @foreach($recentInquiries as $inq)
                            <li>
                                <a class="ab-feed__row" href="{{ route('admin_contact_inquiries.show', $inq) }}">
                                    <span class="ab-feed__icon"><i class="fas fa-user" aria-hidden="true"></i></span>
                                    <div class="ab-feed__info">
                                        <p class="ab-feed__name">{{ $inq->name }}</p>
                                        <p class="ab-feed__meta">{{ \Illuminate\Support\Str::limit($inq->subject, 40) }} · {{ $inq->created_at?->diffForHumans() }}</p>
                                    </div>
                                    <span class="ab-pill {{ $inq->status === 'new' ? 'ab-pill--warn' : '' }}">{{ $inq->status }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="ab-foot">
                        <a href="{{ route('admin_contact_inquiries.index') }}" class="ab-btn ab-btn--ghost ab-btn--sm">View all</a>
                    </div>
                @endif
            </section>
        </div>

        <div class="ab-dash-grid ab-dash-grid--wide">
            <section class="ab-section">
                <div class="ab-section__head">
                    <h2>Recent posts</h2>
                    <p>Latest blog articles.</p>
                </div>
                @if($recentPosts->isEmpty())
                    <div class="ab-empty">
                        <h3>No posts yet</h3>
                        <p>Write the first article from Blog.</p>
                        <a href="{{ route('admin_post_create') }}" class="ab-btn ab-btn--primary">Write Post</a>
                    </div>
                @else
                    <ul class="ab-feed">
                        @foreach($recentPosts as $post)
                            <li>
                                <a class="ab-feed__row" href="{{ route('admin_post_edit', $post->id) }}">
                                    <span class="ab-feed__icon"><i class="fas fa-pen" aria-hidden="true"></i></span>
                                    <div class="ab-feed__info">
                                        <p class="ab-feed__name">{{ \Illuminate\Support\Str::limit($post->post_title, 64) }}</p>
                                        <p class="ab-feed__meta">{{ $post->created_at?->format('M j, Y') }}</p>
                                    </div>
                                    <span class="ab-btn ab-btn--ghost ab-btn--sm">Edit</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="ab-foot">
                        <a href="{{ route('admin_post_show') }}" class="ab-btn ab-btn--ghost ab-btn--sm">All blogs</a>
                    </div>
                @endif
            </section>

            <section class="ab-section">
                <div class="ab-section__head">
                    <h2>Quick actions</h2>
                    <p>Common tasks.</p>
                </div>
                <ul class="ab-feed">
                    @foreach($quickActions as $action)
                        <li>
                            <a class="ab-feed__row" href="{{ route($action['route']) }}">
                                <span class="ab-feed__icon"><i class="fas fa-{{ $action['icon'] }}" aria-hidden="true"></i></span>
                                <div class="ab-feed__info">
                                    <p class="ab-feed__name">{{ $action['label'] }}</p>
                                    <p class="ab-feed__meta">{{ $action['description'] }}</p>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </section>
        </div>

        <section class="ab-section">
            <div class="ab-section__head">
                <h2>Platform snapshot</h2>
                <p>Counts across the rest of the site.</p>
            </div>
            <div class="ab-section__body">
                <div class="ab-snap">
                    <a href="{{ route('admin_post_show') }}">
                        <strong>{{ number_format($stats['posts']) }}</strong>
                        <span>Posts</span>
                    </a>
                    <a href="{{ route('admin_category_show') }}">
                        <strong>{{ number_format($stats['categories']) }}</strong>
                        <span>Categories</span>
                    </a>
                    <a href="{{ route('admin_prop_firms_dashboard') }}">
                        <strong>{{ number_format($stats['prop_firms']) }}</strong>
                        <span>Prop firms</span>
                    </a>
                    <a href="{{ route('admin_faq_show') }}">
                        <strong>{{ number_format($stats['faqs']) }}</strong>
                        <span>FAQs</span>
                    </a>
                    <a href="{{ route('admin_cms_pages_index') }}">
                        <strong>{{ number_format($stats['cms_pages']) }}</strong>
                        <span>CMS pages</span>
                    </a>
                    <a href="{{ route('admin_sub_category_show') }}">
                        <strong>{{ number_format($stats['subcategories']) }}</strong>
                        <span>Subcategories</span>
                    </a>
                    <div>
                        <strong>{{ number_format($stats['subscribers']) }}</strong>
                        <span>Subscribers</span>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
