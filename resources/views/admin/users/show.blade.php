@extends('admin.layout.app')
@include('admin.partials._ab_assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')
@section('heading', 'User Details')

@section('main_content')
<div class="ab-page ab-page--hub">
    <div class="ab-wrap">
        <header class="ab-header">
            <div class="ab-identity">
                <span class="ab-logo ab-logo--lg">
                    <img src="{{ $user->avatar_url }}" alt="">
                </span>
                <div>
                    <p class="ab-header__eyebrow">User overview</p>
                    <h1 class="ab-header__title">{{ $user->name }}</h1>
                    <p class="ab-header__sub">{{ $user->email }}</p>
                    <div class="ab-pills">
                        @if($user->is_verified)
                            <span class="ab-pill ab-pill--ok">Verified</span>
                        @else
                            <span class="ab-pill ab-pill--warn">Pending</span>
                        @endif
                        @if($user->status === 'banned')
                            <span class="ab-pill ab-pill--danger">Suspended</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="ab-header__actions">
                @if($user->is_verified)
                    <form action="{{ route('admin_users_unverify', $user->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <button class="ab-btn ab-btn--ghost" type="submit">Unverify</button>
                    </form>
                @else
                    <form action="{{ route('admin_users_verify', $user->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <button class="ab-btn ab-btn--primary" type="submit">Verify user</button>
                    </form>
                @endif
                <form action="{{ route('admin_users_toggle_status', $user->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <button class="ab-btn ab-btn--ghost" type="submit">{{ $user->status === 'banned' ? 'Reactivate' : 'Suspend' }}</button>
                </form>
                <a href="{{ route('admin_users_index') }}" class="ab-btn ab-btn--ghost">All users</a>
            </div>
        </header>

        <div class="ab-user-layout">
            <div>
                <dl class="ab-dl">
                    <div>
                        <dt>Country</dt>
                        <dd>{{ $user->country ?? 'Not set' }}</dd>
                    </div>
                    <div>
                        <dt>Reviews</dt>
                        <dd>{{ number_format($user->reviews_count) }}</dd>
                    </div>
                    <div>
                        <dt>Joined</dt>
                        <dd>{{ $user->created_at?->format('M j, Y') }}</dd>
                    </div>
                    <div>
                        <dt>Last login</dt>
                        <dd>{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</dd>
                    </div>
                    @if($user->last_login_ip)
                        <div>
                            <dt>Last IP</dt>
                            <dd>{{ $user->last_login_ip }}</dd>
                        </div>
                    @endif
                </dl>
                @if($user->bio)
                    <section class="ab-section">
                        <div class="ab-section__head"><h2>Bio</h2></div>
                        <div class="ab-section__body"><p class="mb-0">{{ $user->bio }}</p></div>
                    </section>
                @endif
                <div class="ab-save">
                    <form action="{{ route('admin_users_delete', $user->id) }}" method="POST" data-ab-delete data-ab-name="{{ $user->name }}" data-ab-warn="This cannot be undone." data-ab-confirm="Delete user">
                        @csrf
                        @method('DELETE')
                        <button class="ab-btn ab-btn--danger" type="submit">Delete user</button>
                    </form>
                </div>
            </div>

            <div>
                <section class="ab-section">
                    <div class="ab-section__head"><h2>Reviews ({{ $reviews->count() }})</h2></div>
                    <div class="ab-section__body">
                        @forelse($reviews as $review)
                            <div class="mb-3 pb-3" style="border-bottom:1px solid var(--ab-border, #e8e3da)">
                                <div class="d-flex justify-content-between gap-2">
                                    <strong>{{ $review->broker->name ?? 'Broker' }}</strong>
                                    @if($review->status == 1)
                                        <span class="ab-pill ab-pill--ok">Approved</span>
                                    @elseif($review->status == 0)
                                        <span class="ab-pill ab-pill--warn">Pending</span>
                                    @else
                                        <span class="ab-pill ab-pill--danger">Declined</span>
                                    @endif
                                </div>
                                <p class="ab-note mb-1">{{ $review->created_at?->format('M j, Y') }} · {{ $review->rating }}/5</p>
                                <p class="mb-0">{{ $review->description }}</p>
                            </div>
                        @empty
                            <p class="ab-note mb-0">No reviews submitted.</p>
                        @endforelse
                    </div>
                </section>

                <section class="ab-section">
                    <div class="ab-section__head"><h2>Activity log</h2></div>
                    <div class="ab-section__body">
                        @forelse($activities as $activity)
                            <div class="mb-2">
                                <p class="ab-broker__name mb-0">{{ $activity->label }}</p>
                                @if($activity->description)<p class="ab-note mb-0">{{ $activity->description }}</p>@endif
                                <p class="ab-note mb-0">{{ $activity->created_at?->format('M j, Y H:i') }}@if($activity->ip_address) · {{ $activity->ip_address }}@endif</p>
                            </div>
                        @empty
                            <p class="ab-note mb-0">No activity recorded.</p>
                        @endforelse
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection
