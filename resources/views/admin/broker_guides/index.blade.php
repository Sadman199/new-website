@extends('admin.layout.app')

@section('heading', 'Broker Guides — ' . $broker->name)

@section('button')
    <a href="{{ route('admin_broker_show') }}" class="btn btn-primary">
        <i class="fas fa-arrow-left"></i> Back to Brokers
    </a>
    <a href="{{ route('admin_broker_guide_topics_index') }}" class="btn btn-outline-secondary ml-2">
        <i class="fas fa-cog"></i> Manage topics
    </a>
@endsection

@section('main_content')
<div class="section-body">
    <div class="card shadow">
        <div class="card-header bg-white">
            <h4 class="mb-0">Broker Guides</h4>
            <small class="text-muted">{{ $broker->name }} · {{ $broker->slug }}</small>
        </div>
        <div class="card-body">
            @include('admin.brokers._tabs', ['broker' => $broker, 'activeTab' => 'guides'])

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <p class="text-muted mb-4">
                Edit per-broker content for each guide topic. Publish individually when ready.
            </p>

            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Topic</th>
                            <th>Status</th>
                            <th>Summary</th>
                            <th>Updated</th>
                            <th width="120">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($guides as $guide)
                            @php
                                $topic = $guide->topic;
                                $requiresSwapFree = $topic?->requires_swap_free;
                                $hasSwapFree = $broker->accountOptions->contains(fn ($o) => (bool) $o->swap_free);
                            @endphp
                            <tr>
                                <td>{{ $topic?->sort_order ?? $guide->sort_order ?: $loop->iteration }}</td>
                                <td>
                                    @if($topic?->icon)
                                        <i class="{{ $topic->icon }} text-muted mr-1"></i>
                                    @endif
                                    <strong>{{ $guide->title }}</strong>
                                    @if($requiresSwapFree && ! $hasSwapFree)
                                        <span class="badge badge-warning ml-1">Needs swap-free account</span>
                                    @endif
                                    @if($topic && ! $topic->is_active)
                                        <span class="badge badge-secondary ml-1">Topic inactive</span>
                                    @endif
                                </td>
                                <td>
                                    @if($guide->status === 'published')
                                        <span class="badge badge-success">Published</span>
                                    @elseif($guide->status === 'hidden')
                                        <span class="badge badge-secondary">Hidden</span>
                                    @else
                                        <span class="badge badge-light">Draft</span>
                                    @endif
                                </td>
                                <td class="small text-muted">{{ Str::limit($guide->summary, 80) ?: '—' }}</td>
                                <td>{{ $guide->updated_at?->format('M j, Y') ?? '—' }}</td>
                                <td>
                                    @if($topic)
                                        <a href="{{ route('admin_broker_guides_edit', [$broker->id, $topic->slug]) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
