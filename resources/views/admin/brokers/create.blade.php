@extends('admin.layout.app')

@section('heading', 'Add Broker')

@section('button')
    <a href="{{ route('admin_broker_show') }}" class="btn btn-primary">
        <i class="fas fa-arrow-left"></i> Back to Brokers
    </a>
@endsection

@section('main_content')
<div class="tw-max-w-6xl tw-mx-auto tw-px-4 tw-py-6">
    <div class="tw-bg-white tw-rounded-2xl tw-border tw-border-slate-200/70 tw-shadow-sm tw-overflow-hidden">
        <div class="tw-flex tw-items-start tw-justify-between tw-gap-4 tw-px-6 tw-py-5 tw-border-b tw-border-slate-100">
            <div>
                <h2 class="tw-text-lg tw-font-extrabold tw-text-slate-900">Add New Broker</h2>
                <p class="tw-mt-1 tw-text-sm tw-text-slate-600">
                    Fill the four sections below. Each group includes help text so you know exactly what to enter.
                </p>
            </div>
            <div class="tw-hidden lg:tw-flex tw-items-center tw-gap-2 tw-text-xs tw-font-semibold tw-text-slate-600">
                <span class="tw-inline-flex tw-items-center tw-gap-2 tw-px-3 tw-py-2 tw-rounded-xl tw-bg-slate-50 tw-border tw-border-slate-200">
                    <i class="fas fa-circle-info tw-text-brand"></i>
                    Help text included per field
                </span>
            </div>
        </div>

        <div class="tw-px-6 tw-py-6">
            <form action="{{ route('admin_broker_store') }}" method="POST" enctype="multipart/form-data" class="tw-space-y-6">
                @csrf
                @include('admin.brokers._form', ['broker' => $broker, 'formOptions' => $formOptions])
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Legacy broker accordion styles (no longer used after Tailwind redesign). */
    #broker-accordion { display: block; }
</style>
@endpush
