@extends('admin.layout.app')

@section('heading', 'Add Author')

@section('button')
<a href="{{ route('admin_author_show') }}" class="btn btn-primary"><i class="fas fa-eye"></i> View</a>
@endsection

@section('main_content')
<div class="section-body py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Create New Author</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin_author_store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Photo</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="photo" name="photo">
                                            <label class="custom-file-label" for="photo">Choose file</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="font-weight-bold">Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Enter author name" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="font-weight-bold">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Enter email address" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" name="password" placeholder="Enter password" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="font-weight-bold">Retype Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" name="retype_password" placeholder="Retype password" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="font-weight-bold">Bio</label>
                                        <textarea class="form-control" name="bio" rows="3" placeholder="Short author bio (optional)">{{ old('bio') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            @include('admin.authors._role_fields', ['author' => null])
                            <!-- Submit Button -->
                            <div class="form-group text-center mt-4">
                                <button type="submit" class="btn btn-primary btn-lg px-5">Create Author</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection