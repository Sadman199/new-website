@once
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin-brokers.css') }}?v=3">
    @endpush
@endonce
@php
    $activeTab = $activeTab ?? 'broker';
    $optionCount = $broker->account_options_count ?? $broker->accountOptions?->count() ?? 0;
    $guideCount = $broker->guides_count ?? $broker->guides?->count() ?? 0;
@endphp
<nav class="ab-tabs" aria-label="Broker admin sections">
    <a href="{{ route('admin_broker_view', $broker->id) }}" class="{{ $activeTab === 'view' ? 'is-active' : '' }}">
        <i class="fas fa-eye" aria-hidden="true"></i> Overview
    </a>
    <a href="{{ route('admin_broker_edit', $broker->id) }}" class="{{ $activeTab === 'broker' ? 'is-active' : '' }}">
        <i class="fas fa-pen" aria-hidden="true"></i> Edit profile
    </a>
    <a href="{{ route('admin_account_options_index', $broker->id) }}" class="{{ $activeTab === 'account-options' ? 'is-active' : '' }}">
        <i class="fas fa-layer-group" aria-hidden="true"></i> Accounts
        @if($optionCount)<span class="ab-count">{{ $optionCount }}</span>@endif
    </a>
    <a href="{{ route('admin_broker_guides_index', $broker->id) }}" class="{{ $activeTab === 'guides' ? 'is-active' : '' }}">
        <i class="fas fa-book-open" aria-hidden="true"></i> Guides
        @if($guideCount)<span class="ab-count">{{ $guideCount }}</span>@endif
    </a>
</nav>
