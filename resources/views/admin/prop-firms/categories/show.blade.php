@extends('admin.layout.app')
@include('admin.brokers._assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')

@section('heading', 'Prop Firm Categories')

@section('main_content')
<div class="ab-page">
    <div class="ab-wrap">
        <header class="ab-header">
            <div>
                <p class="ab-header__eyebrow">Prop firms</p>
                <h1 class="ab-header__title">Categories</h1>
                <p class="ab-header__sub">Groups used to filter the public prop firm directory.</p>
            </div>
            <div class="ab-header__actions">
                <a href="{{ route('admin_prop_firm_categories_create') }}" class="ab-btn ab-btn--primary"><i class="fas fa-plus" aria-hidden="true"></i> Add category</a>
            </div>
        </header>
        @include('admin.prop-firms._nav', ['active' => 'categories'])
        <div class="ab-panel">
            @if($categories->isEmpty())
                <div class="ab-empty">
                    <h3>No categories yet</h3>
                    <p>Add a category so firms can be grouped on the public index.</p>
                    <a href="{{ route('admin_prop_firm_categories_create') }}" class="ab-btn ab-btn--primary">Add category</a>
                </div>
            @else
                <div class="ab-table-wrap">
                    <table class="ab-table">
                        <thead><tr><th>Name</th><th>Slug</th><th>Firms</th><th>Order</th><th></th></tr></thead>
                        <tbody>
                            @foreach($categories as $cat)
                                <tr>
                                    <td>
                                        <p class="ab-broker__name">{{ $cat->name }}</p>
                                        <div class="ab-pills">@if($cat->is_active)<span class="ab-pill ab-pill--ok">Active</span>@else<span class="ab-pill">Inactive</span>@endif</div>
                                    </td>
                                    <td>{{ $cat->slug }}</td>
                                    <td>{{ $cat->prop_firms_count }}</td>
                                    <td>{{ $cat->sort_order }}</td>
                                    <td>
                                        <div class="ab-actions">
                                            <a class="ab-btn ab-btn--ghost ab-btn--sm" href="{{ route('admin_prop_firm_categories_edit', $cat->id) }}">Edit</a>
                                            <form action="{{ route('admin_prop_firm_categories_delete', $cat->id) }}" method="POST" data-ab-delete data-ab-name="{{ $cat->name }}" data-ab-warn="Firms in this category will be ungrouped, not deleted." data-ab-confirm="Delete category">
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
                <div class="ab-foot">{{ $categories->links('pagination::bootstrap-4') }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
