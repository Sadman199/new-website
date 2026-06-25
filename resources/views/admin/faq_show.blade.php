@extends('admin.layout.app')

@section('heading', 'FAQs')

@section('button')
<a href="{{ route('admin_faq_create') }}" class="btn btn-primary">
    <i class="fas fa-plus"></i> Add
</a>
@endsection

@section('main_content')
<div class="section-body py-4">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">FAQ List</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="example1">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col" style="width: 5%;">SL</th>
                                        <th scope="col">FAQ Title</th>
                                        <th scope="col">Language</th>
                                        <th scope="col">Broker</th>
                                        <th scope="col" class="text-center" style="width: 20%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($faq_data as $row)
                                    <tr>
                                        <td class="align-middle">{{ $loop->iteration }}</td>
                                        <td class="align-middle">{{ $row->faq_title }}</td>
                                        <td class="align-middle">{{ $row->rLanguage->name ?? 'N/A' }}</td>
                                        <td class="align-middle">{{ $row->broker->name ?? 'N/A' }}</td>
                                        <td class="text-center align-middle">
                                            <div class="d-inline-flex">
                                                <a href="{{ route('admin_faq_edit', $row->id) }}" class="btn btn-sm btn-outline-primary mr-2" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('admin_faq_delete', $row->id) }}" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this FAQ?');">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div> <!-- table-responsive -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
