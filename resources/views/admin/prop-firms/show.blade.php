@extends('admin.layout.app')

@section('heading', 'All Prop Firms')

@section('button')
<a href="{{ route('admin_prop_firms_create') }}"
   class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-brand tw-text-white tw-px-5 tw-py-2.5 tw-text-sm tw-font-extrabold hover:tw-bg-brand/90">
    <i class="fas fa-plus-circle"></i>
    Add new
</a>
@endsection

@section('main_content')
<div class="tw-max-w-7xl tw-mx-auto tw-px-4 tw-py-6">
    <div class="tw-flex tw-flex-col lg:tw-flex-row lg:tw-items-end lg:tw-justify-between tw-gap-3 tw-mb-6">
        <div>
            <p class="tw-text-xs tw-font-semibold tw-uppercase tw-tracking-widest tw-text-slate-500">Prop firms</p>
            <h2 class="tw-mt-1 tw-text-2xl tw-font-extrabold tw-text-slate-900">All prop firms</h2>
            <p class="tw-mt-1 tw-text-sm tw-text-slate-600">Filter, bulk manage, and edit prop firm details.</p>
        </div>
    </div>

    <div class="tw-bg-white tw-rounded-2xl tw-border tw-border-slate-200/70 tw-px-5 tw-py-4 tw-mb-5">
        <form method="GET" class="tw-grid tw-grid-cols-1 md:tw-grid-cols-5 tw-gap-3">
            <div class="tw-space-y-2 md:tw-col-span-1">
                <label class="tw-text-xs tw-font-bold tw-text-slate-600">Search</label>
                <input type="search" name="q" class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white tw-text-sm tw-px-3 tw-py-2" value="{{ request('q') }}" placeholder="Name or slug…">
            </div>

            <div class="tw-space-y-2 md:tw-col-span-1">
                <label class="tw-text-xs tw-font-bold tw-text-slate-600">Category</label>
                <select name="category_id" class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white tw-text-sm tw-px-3 tw-py-2">
                    <option value="">All</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="tw-space-y-2 md:tw-col-span-1">
                <label class="tw-text-xs tw-font-bold tw-text-slate-600">Status</label>
                <select name="status" class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white tw-text-sm tw-px-3 tw-py-2">
                    <option value="">All</option>
                    <option value="active" @selected(request('status') === 'active')>Active</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                </select>
            </div>

            <div class="tw-space-y-2 md:tw-col-span-1">
                <label class="tw-text-xs tw-font-bold tw-text-slate-600">Sort</label>
                <select name="sort" class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white tw-text-sm tw-px-3 tw-py-2">
                    @foreach(['created_at' => 'Created', 'name' => 'Name', 'trust_score' => 'Trust Score', 'overall_rating' => 'Overall Rating'] as $key => $label)
                        <option value="{{ $key }}" @selected($sort === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="tw-space-y-2 md:tw-col-span-1">
                <label class="tw-text-xs tw-font-bold tw-text-slate-600">Dir</label>
                <select name="direction" class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white tw-text-sm tw-px-3 tw-py-2">
                    <option value="desc" @selected($direction === 'desc')>Desc</option>
                    <option value="asc" @selected($direction === 'asc')>Asc</option>
                </select>
            </div>

            <div class="tw-col-span-full tw-flex tw-justify-end tw-pt-1">
                <button type="submit" class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-brand tw-text-white tw-px-5 tw-py-2.5 tw-text-sm tw-font-extrabold hover:tw-bg-brand/90">
                    <i class="fas fa-filter"></i>
                    Filter
                </button>
            </div>
        </form>
    </div>

    <form method="POST" action="{{ route('admin_prop_firms_bulk') }}" id="bulk-form">
        @csrf

        <div class="tw-bg-white tw-rounded-2xl tw-border tw-border-slate-200/70 tw-px-5 tw-py-4 tw-mb-5">
            <div class="tw-flex tw-items-center tw-justify-between tw-gap-4 tw-flex-wrap">
                <label class="tw-flex tw-items-center tw-gap-3 tw-text-sm tw-font-extrabold tw-text-slate-800">
                    <input type="checkbox" id="check-all" class="tw-w-4 tw-h-4 tw-rounded" />
                    Select all
                </label>

                <div class="tw-flex tw-items-center tw-gap-3 tw-flex-wrap">
                    <select name="action" class="tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white tw-text-sm tw-px-3 tw-py-2" style="width:auto;">
                        <option value="">Bulk action…</option>
                        <option value="activate">Activate</option>
                        <option value="deactivate">Deactivate</option>
                        <option value="delete">Delete</option>
                    </select>
                    <button type="submit" class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white tw-text-slate-800 tw-px-4 tw-py-2.5 tw-text-sm tw-font-extrabold hover:tw-bg-slate-50"
                            onclick="return confirm('Apply bulk action to selected items?')">
                        Apply
                    </button>
                </div>
            </div>
        </div>

        @if($propFirms->isEmpty())
            <div class="tw-bg-white tw-rounded-2xl tw-border tw-border-slate-200/70 tw-px-6 tw-py-10 tw-text-sm tw-text-slate-600">
                No prop firms found.
            </div>
        @else
            <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 xl:tw-grid-cols-3 tw-gap-4">
                @foreach($propFirms as $firm)
                    <article class="tw-bg-white tw-rounded-2xl tw-border tw-border-slate-200/70 tw-overflow-hidden">
                        <div class="tw-px-5 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-start tw-justify-between tw-gap-4">
                            <label class="tw-flex tw-items-center tw-gap-3">
                                <input type="checkbox" name="ids[]" value="{{ $firm->id }}" class="row-check tw-w-4 tw-h-4 tw-rounded" />
                                <div class="tw-w-10 tw-h-10 tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-flex tw-items-center tw-justify-center overflow-hidden">
                                    @if($firm->logo)
                                        <img src="{{ asset($firm->logo) }}" alt="{{ $firm->name }} logo" class="tw-w-full tw-h-full tw-object-contain" />
                                    @else
                                        <i class="fas fa-building tw-text-slate-400"></i>
                                    @endif
                                </div>
                            </label>

                            <span class="tw-inline-flex tw-items-center tw-h-7 tw-px-3 tw-rounded-full tw-text-[11px] tw-font-extrabold tw-border {{ $firm->is_active ? 'tw-bg-emerald-50 tw-border-emerald-200 tw-text-emerald-700' : 'tw-bg-slate-50 tw-border-slate-200 tw-text-slate-700' }}">
                                {{ $firm->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>

                        <div class="tw-px-5 tw-py-4">
                            <div class="tw-space-y-2">
                                <div>
                                    <p class="tw-text-[11px] tw-font-bold tw-uppercase tw-tracking-widest tw-text-slate-500">Name</p>
                                    <p class="tw-mt-1 tw-text-base tw-font-extrabold tw-text-slate-900">{{ $firm->name }}</p>
                                    <p class="tw-text-xs tw-text-slate-500">{{ $firm->slug }}</p>
                                </div>

                                <div class="tw-grid tw-grid-cols-2 tw-gap-3">
                                    <div>
                                        <p class="tw-text-[11px] tw-font-bold tw-uppercase tw-tracking-widest tw-text-slate-500">Category</p>
                                        <p class="tw-text-sm tw-font-extrabold tw-text-slate-900">{{ $firm->category?->name ?? '—' }}</p>
                                    </div>
                                    <div>
                                        <p class="tw-text-[11px] tw-font-bold tw-uppercase tw-tracking-widest tw-text-slate-500">Trust</p>
                                        <p class="tw-text-sm tw-font-extrabold tw-text-slate-900">{{ $firm->trust_score ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="tw-flex tw-items-center tw-gap-2 tw-flex-wrap">
                                    <span class="tw-inline-flex tw-items-center tw-rounded-full tw-bg-amber-50 tw-border tw-border-amber-200 tw-px-2 tw-py-0.5 tw-text-[11px] tw-font-extrabold tw-text-amber-700">
                                        Featured: {{ $firm->is_featured ? 'Yes' : 'No' }}
                                    </span>
                                    <span class="tw-inline-flex tw-items-center tw-rounded-full tw-bg-indigo-50 tw-border tw-border-indigo-200 tw-px-2 tw-py-0.5 tw-text-[11px] tw-font-extrabold tw-text-indigo-700">
                                        Verified: {{ $firm->is_verified ? 'Yes' : 'No' }}
                                    </span>
                                </div>

                                <div>
                                    <p class="tw-text-[11px] tw-font-bold tw-uppercase tw-tracking-widest tw-text-slate-500">Created</p>
                                    <p class="tw-mt-1 tw-text-sm tw-font-extrabold tw-text-slate-900">{{ $firm->created_at?->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="tw-px-5 tw-pb-5">
                            <div class="tw-flex tw-items-center tw-justify-end tw-gap-2 tw-flex-wrap">
                                <a href="{{ route('admin_prop_firms_edit', $firm->id) }}"
                                   class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-white tw-border tw-border-slate-200 tw-text-slate-800 tw-px-4 tw-py-2.5 tw-text-sm tw-font-extrabold hover:tw-bg-slate-50">
                                    <i class="fas fa-edit tw-text-brand"></i>
                                    Edit
                                </a>

                                <form action="{{ route('admin_prop_firms_delete', $firm->id) }}" method="POST" onsubmit="return confirm('Delete this prop firm?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-rose-50 tw-border tw-border-rose-200 tw-text-rose-700 tw-px-4 tw-py-2.5 tw-text-sm tw-font-extrabold hover:tw-bg-rose-100">
                                        <i class="fas fa-trash"></i>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="tw-mt-8">
                {{ $propFirms->links() }}
            </div>
        @endif
    </form>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('check-all')?.addEventListener('change', function () {
    document.querySelectorAll('.row-check').forEach(cb => cb.checked = this.checked);
});
</script>
@endpush
