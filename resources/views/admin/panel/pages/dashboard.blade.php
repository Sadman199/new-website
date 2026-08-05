@php
    $adminName = Auth::guard('admin')->user()->name ?? 'Admin';
    $firstName = explode(' ', trim($adminName))[0];
@endphp

<div class="welcome-banner">
    <div>
        <h2>Welcome back, <span class="highlight">{{ $firstName }}</span></h2>
        <p>Manage brokers, reviews, content, and discovery features from one place.</p>
    </div>
    <a href="{{ route('admin_broker_create') }}" class="btn-bc btn-bc-primary">
        <i class="fas fa-plus"></i> Add Broker
    </a>
</div>

<div class="stat-grid">
    <x-admin.stat-card
        icon="fas fa-building"
        iconClass="yellow"
        :value="$stats['brokers'] ?? 0"
        label="Active Brokers"
    />
    <x-admin.stat-card
        icon="fas fa-star"
        iconClass="green"
        :value="$stats['reviews'] ?? 0"
        label="Total Reviews"
        :change="'<i class=\'fas fa-arrow-up\'></i> ' . ($stats['pending_reviews'] ?? 0) . ' pending'"
    />
    <x-admin.stat-card
        icon="fas fa-users"
        iconClass="blue"
        :value="$stats['subscribers'] ?? 0"
        label="Subscribers"
    />
    <x-admin.stat-card
        icon="fas fa-exclamation-triangle"
        iconClass="red"
        :value="$stats['scam_brokers'] ?? 0"
        label="Scam Brokers"
        change="Monitor closely"
    />
</div>

<div class="grid-2">
    <x-admin.card title="Broker Registrations (Demo)">
        <div class="chart-placeholder">
            <div class="chart-bar" style="height:45%"></div>
            <div class="chart-bar" style="height:62%"></div>
            <div class="chart-bar" style="height:38%"></div>
            <div class="chart-bar" style="height:78%"></div>
            <div class="chart-bar" style="height:55%"></div>
            <div class="chart-bar" style="height:90%"></div>
            <div class="chart-bar" style="height:70%"></div>
        </div>
    </x-admin.card>

    <x-admin.card title="Recent Activity">
        <ul class="activity-list">
            @forelse($recentActivity ?? [] as $activity)
                <li>
                    <div class="activity-icon {{ $activity['icon_class'] ?? 'yellow' }}">
                        <i class="{{ $activity['icon'] ?? 'fas fa-star' }}"></i>
                    </div>
                    <div class="activity-body">
                        <strong>{{ $activity['title'] }}</strong>
                        <small>{{ $activity['subtitle'] ?? '' }}</small>
                    </div>
                </li>
            @empty
                <li>
                    <div class="activity-body">
                        <small class="text-muted">No recent activity.</small>
                    </div>
                </li>
            @endforelse
        </ul>
    </x-admin.card>
</div>

<x-admin.card title="Quick Actions">
    <div class="quick-actions">
        <a href="{{ route('admin_broker_show') }}" class="quick-action-btn">
            <i class="fas fa-building"></i><span>Brokers</span>
        </a>
        <a href="{{ route('reviews.pending') }}" class="quick-action-btn">
            <i class="fas fa-star"></i><span>Reviews</span>
        </a>
        <a href="{{ route('admin_forex_bonus_show') }}" class="quick-action-btn">
            <i class="fas fa-gift"></i><span>Bonuses</span>
        </a>
        <a href="{{ route('admin_post_show') }}" class="quick-action-btn">
            <i class="fas fa-pen"></i><span>Articles</span>
        </a>
        <a href="{{ route('find_my_broker') }}" class="quick-action-btn" target="_blank">
            <i class="fas fa-search"></i><span>Find Broker</span>
        </a>
        <a href="{{ route('admin_setting') }}" class="quick-action-btn">
            <i class="fas fa-cog"></i><span>Settings</span>
        </a>
    </div>
</x-admin.card>
