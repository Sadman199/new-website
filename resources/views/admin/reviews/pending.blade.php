@extends('admin.layout.app')

@section('heading', 'Pending Reviews')

@section('main_content')
<div class="tw-max-w-7xl tw-mx-auto tw-px-4 tw-py-6">
    <div class="tw-mb-6">
        <p class="tw-text-xs tw-font-semibold tw-uppercase tw-tracking-widest tw-text-slate-500">Moderation</p>
        <h2 class="tw-mt-1 tw-text-2xl tw-font-extrabold tw-text-slate-900">Pending user reviews</h2>
        <p class="tw-mt-1 tw-text-sm tw-text-slate-600">Approve only when the review or reply is accurate and compliant. Decline for spam or policy issues.</p>
    </div>

    @if(session('success'))
        <div class="tw-mb-5 tw-bg-emerald-50 tw-border tw-border-emerald-200 tw-text-emerald-800 tw-rounded-2xl tw-px-5 tw-py-3 tw-text-sm tw-font-bold">
            {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="tw-mb-5 tw-bg-rose-50 tw-border tw-border-rose-200 tw-text-rose-800 tw-rounded-2xl tw-px-5 tw-py-3 tw-text-sm tw-font-bold">
            {{ session('error') }}
        </div>
    @endif

    @if($reviews->isEmpty())
        <div class="tw-bg-white tw-rounded-2xl tw-border tw-border-slate-200/70 tw-px-6 tw-py-10 tw-text-sm tw-text-slate-600">
            No pending reviews available.
        </div>
    @else
        <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-2 tw-gap-4">
            @foreach($reviews as $review)
                <article class="tw-bg-white tw-rounded-2xl tw-border tw-border-slate-200/70 tw-overflow-hidden">
                    <div class="tw-px-5 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-start tw-justify-between tw-gap-4">
                        <div class="tw-min-w-0">
                            <div class="tw-flex tw-items-center tw-gap-2 tw-flex-wrap">
                                <h3 class="tw-text-base tw-font-extrabold tw-text-slate-900 tw-truncate">{{ $review->name }}</h3>
                                @if($review->isReply())
                                    <span class="tw-inline-flex tw-items-center tw-rounded-full tw-bg-sky-50 tw-border tw-border-sky-200 tw-px-2.5 tw-py-0.5 tw-text-[11px] tw-font-extrabold tw-text-sky-700">Reply</span>
                                @else
                                    <span class="tw-inline-flex tw-items-center tw-rounded-full tw-bg-slate-50 tw-border tw-border-slate-200 tw-px-2.5 tw-py-0.5 tw-text-[11px] tw-font-extrabold tw-text-slate-600">Review</span>
                                @endif
                            </div>
                            <p class="tw-mt-1 tw-text-xs tw-text-slate-500">{{ $review->email }}</p>
                        </div>

                        <div class="tw-text-right">
                            @unless($review->isReply())
                                <span class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-full tw-bg-amber-50 tw-border tw-border-amber-200 tw-px-3 tw-py-1 tw-text-[11px] tw-font-extrabold tw-text-amber-700">
                                    <i class="fas fa-star tw-text-amber-600"></i>
                                    {{ $review->rating }} / 5
                                </span>
                            @endunless
                            <p class="tw-mt-2 tw-text-xs tw-text-slate-600">{{ $review->country ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="tw-px-5 tw-py-4">
                        <div class="tw-space-y-2">
                            <div>
                                <p class="tw-text-[11px] tw-font-bold tw-uppercase tw-tracking-widest tw-text-slate-500">Broker</p>
                                <p class="tw-mt-1 tw-text-sm tw-font-extrabold tw-text-slate-900">
                                    {{ $review->broker->name ?? 'N/A' }}
                                </p>
                            </div>

                            @if($review->isReply() && $review->parent)
                                <div>
                                    <p class="tw-text-[11px] tw-font-bold tw-uppercase tw-tracking-widest tw-text-slate-500">In reply to</p>
                                    <p class="tw-mt-1 tw-text-sm tw-text-slate-700">
                                        {{ Str::limit($review->parent->description, 140) }}
                                    </p>
                                </div>
                            @endif

                            @unless($review->isReply())
                                <div class="tw-flex tw-flex-wrap tw-gap-2">
                                    @if($review->rating_cost)
                                        <span class="tw-text-[11px] tw-font-bold tw-rounded-full tw-bg-slate-50 tw-border tw-border-slate-200 tw-px-2.5 tw-py-1">Cost {{ $review->rating_cost }}/5</span>
                                    @endif
                                    @if($review->rating_platforms)
                                        <span class="tw-text-[11px] tw-font-bold tw-rounded-full tw-bg-slate-50 tw-border tw-border-slate-200 tw-px-2.5 tw-py-1">Platforms {{ $review->rating_platforms }}/5</span>
                                    @endif
                                    @if($review->rating_customer_support)
                                        <span class="tw-text-[11px] tw-font-bold tw-rounded-full tw-bg-slate-50 tw-border tw-border-slate-200 tw-px-2.5 tw-py-1">Support {{ $review->rating_customer_support }}/5</span>
                                    @endif
                                    @if($review->lengthOfUseLabel())
                                        <span class="tw-text-[11px] tw-font-bold tw-rounded-full tw-bg-slate-50 tw-border tw-border-slate-200 tw-px-2.5 tw-py-1">{{ $review->lengthOfUseLabel() }}</span>
                                    @endif
                                    @if($review->account_type)
                                        <span class="tw-text-[11px] tw-font-bold tw-rounded-full tw-bg-slate-50 tw-border tw-border-slate-200 tw-px-2.5 tw-py-1">{{ $review->account_type }}</span>
                                    @endif
                                </div>
                            @endunless

                            <div>
                                <p class="tw-text-[11px] tw-font-bold tw-uppercase tw-tracking-widest tw-text-slate-500">{{ $review->isReply() ? 'Reply' : 'Review' }}</p>
                                <p class="tw-mt-1 tw-text-sm tw-text-slate-700">
                                    {{ Str::limit($review->description, 220) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="tw-px-5 tw-pb-5">
                        <div class="tw-flex tw-items-center tw-justify-end tw-gap-2 tw-flex-wrap">
                            <form action="{{ route('reviews.approve', $review->id) }}" method="POST" onsubmit="return confirm('Approve this {{ $review->isReply() ? 'reply' : 'review' }}?');">
                                @csrf
                                <button type="submit" class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-emerald-50 tw-border tw-border-emerald-200 tw-text-emerald-700 tw-px-4 tw-py-2.5 tw-text-sm tw-font-extrabold hover:tw-bg-emerald-100">
                                    <i class="fas fa-check"></i>
                                    Approve
                                </button>
                            </form>
                            <form action="{{ route('reviews.decline', $review->id) }}" method="POST" onsubmit="return confirm('Decline this {{ $review->isReply() ? 'reply' : 'review' }}?');">
                                @csrf
                                <button type="submit" class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-rose-50 tw-border tw-border-rose-200 tw-text-rose-700 tw-px-4 tw-py-2.5 tw-text-sm tw-font-extrabold hover:tw-bg-rose-100">
                                    <i class="fas fa-times"></i>
                                    Decline
                                </button>
                            </form>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</div>
@endsection
