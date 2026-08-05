@php
    $admin = $adminUser ?? Auth::guard('admin')->user();
    $initials = collect(explode(' ', $admin->name ?? 'A'))->map(fn ($w) => strtoupper(substr($w, 0, 1)))->take(2)->join('');
@endphp

<header class="admin-header">
    <button type="button" class="header-toggle" id="sidebarToggle" aria-label="Toggle menu">
        <i class="fas fa-bars"></i>
    </button>
    <button type="button" class="header-toggle d-none d-lg-flex" id="sidebarCollapse" aria-label="Collapse sidebar">
        <i class="fas fa-compress-alt"></i>
    </button>
    <div class="header-search">
        <i class="fas fa-search"></i>
        <input type="search" placeholder="Search brokers, users, articles…" aria-label="Search">
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.panel.reviews.index', ['status' => 'pending']) }}" class="header-btn" title="Pending reviews">
            <i class="fas fa-inbox"></i>
        </a>
    </div>
    <div class="header-user dropdown">
        <div class="header-avatar">{{ $initials }}</div>
        <div class="header-user-info">
            <span>{{ $admin->name ?? 'Admin' }}</span>
            <small id="headerPageTitle">{{ $pageTitle ?? 'Dashboard' }}</small>
        </div>
    </div>
    @include('admin.partials.logout-form', [
        'class' => 'btn-bc btn-bc-ghost btn-bc-sm ml-2 d-none d-md-inline-flex',
        'icon' => 'fas fa-sign-out-alt',
        'label' => ' Logout',
    ])
</header>
