@extends('admin.layout.app')

@section('heading', 'Add Prop Firm')

@section('button')
<a href="{{ route('admin_prop_firms_show') }}" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Back</a>
@endsection

@section('main_content')
<div class="section-body">
    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('admin_prop_firms_store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('admin.prop-firms._form')
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@include('admin.prop-firms._form_scripts')
@endpush
