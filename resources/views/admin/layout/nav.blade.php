@php
    $admin = Auth::guard('admin')->user();
    $adminInitials = collect(explode(' ', $admin->name ?? 'Admin'))->map(fn ($p) => strtoupper(substr($p, 0, 1)))->take(2)->join('');
    $notifyCount = $adminNotifyCount ?? 0;
    $notifications = $adminNotifications ?? [];
@endphp

<div class="navbar-bg adm-topbar-bg" aria-hidden="true"></div>

<nav class="navbar main-navbar adm-topbar">
    <div class="tw-flex tw-items-center tw-gap-3 lg:tw-gap-4 tw-w-full tw-h-[70px] tw-px-3 lg:tw-px-5">

        {{-- Sidebar toggle --}}
        <button type="button" data-toggle="sidebar" class="adm-icon-btn" aria-label="Toggle sidebar">
            <i class="fas fa-bars"></i>
        </button>

        {{-- Search --}}
        <div class="tw-relative tw-flex-1 tw-max-w-xl tw-min-w-0 tw-hidden md:tw-block" id="admSearchWrap">
            <i class="fas fa-search tw-absolute tw-left-4 tw-top-1/2 -tw-translate-y-1/2 tw-text-slate-400 tw-text-sm tw-pointer-events-none"></i>
            <input type="search"
                   id="admTopbarSearch"
                   class="adm-search-input"
                   placeholder="Search pages, brokers…"
                   autocomplete="off"
                   aria-label="Search admin">
            <div id="admTopbarSearchResults" class="adm-dropdown adm-dropdown--search adm-dropdown--closed"></div>
        </div>

        <button type="button" class="adm-icon-btn md:tw-hidden" id="admMobileSearchBtn" aria-label="Search">
            <i class="fas fa-search"></i>
        </button>

        <div class="tw-flex tw-items-center tw-gap-2 tw-ml-auto">

            <a href="{{ route('home') }}" target="_blank" rel="noopener"
               class="tw-hidden lg:tw-inline-flex tw-items-center tw-gap-2 tw-h-9 tw-px-3.5 tw-rounded-full tw-text-xs tw-font-semibold tw-text-slate-200 tw-border tw-border-white/10 tw-bg-white/5 hover:tw-bg-white/10 tw-transition-all tw-duration-200">
                <i class="fas fa-external-link-alt tw-text-[11px]"></i>
                View site
            </a>

            {{-- Notifications --}}
            <div class="tw-relative" id="admNotifyWrap">
                <button type="button" id="admNotifyBtn" class="adm-icon-btn" aria-label="Notifications" aria-expanded="false">
                    <i class="far fa-bell"></i>
                    @if($notifyCount > 0)
                        <span class="adm-badge">{{ $notifyCount > 9 ? '9+' : $notifyCount }}</span>
                    @endif
                </button>
                <div id="admNotifyPanel" class="adm-dropdown adm-dropdown--notify adm-dropdown--closed">
                    <div class="tw-flex tw-items-center tw-justify-between tw-px-4 tw-py-3 tw-border-b tw-border-slate-100">
                        <strong class="tw-text-sm tw-font-bold tw-text-slate-900">Notifications</strong>
                        @if($notifyCount > 0)
                            <span class="tw-text-xs tw-font-semibold tw-text-sky-600">{{ $notifyCount }} new</span>
                        @endif
                    </div>
                    <div class="tw-max-h-80 tw-overflow-y-auto">
                        @forelse($notifications as $note)
                            <a href="{{ $note['url'] }}" class="adm-notify-item">
                                <span class="tw-block tw-text-sm tw-font-semibold tw-text-slate-900">{{ $note['title'] }}</span>
                                <span class="tw-block tw-text-xs tw-text-slate-500 tw-mt-0.5">{{ $note['message'] }}</span>
                                <span class="tw-block tw-text-[11px] tw-text-slate-400 tw-mt-1">{{ $note['time'] }}</span>
                            </a>
                        @empty
                            <div class="tw-py-10 tw-text-center tw-text-sm tw-text-slate-400">You're all caught up ✓</div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Profile --}}
            <div class="tw-relative" id="admProfileWrap">
                <button type="button" id="admProfileBtn" class="adm-profile-btn" aria-expanded="false">
                    @if(!empty($admin->photo))
                        <img src="{{ asset('uploads/' . ltrim($admin->photo, '/')) }}" alt="" class="tw-w-8 tw-h-8 tw-rounded-full tw-object-cover tw-ring-2 tw-ring-sky-500/30">
                    @else
                        <span class="adm-profile-fallback">{{ $adminInitials }}</span>
                    @endif
                    <span class="tw-hidden lg:tw-flex tw-flex-col tw-items-start tw-leading-tight tw-min-w-0">
                        <span class="tw-text-sm tw-font-semibold tw-text-white tw-truncate tw-max-w-[110px]">{{ $admin->name }}</span>
                        <span class="tw-text-[11px] tw-text-slate-400">Administrator</span>
                    </span>
                    <i class="fas fa-chevron-down tw-hidden lg:tw-block tw-text-[10px] tw-text-slate-400"></i>
                </button>
                <div id="admProfilePanel" class="adm-dropdown adm-dropdown--profile adm-dropdown--closed">
                    <div class="tw-px-4 tw-py-3 tw-border-b tw-border-slate-100">
                        <strong class="tw-block tw-text-sm tw-font-bold tw-text-slate-900 tw-truncate">{{ $admin->name }}</strong>
                        <span class="tw-block tw-text-xs tw-text-slate-400 tw-truncate">{{ $admin->email ?? 'Admin account' }}</span>
                    </div>
                    <a href="{{ route('admin_home') }}" class="adm-menu-item"><i class="fas fa-home"></i> Dashboard</a>
                    <a href="{{ route('admin_profile') }}" class="adm-menu-item"><i class="far fa-user"></i> Edit profile</a>
                    <a href="{{ route('admin_setting') }}" class="adm-menu-item"><i class="fas fa-cog"></i> Settings</a>
                    <div class="tw-border-t tw-border-slate-100 tw-mt-1 tw-pt-1 tw-px-1">
                        <form action="{{ route('admin_logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="adm-menu-item adm-menu-item--danger tw-w-full">
                                <i class="fas fa-sign-out-alt"></i> Log out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

{{-- Mobile search bar --}}
<div id="admMobileSearchBar" class="adm-mobile-search adm-mobile-search--closed md:tw-hidden">
    <div class="tw-relative tw-px-3 tw-py-2">
        <i class="fas fa-search tw-absolute tw-left-6 tw-top-1/2 -tw-translate-y-1/2 tw-text-slate-400 tw-text-sm"></i>
        <input type="search" id="admTopbarSearchMobile" class="adm-search-input" placeholder="Search…" autocomplete="off">
        <div id="admTopbarSearchResultsMobile" class="adm-dropdown adm-dropdown--search adm-dropdown--closed tw-static tw-shadow-none tw-border-0 tw-mt-1"></div>
    </div>
</div>
