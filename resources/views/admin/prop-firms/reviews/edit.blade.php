@extends('admin.layout.app')
@section('heading', 'Edit Review')
@section('button')<a href="{{ route('admin_prop_firm_reviews_show') }}" class="btn btn-primary">Back</a>@endsection
@section('main_content')
<div class="section-body"><div class="card shadow"><div class="card-body"><form action="{{ route('admin_prop_firm_reviews_update', $review->id) }}" method="POST">@csrf @method('PUT') @include('admin.prop-firms.reviews._form')</form></div></div></div>
@endsection
