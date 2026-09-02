@extends('admin.layout.app')
@include('admin.brokers._assets')
@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')
@section('heading', 'Prop Firm Attributes')
@section('main_content')
<div class="ab-page"><div class="ab-wrap">
    <header class="ab-header">
        <div>
            <p class="ab-header__eyebrow">Prop firms</p>
            <h1 class="ab-header__title">Attributes</h1>
            <p class="ab-header__sub">Filter tags assigned to firms on the public directory.</p>
        </div>
        <div class="ab-header__actions"><a href="{{ route('admin_prop_firm_attributes_create') }}" class="ab-btn ab-btn--primary"><i class="fas fa-plus" aria-hidden="true"></i> Add attribute</a></div>
    </header>
    @include('admin.prop-firms._nav', ['active' => 'attributes'])
    <div class="ab-panel">
        @if($attributes->isEmpty())
            <div class="ab-empty"><h3>No attributes yet</h3><p>Add tags like platform, payout style, or asset class.</p><a href="{{ route('admin_prop_firm_attributes_create') }}" class="ab-btn ab-btn--primary">Add attribute</a></div>
        @else
            <div class="ab-table-wrap">
                <table class="ab-table">
                    <thead><tr><th>Name</th><th>Slug</th><th>Group</th><th>Used by</th><th></th></tr></thead>
                    <tbody>
                        @foreach($attributes as $attr)
                            <tr>
                                <td>
                                    <p class="ab-broker__name">{{ $attr->name }}</p>
                                    <div class="ab-pills">@if($attr->is_active)<span class="ab-pill ab-pill--ok">Active</span>@else<span class="ab-pill">Inactive</span>@endif</div>
                                </td>
                                <td>{{ $attr->slug }}</td>
                                <td>{{ $attr->group ?? '—' }}</td>
                                <td>{{ $attr->prop_firms_count }}</td>
                                <td>
                                    <div class="ab-actions">
                                        <a class="ab-btn ab-btn--ghost ab-btn--sm" href="{{ route('admin_prop_firm_attributes_edit', $attr->id) }}">Edit</a>
                                        <form action="{{ route('admin_prop_firm_attributes_delete', $attr->id) }}" method="POST" data-ab-delete data-ab-name="{{ $attr->name }}" data-ab-warn="This tag will be removed from any assigned firms." data-ab-confirm="Delete attribute">
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
            <div class="ab-foot">{{ $attributes->links('pagination::bootstrap-4') }}</div>
        @endif
    </div>
</div></div>
@endsection
