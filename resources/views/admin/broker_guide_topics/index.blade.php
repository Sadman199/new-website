@extends('admin.layout.app')

@section('heading', 'Broker Guide Topics')

@section('button')
    <a href="{{ route('admin_broker_guide_topics_create') }}"
       class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-brand tw-text-white tw-px-5 tw-py-2.5 tw-text-sm tw-font-extrabold hover:tw-bg-brand/90">
        <i class="fas fa-plus"></i>
        Add topic
    </a>
@endsection

@section('main_content')
<div class="tw-max-w-7xl tw-mx-auto tw-px-4 tw-py-6">
    @if (session('success'))
        <div class="tw-mb-5 tw-bg-emerald-50 tw-border tw-border-emerald-200 tw-text-emerald-800 tw-rounded-2xl tw-px-5 tw-py-3 tw-text-sm tw-font-bold">
            {{ session('success') }}
        </div>
    @endif

    <div class="tw-bg-white tw-rounded-2xl tw-border tw-border-slate-200/70 tw-overflow-hidden tw-mb-5">
        <div class="tw-px-6 tw-py-5 tw-border-b tw-border-slate-100">
            <p class="tw-text-xs tw-font-bold tw-uppercase tw-tracking-widest tw-text-slate-500">Review page hub</p>
            <h2 class="tw-mt-1 tw-text-lg tw-font-extrabold tw-text-slate-900">Hub heading & intro</h2>
            <p class="tw-mt-1 tw-text-sm tw-text-slate-600">Controls the heading and intro shown on every broker review page.</p>
        </div>

        <div class="tw-px-6 tw-py-6">
            <form action="{{ route('admin_broker_guide_topics_hub') }}" method="POST" class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-4">
                @csrf
                <div class="tw-space-y-2">
                    <label class="tw-text-sm tw-font-extrabold tw-text-slate-900">Hub title</label>
                    <input type="text" name="hub_title" class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white tw-text-sm tw-px-3 tw-py-2" value="{{ old('hub_title', app(\App\Services\BrokerGuideHubService::class)->titleTemplate()) }}" required>
                    <p class="tw-text-xs tw-text-slate-600">Use <code class="tw-text-xs">:broker</code> for the broker name.</p>
                </div>
                <div class="tw-space-y-2">
                    <label class="tw-text-sm tw-font-extrabold tw-text-slate-900">Hub description</label>
                    <textarea name="hub_description" class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white tw-text-sm tw-px-3 tw-py-2" rows="2">{{ old('hub_description', app(\App\Services\BrokerGuideHubService::class)->description()) }}</textarea>
                </div>
                <div class="tw-col-span-1 md:tw-col-span-2 tw-flex tw-items-center tw-justify-end">
                    <button type="submit" class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-border tw-border-brand/30 tw-bg-white tw-text-brand tw-px-4 tw-py-2.5 tw-text-sm tw-font-extrabold hover:tw-bg-brand/5">
                        <i class="fas fa-save"></i>
                        Save hub settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="tw-bg-white tw-rounded-2xl tw-border tw-border-slate-200/70 tw-overflow-hidden">
        <div class="tw-px-6 tw-py-5 tw-border-b tw-border-slate-100 tw-flex tw-items-start tw-justify-between tw-gap-4 tw-flex-wrap">
            <div>
                <p class="tw-text-xs tw-font-bold tw-uppercase tw-tracking-widest tw-text-slate-500">Guide topics</p>
                <h2 class="tw-mt-1 tw-text-lg tw-font-extrabold tw-text-slate-900">Topics & activation</h2>
                <p class="tw-mt-1 tw-text-sm tw-text-slate-600">Add or reorder topics. New active topics auto-create drafts for every broker.</p>
            </div>
        </div>

        <div class="tw-px-6 tw-py-6">
            @if($topics->isEmpty())
                <div class="tw-text-sm tw-text-slate-600">No topics found.</div>
            @else
                <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 xl:tw-grid-cols-3 tw-gap-4">
                    @foreach($topics as $topic)
                        <article class="tw-bg-white tw-rounded-2xl tw-border tw-border-slate-200/70 tw-overflow-hidden">
                            <div class="tw-px-5 tw-py-4 tw-border-b tw-border-slate-100">
                                <div class="tw-flex tw-items-start tw-justify-between tw-gap-4">
                                    <div class="tw-min-w-0">
                                        <p class="tw-text-[11px] tw-font-bold tw-uppercase tw-tracking-widest tw-text-slate-500">Topic</p>
                                        <h3 class="tw-text-base tw-font-extrabold tw-text-slate-900 tw-truncate">
                                            @if($topic->icon)
                                                <i class="{{ $topic->icon }} tw-text-slate-400 tw-mr-1"></i>
                                            @endif
                                            {{ $topic->title }}
                                        </h3>
                                        <div class="tw-mt-2 tw-flex tw-items-center tw-gap-2 tw-flex-wrap">
                                            @if($topic->requires_swap_free)
                                                <span class="tw-inline-flex tw-items-center tw-rounded-full tw-bg-sky-50 tw-border tw-border-sky-200 tw-px-2 tw-py-0.5 tw-text-[11px] tw-font-extrabold tw-text-sky-700">
                                                    Swap-free
                                                </span>
                                            @endif
                                            <span class="tw-inline-flex tw-items-center tw-rounded-full tw-bg-slate-50 tw-border tw-border-slate-200 tw-px-2 tw-py-0.5 tw-text-[11px] tw-font-extrabold tw-text-slate-700">
                                                Order {{ $topic->sort_order }}
                                            </span>
                                        </div>
                                    </div>

                                    <span class="tw-inline-flex tw-items-center tw-h-7 tw-px-3 tw-rounded-full tw-text-[11px] tw-font-extrabold tw-border
                                        {{ $topic->is_active ? 'tw-bg-emerald-50 tw-border-emerald-200 tw-text-emerald-700' : 'tw-bg-slate-50 tw-border-slate-200 tw-text-slate-700' }}">
                                        {{ $topic->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>

                            <div class="tw-px-5 tw-py-4">
                                <div class="tw-space-y-3">
                                    <div>
                                        <p class="tw-text-[11px] tw-font-bold tw-uppercase tw-tracking-widest tw-text-slate-500">Slug</p>
                                        <p class="tw-mt-1 tw-text-sm tw-font-extrabold tw-text-slate-900"><code>{{ $topic->slug }}</code></p>
                                    </div>
                                    <div>
                                        <p class="tw-text-[11px] tw-font-bold tw-uppercase tw-tracking-widest tw-text-slate-500">Context profile</p>
                                        <p class="tw-mt-1 tw-text-sm tw-font-extrabold tw-text-slate-900">
                                            {{ $contextProfiles[$topic->context_profile ?? ''] ?? '—' }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="tw-text-[11px] tw-font-bold tw-uppercase tw-tracking-widest tw-text-slate-500">Brokers</p>
                                        <p class="tw-mt-1 tw-text-sm tw-font-extrabold tw-text-slate-900">{{ $topic->guides_count ?? 0 }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="tw-px-5 tw-pb-5">
                                <div class="tw-flex tw-items-center tw-justify-between tw-gap-3 tw-flex-wrap">
                                    <a href="{{ route('admin_broker_guide_topics_edit', $topic->id) }}"
                                       class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-brand tw-text-white tw-px-4 tw-py-2.5 tw-text-sm tw-font-extrabold hover:tw-bg-brand/90">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </a>

                                    <form action="{{ route('admin_broker_guide_topics_destroy', $topic->id) }}" method="POST"
                                          onsubmit="return confirm('Delete this topic and all broker guide content?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-rose-50 tw-border tw-border-rose-200 tw-text-rose-700 tw-px-4 tw-py-2.5 tw-text-sm tw-font-extrabold hover:tw-bg-rose-100">
                                            <i class="fas fa-trash-alt"></i>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
