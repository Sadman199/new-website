@extends('admin.layout.app')

@section('heading', 'Add FAQ')

@section('button')
<a href="{{ route('admin_faq_show') }}" class="btn btn-primary"><i class="fas fa-eye"></i> View</a>
@endsection

@section('main_content')
<div class="section-body py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Create New FAQ</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin_faq_store') }}" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label class="font-weight-bold">FAQ Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="faq_title" placeholder="Enter FAQ title" required>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">FAQ Detail <span class="text-danger">*</span></label>
                                <textarea name="faq_detail" class="form-control snote" cols="30" rows="8" placeholder="Enter FAQ detail" required></textarea>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold" for="broker_id">Select Broker</label>
                                <select class="form-control custom-select" name="broker_id" id="broker_id" required>
                                    @foreach ($brokers as $broker)
                                        <option value="{{ $broker->id }}">{{ $broker->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            @include('admin.partials.language_id_field')

                            <div class="form-group text-center mt-4">
                                <button type="submit" class="btn btn-primary btn-lg px-5">Create FAQ</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
