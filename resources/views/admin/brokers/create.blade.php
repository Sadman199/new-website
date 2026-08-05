@extends('admin.layout.app')

@section('heading', 'Add Broker')

@section('button')
    <a href="{{ route('admin_broker_show') }}" class="btn btn-primary">
        <i class="fas fa-arrow-left"></i> Back to Brokers
    </a>
@endsection

@section('main_content')
<div class="section-body">
    <div class="card shadow">
        <div class="card-header bg-white">
            <h4 class="mb-0">Add New Broker</h4>
            <small class="text-muted">Complete all four sections: Profile &amp; SEO, Classification &amp; Regions, Trading &amp; Payments, Safety &amp; Review.</small>
        </div>
        <div class="card-body">
            <form action="{{ route('admin_broker_store') }}" method="POST" enctype="multipart/form-data">
                @csrf
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
    .broker-form-section-title { font-size: .95rem; letter-spacing: .01em; }
</style>
@endpush
