<x-admin.page-header title="Broker FAQs" description="Per-broker frequently asked questions.">
    <x-slot:actions>
        <a href="{{ route('admin_faq_create') }}" class="btn-bc btn-bc-primary">
            <i class="fas fa-plus"></i> Add FAQ
        </a>
    </x-slot:actions>
</x-admin.page-header>

<div class="bc-card">
    <div class="filters-bar">
        <select name="broker_id">
            <option value="">All Brokers</option>
        </select>
    </div>
    <div class="table-wrap">
        <table class="bc-table">
            <thead>
                <tr>
                    <th>broker_id</th>
                    <th>faq_title</th>
                    <th>language_id</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($faqs as $faq)
                    <tr>
                        <td>{{ $faq->broker_id }}</td>
                        <td>{{ $faq->faq_title }}</td>
                        <td>{{ $faq->language_id }}</td>
                        <td>
                            <a href="{{ route('admin_faq_edit', $faq->id) }}" class="btn-bc btn-bc-ghost btn-bc-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-muted text-center">No FAQs found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
