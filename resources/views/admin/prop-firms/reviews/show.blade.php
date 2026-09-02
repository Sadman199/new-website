@extends('admin.layout.app')
@include('admin.brokers._assets')
@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')
@section('heading', 'Prop Firm Reviews')
@section('main_content')
<div class="ab-page"><div class="ab-wrap">
    <header class="ab-header">
        <div><p class="ab-header__eyebrow">Prop firms</p><h1 class="ab-header__title">Reviews</h1><p class="ab-header__sub">Moderate user and editor reviews.</p></div>
        <div class="ab-header__actions"><a href="{{ route('admin_prop_firm_reviews_create') }}" class="ab-btn ab-btn--primary"><i class="fas fa-plus" aria-hidden="true"></i> Add review</a></div>
    </header>
    @include('admin.prop-firms._nav', ['active' => 'reviews'])
    <div class="ab-panel">
        <form method="GET" class="ab-filters">
            <div class="ab-field"><label for="ab-q">Search</label><input id="ab-q" class="ab-input" type="search" name="q" value="{{ request('q') }}" placeholder="Title or author…"></div>
            <div class="ab-field">
                <label for="ab-firm">Firm</label>
                <select id="ab-firm" class="ab-select" name="prop_firm_id">
                    <option value="">All firms</option>
                    @foreach($propFirms as $firm)
                        <option value="{{ $firm->id }}" @selected(request('prop_firm_id') == $firm->id)>{{ $firm->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="ab-field">
                <label for="ab-status">Status</label>
                <select id="ab-status" class="ab-select" name="status">
                    <option value="">All statuses</option>
                    @foreach(['pending','approved','rejected'] as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="ab-header__actions">
                <button type="submit" class="ab-btn ab-btn--primary">Filter</button>
                @if(request()->filled('q') || request()->filled('prop_firm_id') || request()->filled('status'))
                    <a href="{{ route('admin_prop_firm_reviews_show') }}" class="ab-btn ab-btn--ghost">Reset</a>
                @endif
            </div>
        </form>
        @if($reviews->isEmpty())
            <div class="ab-empty"><h3>No reviews found</h3><p>Add a review or wait for submissions from the public site.</p><a href="{{ route('admin_prop_firm_reviews_create') }}" class="ab-btn ab-btn--primary">Add review</a></div>
        @else
            <div class="ab-table-wrap">
                <table class="ab-table">
                    <thead><tr><th>Title</th><th>Firm</th><th>Rating</th><th>Author</th><th></th></tr></thead>
                    <tbody>
                        @foreach($reviews as $review)
                            <tr>
                                <td>
                                    <p class="ab-broker__name">{{ \Illuminate\Support\Str::limit($review->title, 60) }}</p>
                                    <div class="ab-pills">
                                        @if($review->status === 'approved')<span class="ab-pill ab-pill--ok">Approved</span>
                                        @elseif($review->status === 'rejected')<span class="ab-pill ab-pill--danger">Rejected</span>
                                        @else<span class="ab-pill ab-pill--warn">Pending</span>@endif
                                    </div>
                                </td>
                                <td>{{ $review->propFirm?->name ?? '—' }}</td>
                                <td>{{ number_format((float) $review->rating, 1) }}</td>
                                <td>{{ $review->author ?? '—' }}</td>
                                <td>
                                    <div class="ab-actions">
                                        <a class="ab-btn ab-btn--ghost ab-btn--sm" href="{{ route('admin_prop_firm_reviews_edit', $review->id) }}">Edit</a>
                                        <form action="{{ route('admin_prop_firm_reviews_delete', $review->id) }}" method="POST" data-ab-delete data-ab-name="{{ $review->title }}" data-ab-warn="This review will be permanently removed." data-ab-confirm="Delete review">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="ab-btn ab-btn--danger ab-btn--sm">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="ab-foot">{{ $reviews->links('pagination::bootstrap-4') }}</div>
        @endif
    </div>
</div></div>
@endsection
