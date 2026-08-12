@extends('admin.layout.app')

@section('heading', 'Prop Firms Dashboard')

@section('button')
<a href="{{ route('admin_prop_firms_create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add Prop Firm</a>
@endsection

@section('main_content')
<div class="tw-max-w-7xl tw-mx-auto tw-px-4 tw-py-6">
    <div class="tw-mb-6">
        <p class="tw-text-xs tw-font-semibold tw-uppercase tw-tracking-widest tw-text-slate-500">Prop firms</p>
        <h2 class="tw-mt-1 tw-text-2xl tw-font-extrabold tw-text-slate-900">Overview</h2>
        <p class="tw-mt-1 tw-text-sm tw-text-slate-600">Track firm activity, quality signals, and moderation volume.</p>
    </div>

    <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 lg:tw-grid-cols-4 tw-gap-4 tw-mb-6">
        @foreach([
            ['label' => 'Total Firms', 'value' => $stats['total'], 'icon' => 'fa-building', 'tone' => 'blue'],
            ['label' => 'Active', 'value' => $stats['active'], 'icon' => 'fa-check-circle', 'tone' => 'green'],
            ['label' => 'Featured', 'value' => $stats['featured'], 'icon' => 'fa-star', 'tone' => 'amber'],
            ['label' => 'Verified', 'value' => $stats['verified'], 'icon' => 'fa-shield-alt', 'tone' => 'violet'],
            ['label' => 'Programs', 'value' => $stats['programs'], 'icon' => 'fa-layer-group', 'tone' => 'blue'],
            ['label' => 'Reviews', 'value' => $stats['reviews'], 'icon' => 'fa-comments', 'tone' => 'violet'],
            ['label' => 'FAQs', 'value' => $stats['faqs'], 'icon' => 'fa-question-circle', 'tone' => 'blue'],
            ['label' => 'Categories', 'value' => $stats['categories'], 'icon' => 'fa-tags', 'tone' => 'violet'],
        ] as $card)
            @php
                $iconBg = $card['tone'] === 'green' ? 'tw-bg-emerald-50 tw-border-emerald-200 tw-text-emerald-700' :
                    ($card['tone'] === 'amber' ? 'tw-bg-amber-50 tw-border-amber-200 tw-text-amber-700' :
                    ($card['tone'] === 'violet' ? 'tw-bg-indigo-50 tw-border-indigo-200 tw-text-indigo-700' :
                    'tw-bg-brand/10 tw-border-brand/30 tw-text-brand'));
            @endphp

            <article class="tw-bg-white tw-rounded-2xl tw-border tw-border-slate-200/70 tw-px-5 tw-py-4">
                <div class="tw-flex tw-items-start tw-justify-between tw-gap-3">
                    <span class="tw-inline-flex tw-items-center tw-justify-center tw-w-10 tw-h-10 tw-rounded-xl tw-border {{ $iconBg }}">
                        <i class="fas {{ $card['icon'] }} tw-text-sm"></i>
                    </span>
                    <p class="tw-text-[11px] tw-font-bold tw-uppercase tw-tracking-widest tw-text-slate-500 tw-mt-1">{{ $card['label'] }}</p>
                </div>
                <p class="tw-mt-3 tw-text-2xl tw-font-extrabold tw-text-slate-900 tw-leading-none">{{ number_format($card['value']) }}</p>
            </article>
        @endforeach
    </div>

    <div class="tw-bg-white tw-rounded-2xl tw-border tw-border-slate-200/70 tw-overflow-hidden">
        <div class="tw-px-6 tw-py-5 tw-border-b tw-border-slate-100">
            <h3 class="tw-text-lg tw-font-extrabold tw-text-slate-900">Recently added</h3>
            <p class="tw-mt-1 tw-text-sm tw-text-slate-600">Jump back into editing from here.</p>
        </div>
        <div class="tw-px-6 tw-py-6">
            @if($recent->isEmpty())
                <div class="tw-text-sm tw-text-slate-600">No prop firms yet.</div>
            @else
                <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 xl:tw-grid-cols-3 tw-gap-4">
                    @foreach($recent as $firm)
                        <article class="tw-bg-white tw-rounded-2xl tw-border tw-border-slate-200/70 tw-overflow-hidden">
                            <div class="tw-px-5 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-start tw-justify-between tw-gap-4">
                                <div class="tw-min-w-0">
                                    <h4 class="tw-text-base tw-font-extrabold tw-text-slate-900 tw-truncate">{{ $firm->name }}</h4>
                                    <p class="tw-mt-1 tw-text-xs tw-text-slate-500">{{ $firm->category?->name ?? '—' }}</p>
                                </div>
                                <span class="tw-inline-flex tw-items-center tw-h-7 tw-px-3 tw-rounded-full tw-text-[11px] tw-font-extrabold tw-border {{ $firm->is_active ? 'tw-bg-emerald-50 tw-border-emerald-200 tw-text-emerald-700' : 'tw-bg-slate-50 tw-border-slate-200 tw-text-slate-700' }}">
                                    {{ $firm->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <div class="tw-px-5 tw-py-4">
                                <div class="tw-space-y-2">
                                    <div>
                                        <p class="tw-text-[11px] tw-font-bold tw-uppercase tw-tracking-widest tw-text-slate-500">Trust</p>
                                        <p class="tw-mt-1 tw-text-sm tw-font-extrabold tw-text-slate-900">{{ $firm->trust_score ?? '—' }}</p>
                                    </div>
                                    <div>
                                        <p class="tw-text-[11px] tw-font-bold tw-uppercase tw-tracking-widest tw-text-slate-500">Created</p>
                                        <p class="tw-mt-1 tw-text-sm tw-font-extrabold tw-text-slate-900">{{ $firm->created_at?->format('M d, Y') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="tw-px-5 tw-pb-5">
                                <a href="{{ route('admin_prop_firms_edit', $firm->id) }}" class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-brand tw-text-white tw-px-4 tw-py-2.5 tw-text-sm tw-font-extrabold hover:tw-bg-brand/90">
                                    <i class="fas fa-edit"></i>
                                    Edit
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
