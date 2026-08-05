<x-admin.page-header title="Activity Logs" description='Table: <code>activity_logs</code> — FK: user_id → users' />

<div class="bc-card">
    <div class="filters-bar">
        <select name="action">
            <option value="">All actions</option>
            <option value="login" @selected(request('action') === 'login')>login</option>
            <option value="review_submitted" @selected(request('action') === 'review_submitted')>review_submitted</option>
            <option value="verified_by_admin" @selected(request('action') === 'verified_by_admin')>verified_by_admin</option>
        </select>
        <input type="search" name="user_id" value="{{ request('user_id') }}" placeholder="Filter by user_id…">
    </div>
    <div class="table-wrap">
        <table class="bc-table">
            <thead>
                <tr>
                    <th>user_id</th>
                    <th>action</th>
                    <th>description</th>
                    <th>ip_address</th>
                    <th>user_agent</th>
                    <th>created_at</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td>{{ $log->user_id ?? 'NULL' }}</td>
                        <td>{{ $log->action }}</td>
                        <td>{{ $log->description ?? '—' }}</td>
                        <td>{{ $log->ip_address ?? '—' }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($log->user_agent ?? '—', 40) }}</td>
                        <td>{{ $log->created_at ? $log->created_at->format('M j, Y') : '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-muted text-center">No activity logs found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($logs instanceof \Illuminate\Pagination\AbstractPaginator && $logs->hasPages())
        <div class="bc-pagination">{{ $logs->links() }}</div>
    @endif
</div>
