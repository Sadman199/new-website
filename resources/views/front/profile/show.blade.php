@extends('front.layout.app')

@section('title', $user->name . ' | My Profile | BrokersCourt')
@section('meta_description', 'Manage your BrokersCourt profile, reviews and account activity.')

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/user-account.css') }}?v=1">
@endpush

@section('main_content')
<div class="ua-root" x-data="{ tab: 'reviews' }">
    <div class="ua-wrap ua-wrap--profile">

        @include('front.account._alerts')

        <div class="ua-stats" aria-label="Account statistics">
            <div class="ua-stat">
                <span class="ua-stat__value">{{ $stats['reviews_total'] }}</span>
                <span class="ua-stat__label">Total reviews</span>
            </div>
            <div class="ua-stat">
                <span class="ua-stat__value ua-stat__value--success">{{ $stats['reviews_approved'] }}</span>
                <span class="ua-stat__label">Approved</span>
            </div>
            <div class="ua-stat">
                <span class="ua-stat__value ua-stat__value--warning">{{ $stats['reviews_pending'] }}</span>
                <span class="ua-stat__label">Pending</span>
            </div>
            <div class="ua-stat">
                <span class="ua-stat__value" style="font-size:0.9375rem;">{{ $user->created_at->format('M Y') }}</span>
                <span class="ua-stat__label">Member since</span>
            </div>
        </div>

        <div class="ua-tabs-mobile" role="tablist" aria-label="Profile sections">
            <button type="button" class="ua-tabs-mobile__btn" :class="{ 'is-active': tab === 'reviews' }" @click="tab = 'reviews'">Reviews</button>
            <button type="button" class="ua-tabs-mobile__btn" :class="{ 'is-active': tab === 'activity' }" @click="tab = 'activity'">Activity</button>
            <button type="button" class="ua-tabs-mobile__btn" :class="{ 'is-active': tab === 'security' }" @click="tab = 'security'">Security</button>
            <button type="button" class="ua-tabs-mobile__btn" :class="{ 'is-active': tab === 'settings' }" @click="tab = 'settings'">Settings</button>
        </div>

        <div class="ua-profile-grid">
            <aside class="ua-profile-sidebar">
                <div class="ua-profile-card">
                    <div class="ua-profile-hero"></div>
                    <div class="ua-profile-user">
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="ua-profile-avatar">
                        <h1 class="ua-profile-name">{{ $user->name }}</h1>
                        <p class="ua-profile-email">{{ $user->email }}</p>
                        @if($user->country)
                            <p class="ua-profile-email"><i class="fas fa-map-marker-alt"></i> {{ $user->country }}</p>
                        @endif
                        @if($user->is_verified)
                            <span class="ua-badge ua-badge--verified"><i class="fas fa-check-circle"></i> Verified</span>
                        @else
                            <span class="ua-badge ua-badge--pending"><i class="fas fa-clock"></i> Pending verification</span>
                        @endif
                        @if($user->bio)
                            <p class="ua-bio">{{ $user->bio }}</p>
                        @endif
                    </div>

                    <nav class="ua-nav ua-nav--desktop" aria-label="Profile navigation">
                        <button type="button" class="ua-nav__link" :class="{ 'is-active': tab === 'reviews' }" @click="tab = 'reviews'">
                            <i class="fas fa-star"></i> My reviews
                        </button>
                        <button type="button" class="ua-nav__link" :class="{ 'is-active': tab === 'activity' }" @click="tab = 'activity'">
                            <i class="fas fa-history"></i> Activity
                        </button>
                        <button type="button" class="ua-nav__link" :class="{ 'is-active': tab === 'security' }" @click="tab = 'security'">
                            <i class="fas fa-lock"></i> Security
                        </button>
                        <button type="button" class="ua-nav__link" :class="{ 'is-active': tab === 'settings' }" @click="tab = 'settings'">
                            <i class="fas fa-user-cog"></i> Account settings
                        </button>
                    </nav>

                    <div class="ua-sidebar-actions">
                        <a href="{{ route('user.profile.edit') }}" class="ua-btn ua-btn--ghost"><i class="fas fa-pen"></i> Edit profile</a>
                        <form action="{{ route('user.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="ua-btn ua-btn--danger"><i class="fas fa-sign-out-alt"></i> Log out</button>
                        </form>
                    </div>
                </div>
            </aside>

            <div class="ua-profile-main">
                <div class="ua-toolbar">
                    <a href="{{ route('user.profile.edit') }}" class="ua-btn ua-btn--ghost"><i class="fas fa-pen"></i> Edit profile</a>
                    <form action="{{ route('user.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="ua-btn ua-btn--danger"><i class="fas fa-sign-out-alt"></i> Log out</button>
                    </form>
                </div>

                {{-- Reviews --}}
                <div class="ua-panel" x-show="tab === 'reviews'" x-cloak>
                    <div class="ua-panel__head">
                        <h2 class="ua-panel__title">My reviews</h2>
                        <p class="ua-panel__sub">Broker reviews you've submitted on BrokersCourt.</p>
                    </div>
                    <div class="ua-panel__body ua-panel__body--flush">
                        @forelse($reviews as $review)
                            <article class="ua-review-item">
                                <div class="ua-review-icon"><i class="fas fa-building"></i></div>
                                <div class="flex-1 min-w-0">
                                    <div class="ua-review-head">
                                        <h3 class="ua-review-title">{{ $review->broker->name ?? 'Broker' }}</h3>
                                        @if($review->status == 1)
                                            <span class="ua-status ua-status--approved">Approved</span>
                                        @elseif($review->status == 0)
                                            <span class="ua-status ua-status--pending">Pending</span>
                                        @else
                                            <span class="ua-status ua-status--declined">Declined</span>
                                        @endif
                                    </div>
                                    <p class="ua-review-meta">
                                        <span class="ua-stars">@for($i = 1; $i <= 5; $i++)<i class="fas fa-star{{ $i <= $review->rating ? '' : ' is-dim' }}"></i>@endfor</span>
                                        {{ $review->created_at->format('M d, Y') }}
                                    </p>
                                    <p class="ua-review-body">{{ $review->description }}</p>
                                </div>
                            </article>
                        @empty
                            <div class="ua-empty">
                                <i class="fas fa-comment-slash"></i>
                                <p>You haven't written any reviews yet.</p>
                                <a href="{{ route('all_brokers') }}" class="ua-link">Browse brokers to review →</a>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Activity --}}
                <div class="ua-panel" x-show="tab === 'activity'" x-cloak>
                    <div class="ua-panel__head">
                        <h2 class="ua-panel__title">Recent activity</h2>
                        <p class="ua-panel__sub">Your latest account actions and review submissions.</p>
                    </div>
                    <div class="ua-panel__body ua-panel__body--flush">
                        @forelse($activities as $activity)
                            <div class="ua-activity-item">
                                <span class="ua-activity-dot" aria-hidden="true"></span>
                                <div>
                                    <p class="ua-activity-label">{{ $activity->label }}</p>
                                    @if($activity->description)<p class="ua-activity-desc">{{ $activity->description }}</p>@endif
                                    <p class="ua-activity-time">
                                        {{ $activity->created_at->diffForHumans() }}
                                        @if($activity->ip_address)<span> · {{ $activity->ip_address }}</span>@endif
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="ua-empty">
                                <i class="fas fa-history"></i>
                                <p>No activity recorded yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Security --}}
                <div class="ua-panel" x-show="tab === 'security'" x-cloak>
                    <div class="ua-panel__head">
                        <h2 class="ua-panel__title">Security</h2>
                        <p class="ua-panel__sub">Update your password to keep your account secure.</p>
                    </div>
                    <div class="ua-panel__body">
                        <form action="{{ route('user.profile.password') }}" method="POST" class="ua-form">
                            @csrf
                            @method('PUT')
                            <div class="ua-field">
                                <label for="current_password">Current password</label>
                                <input type="password" name="current_password" id="current_password" required autocomplete="current-password"
                                    class="ua-input @error('current_password') is-error @enderror">
                                @error('current_password')<p class="ua-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                            </div>
                            <div class="ua-field">
                                <label for="password">New password</label>
                                <input type="password" name="password" id="password" required autocomplete="new-password"
                                    class="ua-input @error('password') is-error @enderror">
                                @error('password')<p class="ua-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                            </div>
                            <div class="ua-field">
                                <label for="password_confirmation">Confirm new password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password" class="ua-input">
                            </div>
                            <button type="submit" class="ua-btn ua-btn--primary">
                                <i class="fas fa-key"></i> Update password
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Settings shortcut --}}
                <div class="ua-panel" x-show="tab === 'settings'" x-cloak>
                    <div class="ua-panel__head">
                        <h2 class="ua-panel__title">Account settings</h2>
                        <p class="ua-panel__sub">Manage your profile photo, bio, and personal details.</p>
                    </div>
                    <div class="ua-panel__body">
                        <p style="margin:0 0 1rem;color:var(--ua-muted);font-size:0.9375rem;">Update your display name, country, bio, and avatar from the profile editor.</p>
                        <a href="{{ route('user.profile.edit') }}" class="ua-btn ua-btn--primary">
                            <i class="fas fa-user-edit"></i> Edit profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
