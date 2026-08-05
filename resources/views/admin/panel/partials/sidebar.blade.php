@php
    $currentRoute = Route::currentRouteName();
    $badges = $panelBadges ?? [];
@endphp

<aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-brand">
        <div class="sidebar-brand-icon">BC</div>
        <div class="sidebar-brand-text">
            <h1>{{ config('admin-panel.name') }}</h1>
            <span>{{ config('admin-panel.tagline') }}</span>
        </div>
    </div>

    <nav class="sidebar-nav">
        @foreach(config('admin-panel.navigation', []) as $section)
            <div class="nav-section-label">{{ $section['label'] }}</div>
            @foreach($section['items'] as $item)
                @continue(!empty($item['hidden']))
                @php
                    $isActive = $currentRoute === $item['route']
                        || (isset($item['active_routes']) && in_array($currentRoute, $item['active_routes'], true));
                    $badgeKey = $item['badge'] ?? null;
                    $badgeValue = $badgeKey ? ($badges[$badgeKey] ?? null) : null;
                    try {
                        $href = route($item['route'], $item['params'] ?? []);
                    } catch (\Throwable $e) {
                        $href = '#';
                    }
                @endphp
                <a href="{{ $href }}"
                   class="nav-item {{ $isActive ? 'active' : '' }}"
                   @if($isActive) aria-current="page" @endif>
                    <i class="{{ $item['icon'] }}"></i>
                    {{ $item['label'] }}
                    @if(!empty($item['suffix']))
                        <small class="text-muted ml-1">{{ $item['suffix'] }}</small>
                    @endif
                    @if($badgeValue)
                        <span class="badge-count">{{ $badgeValue }}</span>
                    @endif
                </a>
            @endforeach
        @endforeach
    </nav>

    <div class="sidebar-footer">
        <a class="nav-item" href="{{ url('/') }}" target="_blank" rel="noopener">
            <i class="fas fa-external-link-alt"></i> View Website
        </a>
    </div>
</aside>
