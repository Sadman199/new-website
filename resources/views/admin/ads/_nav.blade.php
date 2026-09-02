@php
    $active = $active ?? 'popups';
@endphp
<nav class="ab-tabs" aria-label="Advertisements">
    <a href="{{ route('admin_top_ad_show') }}" class="{{ $active === 'top' ? 'is-active' : '' }}">
        <i class="fas fa-window-maximize" aria-hidden="true"></i> Top Ad
    </a>
    <a href="{{ route('admin_home_ad_show') }}" class="{{ $active === 'home' ? 'is-active' : '' }}">
        <i class="fas fa-home" aria-hidden="true"></i> Home Ads
    </a>
    <a href="{{ route('admin_sidebar_ad_show') }}" class="{{ $active === 'sidebar' ? 'is-active' : '' }}">
        <i class="fas fa-columns" aria-hidden="true"></i> Sidebar Ads
    </a>
    <a href="{{ route('admin_ads_index') }}" class="{{ $active === 'popups' ? 'is-active' : '' }}">
        <i class="fas fa-bullhorn" aria-hidden="true"></i> Popup Ads
    </a>
</nav>
