@extends('admin.layout.app')

@section('heading', 'Edit Guide — ' . $guide->title)

@section('button')
    <a href="{{ route('admin_broker_guides_index', $broker->id) }}" class="btn btn-primary">
        <i class="fas fa-arrow-left"></i> Back to Guides
    </a>
@endsection

@section('main_content')
<div class="tw-max-w-6xl tw-mx-auto tw-px-4 tw-py-6">
    <div class="tw-bg-white tw-rounded-2xl tw-border tw-border-slate-200/70 tw-overflow-hidden">
        <div class="tw-px-6 tw-py-5 tw-border-b tw-border-slate-100">
            <p class="tw-text-xs tw-font-bold tw-uppercase tw-tracking-widest tw-text-slate-500">Broker guide</p>
            <h2 class="tw-mt-1 tw-text-2xl tw-font-extrabold tw-text-slate-900">{{ $guide->title }}</h2>
            <p class="tw-mt-1 tw-text-sm tw-text-slate-600">{{ $broker->name }} · {{ $topic->slug ?? '' }}</p>
        </div>

        <div class="tw-px-6 tw-py-6">
            @include('admin.brokers._tabs', ['broker' => $broker, 'activeTab' => 'guides'])

            @if ($errors->any())
                <div class="tw-mt-4 tw-bg-rose-50 tw-border tw-border-rose-200 tw-text-rose-800 tw-rounded-2xl tw-px-5 tw-py-4 tw-text-sm tw-font-bold">
                    <ul class="tw-mb-0">
                        @foreach ($errors->all() as $error)
                            <li class="tw-mb-1">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin_broker_guides_update', [$broker->id, $topic->slug]) }}" method="POST" class="tw-mt-6">
                @csrf
                @method('PUT')

                <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-3 tw-gap-6">
                    <div class="lg:tw-col-span-2">
                        <div class="tw-bg-slate-50 tw-border tw-border-slate-200 tw-rounded-2xl tw-px-5 tw-py-4 tw-space-y-5">
                            <div class="tw-space-y-2">
                                <label class="tw-text-sm tw-font-extrabold tw-text-slate-900">
                                    Title <span class="tw-text-red-600">*</span>
                                </label>
                                <input type="text" name="title" class="form-control tw-w-full" value="{{ old('title', $guide->title) }}" required>
                            </div>

                            <div class="tw-space-y-2">
                                <label class="tw-text-sm tw-font-extrabold tw-text-slate-900">Summary</label>
                                <textarea name="summary" class="form-control tw-w-full" rows="2" placeholder="Short blurb for the guide hub card">{{ old('summary', $guide->summary) }}</textarea>
                                <p class="tw-text-xs tw-text-slate-600">Shown on the broker review page guide grid.</p>
                            </div>

                            <div class="tw-space-y-2">
                                <label class="tw-text-sm tw-font-extrabold tw-text-slate-900">Content</label>
                                <textarea name="content" class="form-control snote tw-w-full" rows="14">{{ old('content', $guide->content) }}</textarea>
                                <p class="tw-text-xs tw-text-slate-600">Main guide body shown on the dedicated guide page.</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="tw-bg-white tw-border tw-border-slate-200/70 tw-rounded-2xl tw-px-5 tw-py-4 tw-space-y-5">
                            <div class="tw-space-y-2">
                                <label class="tw-text-sm tw-font-extrabold tw-text-slate-900">
                                    Status <span class="tw-text-red-600">*</span>
                                </label>
                                <select name="status" class="form-control" required>
                                    @foreach (['draft' => 'Draft', 'published' => 'Published', 'hidden' => 'Hidden'] as $value => $label)
                                        <option value="{{ $value }}" @selected(old('status', $guide->status) === $value)">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="tw-space-y-2">
                                <label class="tw-text-sm tw-font-extrabold tw-text-slate-900">Meta title</label>
                                <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $guide->meta_title) }}">
                            </div>

                            <div class="tw-space-y-2">
                                <label class="tw-text-sm tw-font-extrabold tw-text-slate-900">Meta description</label>
                                <textarea name="meta_description" class="form-control" rows="3">{{ old('meta_description', $guide->meta_description) }}</textarea>
                            </div>
                        </div>

                        @if($topic?->requires_swap_free)
                            @php $hasSwapFree = $broker->accountOptions->contains(fn ($o) => (bool) $o->swap_free); @endphp
                            <div class="tw-mt-4 tw-text-xs tw-font-bold tw-rounded-2xl tw-px-4 tw-py-3 tw-border
                                {{ $hasSwapFree ? 'tw-bg-sky-50 tw-border-sky-200 tw-text-sky-800' : 'tw-bg-amber-50 tw-border-amber-200 tw-text-amber-800' }}">
                                @if($hasSwapFree)
                                    Broker has swap-free accounts — this guide can be published.
                                @else
                                    Add a swap-free account option first. Until then, keep it hidden.
                                @endif
                            </div>
                        @endif

                        @if($topic?->context_profile)
                            <div class="tw-mt-3 tw-text-xs tw-rounded-2xl tw-bg-slate-50 tw-border tw-border-slate-200 tw-px-4 tw-py-3 tw-font-bold tw-text-slate-700">
                                Auto context: <span class="tw-font-extrabold tw-text-slate-900">{{ \App\Models\BrokerGuideTopic::contextProfileOptions()[$topic->context_profile] ?? $topic->context_profile }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="tw-mt-6 tw-flex tw-items-center tw-justify-end">
                    <button type="submit" class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-brand tw-text-white tw-px-6 tw-py-2.5 tw-text-sm tw-font-extrabold hover:tw-bg-brand/90">
                        <i class="fas fa-save"></i>
                        Save guide
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
