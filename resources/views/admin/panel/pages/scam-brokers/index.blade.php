<x-admin.page-header
    title="Scam Brokers"
    description='Filtered view of <code>brokers</code> where is_scam = 1 (fields: scam_reason, scam_reported_date)'
/>

<div class="bc-card">
    <div class="table-wrap">
        <table class="bc-table">
            <thead>
                <tr>
                    <th>Broker</th>
                    <th>Reason</th>
                    <th>Reported Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($brokers as $broker)
                    @php $initials = strtoupper(substr($broker->name, 0, 2)); @endphp
                    <tr>
                        <td>
                            <div class="broker-cell">
                                <div class="broker-logo" style="border-color:#ef4444">{{ $initials }}</div>
                                <div><strong>{{ $broker->name }}</strong></div>
                            </div>
                        </td>
                        <td>{{ $broker->scam_reason ?: '—' }}</td>
                        <td>{{ $broker->scam_reported_date ? $broker->scam_reported_date->format('M j, Y') : '—' }}</td>
                        <td><span class="bc-badge bc-badge-danger">Scam</span></td>
                        <td>
                            <a href="{{ route('admin_broker_edit', $broker->id) }}" class="btn-bc btn-bc-ghost btn-bc-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-muted text-center">No scam brokers flagged.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($brokers instanceof \Illuminate\Pagination\AbstractPaginator && $brokers->hasPages())
        <div class="bc-pagination">{{ $brokers->links() }}</div>
    @endif
</div>
