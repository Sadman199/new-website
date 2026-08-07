<x-admin.layout :title="$title ?? 'Broker Safety Reports'">
    <x-admin.page-header title="Broker Safety Management" description="Review community broker reports submitted via the scam checker tool.">
        <x-slot:actions>
            <a href="{{ route('broker.scam_checker') }}" class="btn-bc btn-bc-secondary" target="_blank" rel="noopener">
                <i class="fas fa-external-link-alt"></i> Open scam checker
            </a>
            <a href="{{ route('admin_broker_scam') }}" class="btn-bc btn-bc-primary">
                <i class="fas fa-exclamation-triangle"></i> Scam brokers
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="bc-card mb-3">
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.panel.broker-reports.index') }}"
               class="btn btn-sm {{ empty($activeStatus) ? 'btn-primary' : 'btn-outline-secondary' }}">All</a>
            @foreach($statuses as $value => $label)
                <a href="{{ route('admin.panel.broker-reports.index', ['status' => $value]) }}"
                   class="btn btn-sm {{ $activeStatus === $value ? 'btn-primary' : 'btn-outline-secondary' }}">{{ $label }}</a>
            @endforeach
        </div>
    </div>

    <div class="bc-card">
        <div class="table-wrap">
            <table class="bc-table">
                <thead>
                    <tr>
                        <th>Broker</th>
                        <th>Reporter</th>
                        <th>Issue</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                        <tr>
                            <td>
                                <strong>{{ $report->broker?->name ?? $report->broker_name ?? 'Unknown' }}</strong>
                                @if($report->broker)
                                    <br><a href="{{ route('admin_broker_edit', $report->broker_id) }}" class="small">Edit broker safety</a>
                                @endif
                            </td>
                            <td>
                                {{ $report->reporter_name }}<br>
                                <small class="text-muted">{{ $report->reporter_email }}</small>
                            </td>
                            <td>{{ $report->issueLabel() }}</td>
                            <td><span class="badge badge-secondary">{{ $report->statusLabel() }}</span></td>
                            <td>{{ $report->created_at?->format('M j, Y') }}</td>
                            <td>
                                <form action="{{ route('admin.panel.broker-reports.update', $report) }}" method="post" class="bsc-admin-form">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" class="form-control form-control-sm mb-2">
                                        @foreach($statuses as $value => $label)
                                            <option value="{{ $value }}" @selected($report->status === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <textarea name="admin_notes" class="form-control form-control-sm mb-2" rows="2" placeholder="Admin notes">{{ old('admin_notes', $report->admin_notes) }}</textarea>
                                    <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                </form>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6" class="bg-light">
                                <small>{{ $report->message }}</small>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No broker reports yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">{{ $reports->links('pagination::bootstrap-4') }}</div>
    </div>
</x-admin.layout>
