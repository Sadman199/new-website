@extends('admin.layout.app')

@section('heading', 'Add Account Option — ' . $broker->name)

@section('button')
    <a href="{{ route('admin_account_options_index', $broker->id) }}" class="btn btn-primary">
        <i class="fas fa-arrow-left"></i> Back to Account Options
    </a>
@endsection

@section('main_content')
<div class="section-body">
    <div class="card shadow">
        <div class="card-header bg-white">
            <h4 class="mb-0">New Account Option</h4>
            <small class="text-muted">{{ $broker->name }}</small>
        </div>
        <div class="card-body">
            @include('admin.brokers._tabs', ['broker' => $broker, 'activeTab' => 'account-options'])

            <form action="{{ route('admin_account_options_store', $broker->id) }}" method="POST">
                @csrf
                @include('admin.account_options._form', ['broker' => $broker, 'formOptions' => $formOptions])
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    #account-option-accordion .card-header .btn-link { font-weight: 600; text-decoration: none; color: #34395e; }
    #account-option-accordion .card-header .btn-link:hover { color: #6777ef; }
    #account-option-accordion .card { margin-bottom: .5rem; border: 1px solid #e4e6fc; }
</style>
@endpush
