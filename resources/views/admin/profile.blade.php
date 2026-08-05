@extends('admin.layout.app')

@section('heading', 'Edit Profile')

@section('main_content')
<div class="section-body py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Update Admin Profile</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin_profile_submit') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row align-items-start">
                                <!-- Profile Photo -->
                                <div class="col-md-4 text-center mb-4 mb-md-0">
                                    <img src="{{ asset('uploads/' . Auth::guard('admin')->user()->photo) }}" alt="Profile Photo" class="img-fluid rounded shadow-sm mb-3" style="max-width: 200px;">
                                    <div class="custom-file">
                                        <input type="file" class="form-control-file" name="photo" id="photo">
                                        <label class="form-text text-muted small mt-2 d-block">Upload new photo (optional)</label>
                                    </div>
                                </div>

                                <!-- Profile Info -->
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="name" value="{{ Auth::guard('admin')->user()->name }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label class="font-weight-bold">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="email" value="{{ Auth::guard('admin')->user()->email }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label class="font-weight-bold">New Password</label>
                                        <input type="password" class="form-control" name="password" placeholder="Leave blank to keep current">
                                    </div>

                                    <div class="form-group">
                                        <label class="font-weight-bold">Retype Password</label>
                                        <input type="password" class="form-control" name="retype_password" placeholder="Retype new password">
                                    </div>

                                    <div class="form-group text-right mt-4">
                                        <button type="submit" class="btn btn-primary btn-lg px-5">Update Profile</button>
                                    </div>
                                </div>
                            </div> <!-- end row -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
