@extends('admin.layout.app')
@include('admin.brokers._assets')
@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')
@section('heading', 'Prop Firm Programs')
@section('main_content')
<div class="ab-page"><div class="ab-wrap">
    <header class="ab-header">
        <div><p class="ab-header__eyebrow">Prop firms</p><h1 class="ab-header__title">Programs</h1><p class="ab-header__sub">Funding programs across every firm.</p></div>
        <div class="ab-header__actions"><a href="{{ route('admin_prop_firm_programs_create') }}" class="ab-btn ab-btn--primary"><i class="fas fa-plus" aria-hidden="true"></i> Add program</a></div>
    </header>
    @include('admin.prop-firms._nav', ['active' => 'programs'])
    <div class="ab-panel">
        <form method="GET" class="ab-filters">
            <div class="ab-field"><label for="ab-q">Search</label><input id="ab-q" class="ab-input" type="search" name="q" value="{{ request('q') }}" placeholder="Program name…"></div>
            <div class="ab-field">
                <label for="ab-firm">Firm</label>
                <select id="ab-firm" class="ab-select" name="prop_firm_id">
                    <option value="">All firms</option>
                    @foreach($propFirms as $firm)
                        <option value="{{ $firm->id }}" @selected(request('prop_firm_id') == $firm->id)>{{ $firm->name }}</option>
                    @endforeach
                </select>
            </div>
            <div></div>
            <div class="ab-header__actions">
                <button type="submit" class="ab-btn ab-btn--primary">Filter</button>
                @if(request()->filled('q') || request()->filled('prop_firm_id'))
                    <a href="{{ route('admin_prop_firm_programs_show') }}" class="ab-btn ab-btn--ghost">Reset</a>
                @endif
            </div>
        </form>
        @if($programs->isEmpty())
            <div class="ab-empty"><h3>No programs found</h3><p>Add a program, or attach programs while editing a firm.</p><a href="{{ route('admin_prop_firm_programs_create') }}" class="ab-btn ab-btn--primary">Add program</a></div>
        @else
            <div class="ab-table-wrap">
                <table class="ab-table">
                    <thead><tr><th>Program</th><th>Firm</th><th>Account size</th><th>Entry fee</th><th></th></tr></thead>
                    <tbody>
                        @foreach($programs as $program)
                            <tr>
                                <td>
                                    <p class="ab-broker__name">{{ $program->name }}</p>
                                    <div class="ab-pills">@if($program->is_active)<span class="ab-pill ab-pill--ok">Active</span>@else<span class="ab-pill">Inactive</span>@endif</div>
                                </td>
                                <td>{{ $program->propFirm?->name ?? '—' }}</td>
                                <td>{{ $program->account_size ?? '—' }}</td>
                                <td>{{ $program->entry_fee !== null ? '$'.number_format((float) $program->entry_fee, 2) : '—' }}</td>
                                <td>
                                    <div class="ab-actions">
                                        <a class="ab-btn ab-btn--ghost ab-btn--sm" href="{{ route('admin_prop_firm_programs_edit', $program->id) }}">Edit</a>
                                        <form action="{{ route('admin_prop_firm_programs_delete', $program->id) }}" method="POST" data-ab-delete data-ab-name="{{ $program->name }}" data-ab-warn="This program will be removed from the firm." data-ab-confirm="Delete program">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="ab-btn ab-btn--danger ab-btn--sm">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="ab-foot">{{ $programs->links('pagination::bootstrap-4') }}</div>
        @endif
    </div>
</div></div>
@endsection
