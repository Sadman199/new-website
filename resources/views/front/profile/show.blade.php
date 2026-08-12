@extends('front.layout.app')

@section('title', $user->name . ' | My Profile | BrokersCourt')
@section('meta_description', 'Manage your BrokersCourt profile, reviews and account activity.')
@section('robots', 'noindex, nofollow')
@section('canonical', route('user.profile'))

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/user-account.css') }}?v=8">
@endpush

@section('main_content')
@php
    $tabAliases = [
        'overview' => 'overview',
        'safety' => 'safety',
        'settings' => 'settings',
        'reviews' => 'overview',
        'saved' => 'overview',
        'activity' => 'overview',
        'notifications' => 'overview',
        'reports' => 'safety',
        'security' => 'settings',
    ];
    $scrollAliases = [
        'reviews' => 'ua-reviews',
        'saved' => 'ua-saved',
        'activity' => 'ua-activity',
        'notifications' => 'ua-notifications',
        'security' => 'ua-security',
    ];
    $requestedTab = (string) request('tab', 'overview');
    $activeTab = $tabAliases[$requestedTab] ?? 'overview';
    $scrollTo = $scrollAliases[$requestedTab] ?? null;
    $overviewUrl = route('user.profile', ['tab' => 'overview']);
    $safetyUrl = route('user.profile', ['tab' => 'safety']);
    $settingsUrl = route('user.profile', ['tab' => 'settings']);
