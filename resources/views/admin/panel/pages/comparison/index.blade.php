<x-admin.page-header
    title="Compare Brokers"
    description='<span class="bc-badge bc-badge-warning">No database table</span> — Frontend reads <code>brokers</code> columns (minimum_deposit, regulation, spreads, rating, etc.)'
/>

<div class="bc-card">
    <div class="bc-card-body">
        <div class="alert alert-info mb-0">
            <h4 class="mb-2"><i class="fas fa-columns"></i> Frontend App Feature</h4>
            <p class="mb-2">
                Broker comparison is handled by the Laravel application, not a separate admin module.
                It reads live data from the <code>brokers</code> table and renders comparison pages on the public site.
            </p>
            <p class="mb-3 text-muted">
                Manage broker fields (spreads, regulation, minimum deposit, rating, etc.) via
                <a href="{{ route('admin_broker_show') }}">Broker Management</a>.
            </p>
            <a href="{{ route('broker.comparison') }}" class="btn-bc btn-bc-outline btn-bc-sm" target="_blank">
                <i class="fas fa-external-link-alt"></i> View Compare Page
            </a>
        </div>
    </div>
</div>
