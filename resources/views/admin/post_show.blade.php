@extends('admin.layout.app')

@section('heading', 'Posts')

@section('button')
<a href="{{ route('admin_post_create') }}" class="btn btn-primary">
    <i class="fas fa-plus"></i> Add New
</a>
@endsection

@section('main_content')
<div class="section-body py-4">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">All Blog Posts</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th class="text-center" style="width: 5%;">#</th>
                                        <th class="text-center" style="width: 10%;">Thumbnail</th>
                                        <th style="width: 25%;">Title</th>
                                        <th class="text-center" style="width: 15%;">Sub Category</th>
                                        <th class="text-center" style="width: 15%;">Category</th>
                                        <th class="text-center" style="width: 10%;">Author</th>
                                        <th class="text-center" style="width: 10%;">Admin</th>
                                        <th class="text-center" style="width: 10%;">Language</th>
                                        <th class="text-center" style="width: 15%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($posts as $row)
                                    <tr>
                                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                        <td class="text-center align-middle">
                                            <img src="{{ asset('uploads/' . $row->post_photo) }}" alt="Thumbnail" class="img-thumbnail" style="max-width: 70px; max-height: 70px;">
                                        </td>
                                        <td class="align-middle">
                                            <strong>{{ Str::limit($row->post_title, 60) }}</strong>
                                        </td>
                                        <td class="text-center align-middle">
                                            {{ optional($row->rSubCategory)->sub_category_name ?? 'N/A' }}
                                        </td>
                                        <td class="text-center align-middle">
                                            {{ optional(optional($row->rSubCategory)->rCategory)->category_name ?? 'N/A' }}
                                        </td>
                                        <td class="text-center align-middle">
                                            @if($row->author_id != 0)
                                                {{ \App\Models\Author::where('id', $row->author_id)->value('name') ?? 'N/A' }}
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            @if($row->admin_id != 0)
                                                {{ Auth::guard('admin')->user()->name ?? 'N/A' }}
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            {{ $row->rLanguage->name ?? 'N/A' }}
                                        </td>
                                        <td class="text-center align-middle">
                                            @if($row->admin_id != 0)
                                                <div class="d-inline-flex">
                                                    <a href="{{ route('admin_post_edit', $row->id) }}" class="btn btn-sm btn-outline-primary mr-1" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="{{ route('admin_post_delete', $row->id) }}" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this post?');">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            @else
                                                <span class="badge badge-secondary">No Actions</span>
                                            @endif
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
