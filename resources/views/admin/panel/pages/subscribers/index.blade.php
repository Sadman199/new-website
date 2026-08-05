@php
    $subscriberStats = $subscriberStats ?? ['total' => 0, 'active' => 0];
@endphp

<x-admin.page-header title="Subscribers" description="Email newsletter subscribers.">
    <x-slot:actions>
        <a href="{{ route('admin_subscriber_send_email') }}" class="btn-bc btn-bc-primary">
            <i class="fas fa-paper-plane"></i> Send Email to All
        </a>
    </x-slot:actions>
</x-admin.page-header>

<div class="bc-card">
    <div class="stat-grid" style="grid-template-columns: repeat(2,1fr);margin:0;padding:1rem;">
        <div class="stat-card">
            <div class="stat-value">{{ $subscriberStats['total'] ?? 0 }}</div>
            <div class="stat-label">Total Subscribers</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $subscriberStats['active'] ?? 0 }}</div>
            <div class="stat-label">Active</div>
        </div>
    </div>
    <div class="table-wrap">
        <table class="bc-table">
            <thead>
                <tr>
                    <th>email</th>
                    <th>token</th>
                    <th>status</th>
                    <th>created_at</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subscribers as $subscriber)
                    <tr>
                        <td>{{ $subscriber->email }}</td>
                        <td>{{ $subscriber->token ?? '—' }}</td>
                        <td>{{ $subscriber->status ?? 'active' }}</td>
                        <td>{{ $subscriber->created_at ? $subscriber->created_at->format('M j, Y') : '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-muted text-center">No subscribers found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($subscribers instanceof \Illuminate\Pagination\AbstractPaginator && $subscribers->hasPages())
        <div class="bc-pagination">{{ $subscribers->links() }}</div>
    @endif
</div>
