<x-admin.page-header title="User Profile" description='Table: <code>users</code>' />

<div class="bc-card">
    <div class="bc-card-header"><h3>User Profile (users table)</h3></div>
    <div class="bc-card-body">
        <div class="form-grid">
            <div class="form-group">
                <label for="name">name</label>
                <input class="form-control-bc" type="text" id="name" value="{{ old('name', $user->name) }}" readonly>
            </div>
            <div class="form-group">
                <label for="email">email</label>
                <input class="form-control-bc" type="email" id="email" value="{{ old('email', $user->email) }}" readonly>
            </div>
            <div class="form-group">
                <label for="country">country</label>
                <input class="form-control-bc" type="text" id="country" value="{{ old('country', $user->country) }}" readonly>
            </div>
            <div class="form-group">
                <label for="avatar">avatar</label>
                <input class="form-control-bc" type="text" id="avatar" value="{{ old('avatar', $user->avatar) }}" readonly>
            </div>
            <div class="form-group">
                <label for="status">status</label>
                <select class="form-control-bc" id="status" disabled>
                    <option value="active" @selected(($user->status ?? 'active') === 'active')>active</option>
                    <option value="suspended" @selected(in_array($user->status ?? '', ['suspended', 'banned'], true))>suspended</option>
                </select>
            </div>
            <div class="form-group">
                <label for="verified_at">verified_at</label>
                <input class="form-control-bc" type="datetime-local" id="verified_at" value="{{ old('verified_at', optional($user->verified_at)->format('Y-m-d\TH:i')) }}" readonly>
            </div>
            <div class="form-group">
                <label for="email_verified_at">email_verified_at</label>
                <input class="form-control-bc" type="datetime-local" id="email_verified_at" value="{{ old('email_verified_at', optional($user->email_verified_at)->format('Y-m-d\TH:i')) }}" readonly>
            </div>
        </div>
        <div class="form-group">
            <label for="bio">bio</label>
            <textarea class="form-control-bc" id="bio" rows="2" readonly>{{ old('bio', $user->bio) }}</textarea>
        </div>
        <div class="form-check-bc">
            <input type="checkbox" id="is_verified" @checked($user->is_verified) disabled>
            <label for="is_verified">is_verified</label>
        </div>
        <div class="mt-3">
            <a href="{{ route('admin_users_index') }}" class="btn-bc btn-bc-outline btn-bc-sm">Back to Users</a>
            @if($user->is_verified)
                <form action="{{ route('admin_users_unverify', $user->id) }}" method="POST" class="d-inline">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn-bc btn-bc-primary btn-bc-sm">Unverify</button>
                </form>
            @else
                <form action="{{ route('admin_users_verify', $user->id) }}" method="POST" class="d-inline">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn-bc btn-bc-primary btn-bc-sm">Verify User</button>
                </form>
            @endif
        </div>
    </div>
</div>

@if(isset($reviews) && $reviews->count())
<div class="bc-card mt-3">
    <div class="bc-card-header"><h3>User Reviews</h3></div>
    <div class="bc-card-body">
        @foreach($reviews as $review)
            <div class="border-bottom pb-2 mb-2">
                <strong>{{ $review->broker->name ?? 'Broker #' . $review->broker_id }}</strong>
                <small class="text-muted d-block">{{ $review->description }}</small>
            </div>
        @endforeach
    </div>
</div>
@endif
