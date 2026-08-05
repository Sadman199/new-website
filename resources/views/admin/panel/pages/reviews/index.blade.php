@php
    $status = $status ?? 'pending';
    $pendingCount = $pendingCount ?? ($stats['pending_reviews'] ?? 0);
@endphp

<x-admin.page-header title="Reviews &amp; Ratings" description="Moderate user-submitted broker reviews." />

<div class="bc-card">
    <div class="bc-tabs" data-tab-group="reviews">
        <a href="{{ route('reviews.pending', ['status' => 'pending']) }}" class="bc-tab {{ $status === 'pending' ? 'active' : '' }}" data-tab="pending">
            Pending <span class="bc-badge bc-badge-warning">{{ $pendingCount }}</span>
        </a>
        <a href="{{ route('reviews.pending', ['status' => 'approved']) }}" class="bc-tab {{ $status === 'approved' ? 'active' : '' }}" data-tab="approved">
            Approved
        </a>
        <a href="{{ route('reviews.pending', ['status' => 'declined']) }}" class="bc-tab {{ $status === 'declined' ? 'active' : '' }}" data-tab="declined">
            Declined
        </a>
    </div>

    <div class="tab-panel active" data-tab-panel="reviews" data-tab-id="{{ $status }}">
        <div class="table-wrap">
            <table class="bc-table">
                <thead>
                    <tr>
                        <th>user_id</th>
                        <th>broker_id</th>
                        <th>name</th>
                        <th>email</th>
                        <th>country</th>
                        <th>rating</th>
                        <th>description</th>
                        <th>status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                        @php
                            $rating = (int) ($review->rating ?? 0);
                            $reviewStatus = (int) ($review->status ?? 0);
                        @endphp
                        <tr>
                            <td>{{ $review->user_id ?: '—' }}</td>
                            <td>{{ $review->broker_id }}</td>
                            <td>{{ $review->name ?? ($review->user->name ?? '—') }}</td>
                            <td>{{ $review->email ?? ($review->user->email ?? '—') }}</td>
                            <td>{{ $review->country ?? '—' }}</td>
                            <td>
                                <span class="stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $rating)
                                            ★
                                        @else
                                            <span class="empty">★</span>
                                        @endif
                                    @endfor
                                </span>
                                {{ $rating }}
                            </td>
                            <td>{{ \Illuminate\Support\Str::limit($review->description, 60) }}</td>
                            <td>
                                @if($reviewStatus === 1)
                                    <span class="bc-badge bc-badge-success">1 Approved</span>
                                @elseif($reviewStatus === -1)
                                    <span class="bc-badge bc-badge-danger">-1 Declined</span>
                                @else
                                    <span class="bc-badge bc-badge-warning">0 Pending</span>
                                @endif
                            </td>
                            <td>
                                @if($reviewStatus !== 1)
                                    <form action="{{ route('reviews.approve', $review->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn-bc btn-bc-success btn-bc-sm">1 Approve</button>
                                    </form>
                                @endif
                                @if($reviewStatus !== -1)
                                    <form action="{{ route('reviews.decline', $review->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn-bc btn-bc-danger btn-bc-sm">-1 Decline</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-muted text-center">
                                @if($status === 'approved')
                                    No approved reviews.
                                @elseif($status === 'declined')
                                    No declined reviews.
                                @else
                                    No pending reviews.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($reviews instanceof \Illuminate\Pagination\AbstractPaginator && $reviews->hasPages())
            <div class="bc-pagination">{{ $reviews->links() }}</div>
        @endif
    </div>
</div>