@endphp
<div class="ua-root" @if($scrollTo) data-ua-scroll="{{ $scrollTo }}" @endif>
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
                <span class="ua-stat__value">{{ $stats['saved_brokers'] }}</span>
                <span class="ua-stat__label">Saved brokers</span>
            </div>
            <div class="ua-stat">
                <span class="ua-stat__value" style="font-size:0.9375rem;">{{ $user->created_at->format('M Y') }}</span>
                <span class="ua-stat__label">Member since</span>
            </div>
        </div>

        <div class="ua-quick-actions" aria-label="Quick actions">
            <a href="{{ route('all_brokers') }}" class="ua-quick-action"><i class="fas fa-star"></i> Write a review</a>
            <a href="{{ route('find_my_broker') }}" class="ua-quick-action"><i class="fas fa-search"></i> Find my broker</a>
            <a href="{{ route('broker.comparison') }}" class="ua-quick-action"><i class="fas fa-columns"></i> Compare brokers</a>
            <a href="{{ $overviewUrl }}#ua-saved" class="ua-quick-action"><i class="fas fa-bookmark"></i> Saved brokers</a>
        </div>

        <div class="ua-tabs-mobile" role="tablist" aria-label="Profile sections">
            <a href="{{ $overviewUrl }}" class="ua-tabs-mobile__btn{{ $activeTab === 'overview' ? ' is-active' : '' }}" role="tab" aria-selected="{{ $activeTab === 'overview' ? 'true' : 'false' }}">Overview</a>
            <a href="{{ $safetyUrl }}" class="ua-tabs-mobile__btn{{ $activeTab === 'safety' ? ' is-active' : '' }}" role="tab" aria-selected="{{ $activeTab === 'safety' ? 'true' : 'false' }}">Safety</a>
            <a href="{{ $settingsUrl }}" class="ua-tabs-mobile__btn{{ $activeTab === 'settings' ? ' is-active' : '' }}" role="tab" aria-selected="{{ $activeTab === 'settings' ? 'true' : 'false' }}">Settings</a>
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
                        <a href="{{ $overviewUrl }}" class="ua-nav__link{{ $activeTab === 'overview' ? ' is-active' : '' }}"@if($activeTab === 'overview') aria-current="page"@endif>
                            <i class="fas fa-th-large"></i> Overview
                            @if($stats['saved_brokers'] > 0)
                                <span class="ua-nav-badge">{{ $stats['saved_brokers'] > 9 ? '9+' : $stats['saved_brokers'] }}</span>
                            @endif
                        </a>
                        <a href="{{ $safetyUrl }}" class="ua-nav__link{{ $activeTab === 'safety' ? ' is-active' : '' }}"@if($activeTab === 'safety') aria-current="page"@endif>
                            <i class="fas fa-shield-alt"></i> Safety reports
                        </a>
                        <a href="{{ $settingsUrl }}" class="ua-nav__link{{ $activeTab === 'settings' ? ' is-active' : '' }}"@if($activeTab === 'settings') aria-current="page"@endif>
                            <i class="fas fa-user-cog"></i> Settings
                        </a>
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

                @if($activeTab === 'overview')
                <div class="ua-panel-stack">
                    <section class="ua-panel" id="ua-reviews">
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
                                        @if($review->status == 1 && $review->broker)
                                            <a href="{{ route('broker_detail', ['slug' => \App\Http\Controllers\Front\BrokerController::reviewSlugFor($review->broker)]) }}" class="ua-link">View live review →</a>
                                        @elseif($review->status == 0 && $review->broker)
                                            <div class="ua-review-actions">
                                                <a href="{{ route('broker_detail', ['slug' => \App\Http\Controllers\Front\BrokerController::reviewSlugFor($review->broker)]) }}#voices" class="ua-btn ua-btn--ghost ua-btn--sm">Edit</a>
                                                <form action="{{ route('user.reviews.destroy', $review) }}" method="POST" class="ua-review-actions__form" onsubmit="return confirm('Delete this pending review?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="ua-btn ua-btn--ghost ua-btn--sm ua-btn--danger">Delete</button>
                                                </form>
                                            </div>
                                        @endif
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
                    </section>

                    <section class="ua-panel" id="ua-saved">
                        <div class="ua-panel__head">
                            <h2 class="ua-panel__title">Saved brokers</h2>
                            <p class="ua-panel__sub">Bookmarks sync across devices when you're signed in.</p>
                        </div>
                        <div class="ua-panel__body ua-panel__body--flush" data-ua-saved-grid>
                            @forelse($savedBrokerCards as $broker)
                                <article class="ua-saved-item" data-broker-id="{{ $broker['id'] }}">
                                    <a href="{{ $broker['review_url'] }}" class="ua-saved-item__logo" aria-hidden="true" tabindex="-1">
                                        @if($broker['logo'])
                                            <img src="{{ $broker['logo'] }}" alt="">
                                        @else
                                            <span>{{ strtoupper(substr($broker['name'], 0, 1)) }}</span>
                                        @endif
                                    </a>
                                    <div class="ua-saved-item__body">
                                        <a href="{{ $broker['review_url'] }}" class="ua-saved-item__name">{{ $broker['name'] }}</a>
                                        @if($broker['rating'] !== null)
                                            <p class="ua-saved-item__meta">★ {{ number_format($broker['rating'], 1) }}/5 · {{ $broker['regulation_summary'] ?? '—' }}</p>
                                        @endif
                                    </div>
                                    <form action="{{ route('user.saved_brokers.destroy', $broker['id']) }}" method="POST" class="ua-saved-item__remove" data-ua-unsave>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="ua-btn ua-btn--ghost ua-btn--sm" title="Remove from saved">
                                            <i class="fas fa-bookmark"></i> Remove
                                        </button>
                                    </form>
                                </article>
                            @empty
                                <div class="ua-empty">
                                    <i class="fas fa-bookmark"></i>
                                    <p>No saved brokers yet.</p>
                                    <a href="{{ route('find_my_broker') }}" class="ua-link">Find my broker →</a>
                                </div>
                            @endforelse
                        </div>
                    </section>

                    <section class="ua-panel" id="ua-activity">
                        <div class="ua-panel__head">
                            <h2 class="ua-panel__title">Recent activity</h2>
                            <p class="ua-panel__sub">Latest account actions and review submissions.</p>
                        </div>
                        <div class="ua-panel__body ua-panel__body--flush">
                            @forelse($activities as $activity)
                                <div class="ua-activity-item">
                                    <span class="ua-activity-dot" aria-hidden="true"></span>
                                    <div>
                                        <p class="ua-activity-label">{{ $activity->label }}</p>
                                        @if($activity->description)<p class="ua-activity-desc">{{ $activity->description }}</p>@endif
                                        <p class="ua-activity-time">{{ $activity->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="ua-empty">
                                    <i class="fas fa-history"></i>
                                    <p>No activity recorded yet.</p>
                                </div>
                            @endforelse
                        </div>
                    </section>

                    <section class="ua-panel" id="ua-notifications">
                        <div class="ua-panel__head ua-panel__head--row">
                            <div>
                                <h2 class="ua-panel__title">Recent notifications</h2>
                                <p class="ua-panel__sub">Use the bell in the navbar for your live inbox.</p>
                            </div>
                            @if($unreadNotifications > 0)
                                <form action="{{ route('user.notifications.read_all') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="ua-btn ua-btn--ghost ua-btn--sm">Mark all read</button>
                                </form>
                            @endif
                        </div>
                        <div class="ua-panel__body ua-panel__body--flush">
                            @forelse($notifications as $notification)
                                <article class="ua-notification-item{{ $notification->isUnread() ? ' is-unread' : '' }}">
                                    <div class="ua-notification-icon" aria-hidden="true">
                                        @if($notification->type === 'review_approved')
                                            <i class="fas fa-check-circle"></i>
                                        @elseif($notification->type === 'review_declined')
                                            <i class="fas fa-times-circle"></i>
                                        @elseif($notification->type === 'account_verified')
                                            <i class="fas fa-certificate"></i>
                                        @elseif(str_starts_with($notification->type, 'report_'))
                                            <i class="fas fa-flag"></i>
                                        @else
                                            <i class="fas fa-clock"></i>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="ua-notification-head">
                                            <h3 class="ua-notification-title">{{ $notification->title }}</h3>
                                            @unless($notification->isUnread())
                                                <span class="ua-notification-read">Read</span>
                                            @endunless
                                        </div>
                                        <p class="ua-notification-message">{{ $notification->message }}</p>
                                        <p class="ua-notification-time">{{ $notification->created_at->diffForHumans() }}</p>
                                        @if($notification->url)
                                            <a href="{{ $notification->url }}" class="ua-link">View details →</a>
                                        @endif
                                    </div>
                                    @if($notification->isUnread())
                                        <form action="{{ route('user.notifications.read', $notification) }}" method="POST" class="ua-notification-mark">
                                            @csrf
                                            <button type="submit" class="ua-btn ua-btn--ghost ua-btn--sm" title="Mark as read" aria-label="Mark as read">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                </article>
                            @empty
                                <div class="ua-empty">
                                    <i class="fas fa-bell-slash"></i>
                                    <p>No notifications yet.</p>
                                </div>
                            @endforelse
                        </div>
                    </section>
                </div>
                @elseif($activeTab === 'safety')
                <div class="ua-panel">
                    <div class="ua-panel__head">
                        <h2 class="ua-panel__title">Scam checker reports</h2>
                        <p class="ua-panel__sub">Safety reports you've submitted through the scam checker.</p>
                    </div>
                    <div class="ua-panel__body ua-panel__body--flush">
                        @forelse($brokerReports as $report)
                            <article class="ua-report-item">
                                <div class="ua-report-item__head">
                                    <h3 class="ua-report-item__title">{{ $report->broker_name ?? $report->broker?->name ?? 'Broker report' }}</h3>
                                    <span class="ua-status ua-status--{{ $report->status === 'pending' ? 'pending' : ($report->status === 'dismissed' ? 'declined' : 'approved') }}">{{ $report->statusLabel() }}</span>
                                </div>
                                <p class="ua-report-item__meta">{{ $report->issueLabel() }} · {{ $report->created_at->format('M d, Y') }}</p>
                                <p class="ua-report-item__body">{{ Str::limit($report->message, 220) }}</p>
                                @if($report->broker)
                                    <a href="{{ route('broker.scam_checker.show', ['slug' => $report->broker->listingSlug()]) }}" class="ua-link">View scam checker page →</a>
                                @endif
                            </article>
                        @empty
                            <div class="ua-empty">
                                <i class="fas fa-shield-alt"></i>
                                <p>No reports submitted yet.</p>
                                <a href="{{ route('broker.scam_checker') }}" class="ua-link">Run the scam checker →</a>
                            </div>
                        @endforelse
                    </div>
                </div>
                @else
                <div class="ua-panel-stack">
                    <section class="ua-panel">
                        <div class="ua-panel__head">
                            <h2 class="ua-panel__title">Preferences</h2>
                            <p class="ua-panel__sub">Personalize your BrokersCourt experience.</p>
                        </div>
                        <div class="ua-panel__body">
                            <form action="{{ route('user.profile.preferences') }}" method="POST" class="ua-form">
                                @csrf
                                @method('PUT')
                                <div class="ua-field">
                                    <label for="preferred_country_slug">Default country for broker listings</label>
                                    <select name="preferred_country_slug" id="preferred_country_slug" class="ua-input ua-select">
                                        <option value="">Global (all countries)</option>
                                        @foreach($countryOptions as $country)
                                            <option value="{{ $country['slug'] }}" @selected($preferredCountrySlug === $country['slug'])>
                                                {{ $country['flag'] }} {{ $country['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="ua-hint">Applied when you sign in and used for country-specific broker recommendations.</p>
                                </div>
                                <button type="submit" class="ua-btn ua-btn--primary">
                                    <i class="fas fa-save"></i> Save preferences
                                </button>
                            </form>

                            <div class="ua-settings-divider"></div>

                            <p style="margin:0 0 0.75rem;color:var(--ua-muted);font-size:0.9375rem;">Update your display name, bio, and avatar from the profile editor.</p>
                            <a href="{{ route('user.profile.edit') }}" class="ua-btn ua-btn--ghost">
                                <i class="fas fa-user-edit"></i> Edit profile
                            </a>
                        </div>
                    </section>

                    <section class="ua-panel" id="ua-security">
                        <div class="ua-panel__head">
                            <h2 class="ua-panel__title">Security</h2>
                            @if($user->hasPassword())
                                <p class="ua-panel__sub">Update your password to keep your account secure.</p>
                            @else
                                <p class="ua-panel__sub">You signed in with Google. Set a password if you also want email sign-in.</p>
                            @endif
                        </div>
                        <div class="ua-panel__body">
                            @if($user->hasPassword())
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
                            @else
                            <form action="{{ route('user.profile.set_password') }}" method="POST" class="ua-form">
                                @csrf
                                @method('PUT')
                                <div class="ua-field">
                                    <label for="password">New password</label>
                                    <input type="password" name="password" id="password" required autocomplete="new-password"
                                        class="ua-input @error('password') is-error @enderror">
                                    @error('password')<p class="ua-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                                </div>
                                <div class="ua-field">
                                    <label for="password_confirmation">Confirm password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password" class="ua-input">
                                </div>
                                <button type="submit" class="ua-btn ua-btn--primary">
                                    <i class="fas fa-key"></i> Set password
                                </button>
                            </form>
                            @endif

                            <div class="ua-security-note">
                                <p><strong>Sign-in methods:</strong>
                                    @if($user->google_id) Google @endif
                                    @if($user->google_id && $user->hasPassword()) · @endif
                                    @if($user->hasPassword()) Email &amp; password @endif
                                </p>
                                @if($user->last_login_at)
                                    <p>Last login: {{ $user->last_login_at->format('M j, Y g:i A') }}</p>
                                @endif
                            </div>
                        </div>
                    </section>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/user-profile.js') }}?v=4" defer></script>
@endpush
