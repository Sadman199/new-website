@extends('admin.layout.app')

@section('heading', 'Authors')

@section('button')
<a href="{{ route('admin_author_create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add New</a>
@endsection

@section('main_content')
<div class="section-body py-4">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Author List</h5>
                        <a href="{{ route('admin_author_create') }}" class="btn btn-light btn-sm"><i class="fas fa-plus"></i> Add New</a>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">
                            Assign editorial roles to authors. Authors with <strong>Written</strong>, <strong>Edited</strong>, or <strong>Fact-Checked</strong>
                            capabilities can be selected when publishing posts.
                        </p>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="example1">
                                <thead class="thead-dark">
                                    <tr>
                                        <th class="text-center" style="width: 4%">#</th>
                                        <th class="text-center" style="width: 8%">Photo</th>
                                        <th style="width: 18%">Name</th>
                                        <th style="width: 20%">Email</th>
                                        <th class="text-center" style="width: 18%">Roles</th>
                                        <th class="text-center" style="width: 18%">Contributions</th>
                                        <th class="text-center" style="width: 14%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($authors as $row)
                                    <tr>
                                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                        <td class="text-center align-middle">
                                            <img src="{{ $row->photoUrl() }}" alt="{{ $row->name }}" class="img-fluid rounded" style="max-width: 60px; max-height: 60px; object-fit: cover;">
                                        </td>
                                        <td class="align-middle">
                                            <strong>{{ $row->name }}</strong>
                                            @if($row->bio)
                                                <br><small class="text-muted">{{ Str::limit($row->bio, 60) }}</small>
                                            @endif
                                        </td>
                                        <td class="align-middle">{{ $row->email }}</td>
                                        <td class="text-center align-middle">
                                            @if($row->can_write)
                                                <span class="badge badge-primary mb-1">Written</span>
                                            @endif
                                            @if($row->can_edit)
                                                <span class="badge badge-info mb-1">Edited</span>
                                            @endif
                                            @if($row->can_fact_check)
                                                <span class="badge badge-success mb-1">Fact-Checked</span>
                                            @endif
                                            @if(! $row->can_write && ! $row->can_edit && ! $row->can_fact_check)
                                                <span class="text-muted">No roles</span>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle small">
                                            <div>Written: {{ $row->written_posts_count ?? 0 }}</div>
                                            <div>Edited: {{ $row->edited_posts_count ?? 0 }}</div>
                                            <div>Fact-Checked: {{ $row->fact_checked_posts_count ?? 0 }}</div>
                                        </td>
                                        <td class="text-center align-middle">
                                            <a href="{{ route('admin_author_edit', $row->id) }}" class="btn btn-primary btn-sm mr-1" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin_author_delete', $row->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this author? Editorial credits on posts will be cleared.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">No authors found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
