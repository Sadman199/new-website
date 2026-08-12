@php
    $activeTab = $activeTab ?? 'account-options';
@endphp
<div class="tw-flex tw-items-center tw-gap-2 tw-bg-slate-50 tw-border tw-border-slate-200 tw-rounded-2xl tw-p-1 tw-mb-5">
    <a href="{{ route('admin_broker_edit', $broker->id) }}"
       class="tw-flex tw-items-center tw-gap-2 tw-px-3 tw-py-2 tw-rounded-xl tw-text-sm tw-font-semibold tw-transition tw-duration-150 tw-border tw-border-transparent
           {{ $activeTab === 'broker' ? 'tw-bg-white tw-text-slate-900 tw-border-slate-200' : 'tw-text-slate-600 hover:tw-bg-white/70' }}">
        <i class="fas fa-briefcase tw-text-brand"></i>
        <span>Broker Profile</span>
    </a>

    @php $optionCount = $broker->account_options_count ?? $broker->accountOptions?->count() ?? 0; @endphp
    <a href="{{ route('admin_account_options_index', $broker->id) }}"
       class="tw-flex tw-items-center tw-gap-2 tw-px-3 tw-py-2 tw-rounded-xl tw-text-sm tw-font-semibold tw-transition tw-duration-150 tw-border tw-border-transparent
           {{ $activeTab === 'account-options' ? 'tw-bg-white tw-text-slate-900 tw-border-slate-200' : 'tw-text-slate-600 hover:tw-bg-white/70' }}">
        <i class="fas fa-layer-group tw-text-brand"></i>
        <span>Account Options</span>
        @if($optionCount)
            <span class="tw-ml-1 tw-inline-flex tw-items-center tw-h-5 tw-px-2 tw-rounded-full tw-bg-slate-100 tw-text-slate-700 tw-text-xs tw-font-bold">
                {{ $optionCount }}
            </span>
        @endif
    </a>

    @php $guideCount = $broker->guides_count ?? $broker->guides?->count() ?? 0; @endphp
    <a href="{{ route('admin_broker_guides_index', $broker->id) }}"
       class="tw-flex tw-items-center tw-gap-2 tw-px-3 tw-py-2 tw-rounded-xl tw-text-sm tw-font-semibold tw-transition tw-duration-150 tw-border tw-border-transparent
           {{ $activeTab === 'guides' ? 'tw-bg-white tw-text-slate-900 tw-border-slate-200' : 'tw-text-slate-600 hover:tw-bg-white/70' }}">
        <i class="fas fa-book-open tw-text-brand"></i>
        <span>Guides</span>
        @if($guideCount)
            <span class="tw-ml-1 tw-inline-flex tw-items-center tw-h-5 tw-px-2 tw-rounded-full tw-bg-slate-100 tw-text-slate-700 tw-text-xs tw-font-bold">
                {{ $guideCount }}
            </span>
        @endif
    </a>
</div>
