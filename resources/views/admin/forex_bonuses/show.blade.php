@extends('admin.layout.app')

@section('heading', 'Deposit Bonus Management')

@section('button')
<a href="{{ route('admin_forex_bonus_create') }}" class="btn btn-primary btn-lg">
    <i class="fas fa-plus-circle"></i> Add New Bonus
</a>
@endsection

@section('main_content')
<div class="section-body">
    <div class="card shadow">
        <div class="card-header bg-white">
            <h4 class="mb-0">Forex Bonus List</h4>
        </div>
        <div class="card-body">
            @if($forexBonuses->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th width="5%">#</th>
                            <th width="20%">Title</th>
                            <th width="15%">Image</th>
                            <th width="15%">Publish Date</th>
                            <th width="15%">Author</th>
                            <th width="15%">Promo Type</th>
                            <th width="15%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($forexBonuses as $forexBonus)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $forexBonus->title }}</strong>
                                @if($forexBonus->is_featured)
                                <span class="badge badge-warning ml-2">Featured</span>
                                @endif
                            </td>
                            <td>
                                @if ($forexBonus->feature_image)
                                <img src="{{ asset($forexBonus->feature_image) }}" 
                                     alt="Feature Image"
                                     class="img-thumbnail"
                                     style="width:100px; height:auto;">
                                @else
                                <span class="text-muted">No image</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-light">
                                    {{ \Carbon\Carbon::parse($forexBonus->publish_date)->format('d M Y') }}
                                </span>
                            </td>
                            <td>{{ $forexBonus->author_name }}</td>
                            <td>
                                <span class="badge badge-info">
                                    {{ $forexBonus->promo_type }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin_forex_bonus_edit', $forexBonus->id) }}"
                                       class="btn btn-sm btn-primary"
                                       data-toggle="tooltip"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <form action="{{ route('admin_forex_bonus_delete', $forexBonus->id) }}" 
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this Forex Bonus?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-danger"
                                                data-toggle="tooltip"
                                                title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                    
                                    <a href="#" 
                                       class="btn btn-sm btn-secondary"
                                       data-toggle="tooltip"
                                       title="Preview">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Showing {{ $forexBonuses->firstItem() }} to {{ $forexBonuses->lastItem() }} 
                    of {{ $forexBonuses->total() }} entries
                </div>
                <nav>
                 {{ $forexBonuses->links() }}
                </nav>
            </div>

            @else
            <div class="alert alert-info alert-dismissible fade show">
                <i class="fas fa-info-circle mr-2"></i>
                No Forex Bonus posts found. Would you like to 
                <a href="{{ route('admin_forex_bonus_create') }}" class="alert-link">create one</a>?
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Enable tooltips
        $('[data-toggle="tooltip"]').tooltip();
        
        // Add some interactive features
        $('.table tbody tr').hover(
            function() {
                $(this).addClass('table-active');
            },
            function() {
                $(this).removeClass('table-active');
            }
        );
    });
</script>
@endpush