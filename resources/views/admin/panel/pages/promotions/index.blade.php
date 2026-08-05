<x-admin.page-header title="Bonuses &amp; Promotions" description='Table: <code>forex_bonuses</code>'>
    <x-slot:actions>
        <a href="{{ route('admin_forex_bonus_create') }}" class="btn-bc btn-bc-primary">
            <i class="fas fa-plus"></i> Add Promotion
        </a>
    </x-slot:actions>
</x-admin.page-header>

<div class="bc-card">
    <div class="table-wrap">
        <table class="bc-table">
            <thead>
                <tr>
                    <th>title</th>
                    <th>promo_type</th>
                    <th>publish_date</th>
                    <th>min_deposit</th>
                    <th>promotion_status</th>
                    <th>expiry_date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bonuses as $bonus)
                    <tr>
                        <td>{{ $bonus->title }}</td>
                        <td>{{ $bonus->promo_type }}</td>
                        <td>{{ $bonus->publish_date ? \Illuminate\Support\Carbon::parse($bonus->publish_date)->format('M j, Y') : '—' }}</td>
                        <td>${{ number_format((float) ($bonus->min_deposit ?? 0), 0) }}</td>
                        <td>
                            <span class="bc-badge bc-badge-success">{{ $bonus->promotion_status }}</span>
                        </td>
                        <td>{{ $bonus->expiry_date ? \Illuminate\Support\Carbon::parse($bonus->expiry_date)->format('M j, Y') : '—' }}</td>
                        <td>
                            <a href="{{ route('admin_forex_bonus_edit', $bonus->id) }}" class="btn-bc btn-bc-ghost btn-bc-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-muted text-center">No promotions found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($bonuses instanceof \Illuminate\Pagination\AbstractPaginator && $bonuses->hasPages())
        <div class="bc-pagination">{{ $bonuses->links() }}</div>
    @endif
</div>
