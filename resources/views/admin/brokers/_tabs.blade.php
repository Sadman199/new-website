@php
    $activeTab = $activeTab ?? 'account-options';
@endphp
<ul class="nav nav-pills mb-4">
    <li class="nav-item">
        <a class="nav-link {{ $activeTab === 'broker' ? 'active' : '' }}"
           href="{{ route('admin_broker_edit', $broker->id) }}">
            <i class="fas fa-briefcase mr-1"></i> Broker Profile
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $activeTab === 'account-options' ? 'active' : '' }}"
           href="{{ route('admin_account_options_index', $broker->id) }}">
            <i class="fas fa-layer-group mr-1"></i> Account Options
            @php $optionCount = $broker->account_options_count ?? $broker->accountOptions?->count() ?? 0; @endphp
            @if($optionCount)
                <span class="badge badge-light ml-1">{{ $optionCount }}</span>
            @endif
        </a>
    </li>
</ul>
