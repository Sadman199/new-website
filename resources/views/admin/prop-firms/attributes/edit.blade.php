@extends('admin.layout.app')
@section('heading', 'Edit Attribute')
@section('button')<a href="{{ route('admin_prop_firm_attributes_show') }}" class="btn btn-primary">Back</a>@endsection
@section('main_content')
<div class="section-body"><div class="card shadow"><div class="card-body"><form action="{{ route('admin_prop_firm_attributes_update', $attribute->id) }}" method="POST">@csrf @method('PUT') @include('admin.prop-firms.attributes._form')</form></div></div></div>
@endsection
