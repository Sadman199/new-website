<div class="main-sidebar adm-sidebar">
    <aside id="sidebar-wrapper">
        {{-- Brand --}}
        <div class="adm-sidebar__brand">
            <a href="{{ route('admin_home') }}" class="adm-sidebar__brand-link">
                @php($logo = \App\Support\SiteTheme::setting()?->logo)
                @if($logo)
                    <img src="{{ asset('uploads/' . ltrim($logo, '/')) }}" alt="{{ \App\Support\SiteTheme::siteName() }}" class="adm-sidebar__logo">
                @else
                    <span class="adm-sidebar__logo-fallback" aria-hidden="true">
                        <i class="fas fa-chart-bar"></i>
                    </span>
                @endif
                <span class="adm-sidebar__brand-text">
                    <strong>{{ \App\Support\SiteTheme::siteName() }}</strong>
                    <small>Admin Panel</small>
                </span>
            </a>
        </div>

        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('admin_home') }}" class="adm-sidebar__brand-sm">
                <i class="fas fa-chart-bar"></i>
            </a>
        </div>

        {{-- Navigation --}}
        <ul class="sidebar-menu adm-sidebar__menu">
            @foreach(config('admin-sidebar.sections', []) as $section)
                <li class="menu-header adm-sidebar__heading">{{ $section['label'] }}</li>

                @foreach($section['items'] as $item)
                    @if(isset($item['children']))
                        @php($groupActive = \App\Support\AdminSidebar::isActive($item))
                        <li class="nav-item dropdown {{ $groupActive ? 'active' : '' }}">
                            <a href="#" class="nav-link has-dropdown adm-sidebar__link {{ $groupActive ? 'is-active' : '' }}">
                                <i class="{{ $item['icon'] }} adm-sidebar__icon" aria-hidden="true"></i>
                                <span class="adm-sidebar__label">{{ $item['label'] }}</span>
                            </a>
                            <ul class="dropdown-menu adm-sidebar__submenu">
                                @foreach($item['children'] as $child)
                                    <li class="{{ \App\Support\AdminSidebar::isChildActive($child) ? 'active' : '' }}">
                                        <a class="nav-link adm-sidebar__sublink" href="{{ route($child['route']) }}">
                                            {{ $child['label'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @else
                        @php($active = \App\Support\AdminSidebar::isActive($item))
                        @php($badge = \App\Support\AdminSidebar::badgeCount($item, $sidebarBadges ?? []))
                        <li class="{{ $active ? 'active' : '' }}">
                            <a class="nav-link adm-sidebar__link {{ $active ? 'is-active' : '' }}" href="{{ route($item['route']) }}">
                                <i class="{{ $item['icon'] }} adm-sidebar__icon" aria-hidden="true"></i>
                                <span class="adm-sidebar__label">{{ $item['label'] }}</span>
                                @if($badge)
                                    <span class="adm-sidebar__badge">{{ $badge }}</span>
                                @endif
                            </a>
                        </li>
                    @endif
                @endforeach
            @endforeach
        </ul>

        {{-- Footer --}}
        <div class="adm-sidebar__footer hide-sidebar-mini">
            <a href="{{ route('home') }}" target="_blank" rel="noopener" class="adm-sidebar__footer-link">
                <i class="fas fa-external-link-alt" aria-hidden="true"></i>
                View live site
            </a>
        </div>
    </aside>
</div>
