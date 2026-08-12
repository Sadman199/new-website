@extends('admin.layout.app')

@section('heading', 'Edit Broker — ' . $broker->name)

@section('button')
    <a href="{{ route('admin_broker_show') }}" class="btn btn-primary">
        <i class="fas fa-arrow-left"></i> Back to Brokers
    </a>
@endsection

@section('main_content')
<div class="tw-max-w-6xl tw-mx-auto tw-px-4 tw-py-6">
    <div class="tw-bg-white tw-rounded-2xl tw-border tw-border-slate-200/70 tw-shadow-sm tw-overflow-hidden">
        <div class="tw-px-6 tw-py-5 tw-border-b tw-border-slate-100 tw-flex tw-items-start tw-justify-between tw-gap-4">
            <div>
                <h2 class="tw-text-lg tw-font-extrabold tw-text-slate-900">Edit Broker</h2>
                <p class="tw-mt-1 tw-text-sm tw-text-slate-600 tw-flex tw-items-center tw-gap-2">
                    <span class="tw-inline-flex tw-items-center tw-gap-2 tw-px-2 tw-py-1 tw-rounded-lg tw-bg-slate-50 tw-border tw-border-slate-200">
                        <i class="fas fa-link tw-text-slate-400"></i>
                        <span class="tw-truncate">{{ $broker->slug }}</span>
                    </span>
                </p>
            </div>

            @if($broker->rating)
                <span class="tw-inline-flex tw-items-center tw-gap-2 tw-h-9 tw-px-4 tw-rounded-full tw-bg-amber-50 tw-border tw-border-amber-200 tw-text-amber-700 tw-font-bold tw-text-sm">
                    <i class="fas fa-star"></i>
                    {{ number_format($broker->rating, 1) }}/5
                </span>
            @endif
        </div>

        <div class="tw-px-6 tw-py-6">
            @include('admin.brokers._tabs', ['broker' => $broker->loadCount('accountOptions'), 'activeTab' => 'broker'])

            <form action="{{ route('admin_broker_update', $broker->id) }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="tw-space-y-6">
                @csrf
                @method('PUT')
                @include('admin.brokers._form', ['broker' => $broker, 'formOptions' => $formOptions])
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Legacy styles kept disabled; the broker form uses Tailwind <details> sections now. */
</style>
@endpush
