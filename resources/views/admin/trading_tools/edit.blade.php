@extends('admin.layout.app')

@section('heading', 'Edit Trading Tool')

@section('button')
<a href="{{ route('admin_trading_tools_index') }}" class="btn btn-primary"><i class="fas fa-eye"></i> View All</a>
@endsection

@section('main_content')
<div class="section-body py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Edit: {{ $tool->name }}</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin_trading_tools_update', $tool->id) }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label class="font-weight-bold">Name</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $tool->name) }}" required>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold">Icon (Font Awesome class)</label>
                                <input type="text" name="icon" class="form-control" value="{{ old('icon', $tool->icon) }}">
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold">Short Description</label>
                                <input type="text" name="short_description" class="form-control" value="{{ old('short_description', $tool->short_description) }}">
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold">Description</label>
                                <textarea name="description" class="form-control" rows="3">{{ old('description', $tool->description) }}</textarea>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold">Sort Order</label>
                                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $tool->sort_order) }}" min="0">
                            </div>
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ $tool->is_active ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Active on dashboard</label>
                            </div>
                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-primary btn-lg px-5">Update Tool</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
