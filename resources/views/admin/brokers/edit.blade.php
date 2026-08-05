@extends('admin.layout.app')

@section('heading', 'Edit Broker — ' . $broker->name)

@section('button')
    <a href="{{ route('admin_broker_show') }}" class="btn btn-primary">
        <i class="fas fa-arrow-left"></i> Back to Brokers
    </a>
    <a href="{{ route('broker_detail', $broker->slug) }}" class="btn btn-outline-primary ml-2" target="_blank">
        <i class="fas fa-external-link-alt"></i> View on Site
    </a>
@endsection

@section('main_content')
<div class="section-body">
    <div class="card shadow">
        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h4 class="mb-0">Edit Broker</h4>
                <small class="text-muted">{{ $broker->slug }}</small>
            </div>
            @if($broker->rating)
                <span class="badge badge-warning badge-lg">{{ number_format($broker->rating, 1) }}/5</span>
            @endif
        </div>
        <div class="card-body">
            @include('admin.brokers._tabs', ['broker' => $broker->loadCount('accountOptions'), 'activeTab' => 'broker'])

            <form action="{{ route('admin_broker_update', $broker->id) }}" method="POST" enctype="multipart/form-data">
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
    #broker-accordion .card-header .btn-link { font-weight: 600; text-decoration: none; color: #34395e; width: 100%; text-align: left; }
    #broker-accordion .card-header .btn-link:hover { color: #6777ef; }
    #broker-accordion .card { margin-bottom: .75rem; border: 1px solid #e4e6fc; border-radius: .5rem; overflow: hidden; }
    #broker-accordion .card-header { background: #f8f9fe; border-bottom: 1px solid #e4e6fc; }
    #broker-accordion .card-body { background: #fff; }
</style>
@endpush
