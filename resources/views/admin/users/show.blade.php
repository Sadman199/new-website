@extends('admin.layout.app')

@section('heading', 'User Details')

@section('button')
<a href="{{ route('admin_users_index') }}" class="btn btn-secondary btn-lg">
    <i class="fas fa-arrow-left"></i> All Users
</a>
@endsection

@section('main_content')
<div class="section-body">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="row">
        <!-- Profile -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="rounded-circle mb-3" style="width:96px;height:96px;object-fit:cover;">
                    <h4 class="mb-0">{{ $user->name }}</h4>
                    <p class="text-muted mb-2">{{ $user->email }}</p>
                    @if($user->is_verified)
                        <span class="badge badge-info"><i class="fas fa-check-circle"></i> Verified</span>
                    @else
                        <span class="badge badge-warning"><i class="fas fa-clock"></i> Pending</span>
                    @endif
                    @if($user->status === 'banned')<span class="badge badge-danger">Suspended</span>@endif

                    <hr>
                    <ul class="list-unstyled text-left small mb-0">
                        <li class="mb-1"><i class="fas fa-map-marker-alt text-muted mr-2"></i>{{ $user->country ?? 'Not set' }}</li>
                        <li class="mb-1"><i class="fas fa-star text-muted mr-2"></i>{{ $user->reviews_count }} reviews</li>
                        <li class="mb-1"><i class="fas fa-calendar text-muted mr-2"></i>Joined {{ $user->created_at->format('M d, Y') }}</li>
                        <li class="mb-1"><i class="fas fa-sign-in-alt text-muted mr-2"></i>Last login: {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</li>
                        @if($user->last_login_ip)<li class="mb-1"><i class="fas fa-network-wired text-muted mr-2"></i>{{ $user->last_login_ip }}</li>@endif
                    </ul>
                    @if($user->bio)<p class="text-muted small mt-3 text-left">{{ $user->bio }}</p>@endif

                    <div class="mt-3 d-flex justify-content-center flex-wrap" style="gap:.5rem;">
                        @if($user->is_verified)
                            <form action="{{ route('admin_users_unverify', $user->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm btn-warning"><i class="fas fa-user-times"></i> Unverify</button>
                            </form>
                        @else
                            <form action="{{ route('admin_users_verify', $user->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm btn-success"><i class="fas fa-user-check"></i> Verify user</button>
                            </form>
                        @endif
                        <form action="{{ route('admin_users_toggle_status', $user->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button class="btn btn-sm btn-dark">
                                <i class="fas {{ $user->status === 'banned' ? 'fa-unlock' : 'fa-ban' }}"></i>
                                {{ $user->status === 'banned' ? 'Reactivate' : 'Suspend' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews + Activity -->
        <div class="col-md-8">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white"><h5 class="mb-0">Reviews ({{ $reviews->count() }})</h5></div>
                <div class="card-body">
                    @forelse($reviews as $review)
                        <div class="border-bottom pb-2 mb-2">
                            <div class="d-flex justify-content-between">
                                <strong>{{ $review->broker->name ?? 'Broker' }}</strong>
                                <span>
                                    @if($review->status == 1)<span class="badge badge-success">Approved</span>
                                    @elseif($review->status == 0)<span class="badge badge-warning">Pending</span>
                                    @else<span class="badge badge-danger">Declined</span>@endif
                                </span>
                            </div>
                            <div class="text-warning small">
                                @for($i=1;$i<=5;$i++)<i class="fas fa-star {{ $i <= $review->rating ? '' : 'text-muted' }}"></i>@endfor
                                <span class="text-muted ml-1">{{ $review->created_at->format('M d, Y') }}</span>
                            </div>
                            <p class="small text-muted mb-0">{{ $review->description }}</p>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No reviews submitted.</p>
                    @endforelse
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white"><h5 class="mb-0">Activity log</h5></div>
                <div class="card-body">
                    @forelse($activities as $activity)
                        <div class="d-flex align-items-start border-bottom py-2">
                            <i class="fas fa-circle text-primary mr-2" style="font-size:8px;margin-top:6px;"></i>
                            <div>
                                <div class="font-weight-bold small">{{ $activity->label }}</div>
                                @if($activity->description)<div class="small text-muted">{{ $activity->description }}</div>@endif
                                <div class="small text-muted">
                                    {{ $activity->created_at->format('M d, Y H:i') }}
                                    @if($activity->ip_address)· {{ $activity->ip_address }}@endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No activity recorded.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
