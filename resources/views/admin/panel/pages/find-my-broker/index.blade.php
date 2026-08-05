<x-admin.page-header
    title="Find My Broker"
    description='<span class="bc-badge bc-badge-warning">No database table</span> — Filters query <code>brokers</code> and <code>account_options</code> text columns via LIKE'
/>

<div class="bc-card">
    <div class="bc-card-body">
        <div class="alert alert-info mb-0">
            <h4 class="mb-2"><i class="fas fa-search"></i> Frontend App Feature</h4>
            <p class="mb-2">
                Find My Broker is powered by <code>BrokerFilterService</code> on the frontend.
                It filters brokers and account options dynamically — there is no dedicated admin table to manage.
            </p>
            <p class="mb-3 text-muted">
                Keep broker and account option data up to date via
                <a href="{{ route('admin_broker_show') }}">Brokers</a> and account options per broker.
            </p>
            <a href="{{ route('find_my_broker') }}" class="btn-bc btn-bc-outline btn-bc-sm" target="_blank">
                <i class="fas fa-external-link-alt"></i> View Find My Broker
            </a>
        </div>
    </div>
</div>
