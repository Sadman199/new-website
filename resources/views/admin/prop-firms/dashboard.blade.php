@extends('admin.layout.app')

@section('heading', 'Prop Firms Dashboard')

@section('button')
<a href="{{ route('admin_prop_firms_create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add Prop Firm</a>
@endsection

@section('main_content')
<div class="section-body">
    <div class="row">
        @foreach([
            ['label' => 'Total Firms', 'value' => $stats['total'], 'icon' => 'fa-building', 'color' => 'primary'],
            ['label' => 'Active', 'value' => $stats['active'], 'icon' => 'fa-check-circle', 'color' => 'success'],
            ['label' => 'Featured', 'value' => $stats['featured'], 'icon' => 'fa-star', 'color' => 'warning'],
            ['label' => 'Verified', 'value' => $stats['verified'], 'icon' => 'fa-shield-alt', 'color' => 'info'],
            ['label' => 'Programs', 'value' => $stats['programs'], 'icon' => 'fa-layer-group', 'color' => 'secondary'],
            ['label' => 'Reviews', 'value' => $stats['reviews'], 'icon' => 'fa-comments', 'color' => 'dark'],
            ['label' => 'FAQs', 'value' => $stats['faqs'], 'icon' => 'fa-question-circle', 'color' => 'primary'],
            ['label' => 'Categories', 'value' => $stats['categories'], 'icon' => 'fa-tags', 'color' => 'info'],
        ] as $card)
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card card-statistic-1">
                <div class="card-icon bg-{{ $card['color'] }}"><i class="fas {{ $card['icon'] }}"></i></div>
                <div class="card-wrap">
                    <div class="card-header"><h4>{{ $card['label'] }}</h4></div>
                    <div class="card-body">{{ number_format($card['value']) }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="card shadow">
        <div class="card-header bg-white"><h4 class="mb-0">Recently Added</h4></div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Trust</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent as $firm)
                        <tr>
                            <td><strong>{{ $firm->name }}</strong></td>
                            <td>{{ $firm->category?->name ?? '—' }}</td>
                            <td>{{ $firm->trust_score ?? '—' }}</td>
                            <td>
                                @if($firm->is_active)<span class="badge badge-success">Active</span>@else<span class="badge badge-secondary">Inactive</span>@endif
                            </td>
                            <td>{{ $firm->created_at?->format('M d, Y') }}</td>
                            <td><a href="{{ route('admin_prop_firms_edit', $firm->id) }}" class="btn btn-sm btn-outline-primary">Edit</a></td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">No prop firms yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
