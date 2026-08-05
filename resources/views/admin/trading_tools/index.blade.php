@extends('admin.layout.app')

@section('heading', 'Trading Tools')

@section('main_content')
<div class="section-body py-4">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Tools shown on the Trading Tools dashboard</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>Order</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Icon</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tools as $tool)
                            <tr>
                                <td>{{ $tool->sort_order }}</td>
                                <td>
                                    <strong>{{ $tool->name }}</strong>
                                    <div class="small text-muted">{{ $tool->short_description }}</div>
                                </td>
                                <td><code>{{ $tool->slug }}</code></td>
                                <td><i class="{{ $tool->icon }}"></i> {{ $tool->icon }}</td>
                                <td>
                                    @if($tool->is_active)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-secondary">Hidden</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin_trading_tools_edit', $tool->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                                    <a href="{{ route('admin_trading_tools_toggle', $tool->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-power-off"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <p class="text-muted small mb-0">Calculators are built into the site. Use this panel to rename, reorder, or hide tools on the public dashboard.</p>
            </div>
        </div>
    </div>
</div>
@endsection
