@php
    $counts = $counts ?? ['total' => 0, 'verified' => 0, 'active_month' => 0];
@endphp

<x-admin.page-header title="Users" description="Registered frontend users who can submit reviews." />

<div class="stat-grid" style="grid-template-columns: repeat(3, 1fr); margin-bottom:1rem;">
    <div class="stat-card">
        <div class="stat-value">{{ $counts['total'] ?? 0 }}</div>
        <div class="stat-label">Total Users</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $counts['verified'] ?? 0 }}</div>
        <div class="stat-label">Verified</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $counts['active_month'] ?? 0 }}</div>
        <div class="stat-label">Active This Month</div>
    </div>
</div>

<div class="bc-card">
    <div class="table-wrap">
        <table class="bc-table">
            <thead>
                <tr>
                    <th>name</th>
                    <th>email</th>
                    <th>country</th>
                    <th>is_verified</th>
                    <th>status</th>
                    <th>last_login_at</th>
                    <th>last_login_ip</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->country ?? '—' }}</td>
                        <td>
                            <span class="bc-badge {{ $user->is_verified ? 'bc-badge-success' : 'bc-badge-muted' }}">
                                {{ $user->is_verified ? '1' : '0' }}
                            </span>
                        </td>
                        <td>{{ $user->status ?? 'active' }}</td>
                        <td>{{ $user->last_login_at ? $user->last_login_at->format('M j, Y') : '—' }}</td>
                        <td>{{ $user->last_login_ip ?? '—' }}</td>
                        <td>
                            <a href="{{ route('admin_users_show', $user->id) }}" class="btn-bc btn-bc-ghost btn-bc-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-muted text-center">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users instanceof \Illuminate\Pagination\AbstractPaginator && $users->hasPages())
        <div class="bc-pagination">{{ $users->links() }}</div>
    @endif
</div>
