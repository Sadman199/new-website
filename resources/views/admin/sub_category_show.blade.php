@extends('admin.layout.app')
@include('admin.posts._assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')
@section('heading', 'Blog')

@section('main_content')
<div class="ab-page ab-page--blog">
    <div class="ab-wrap">
        @include('admin.posts._nav', ['active' => 'subcategories'])
        <header class="ab-header">
            <div>
                <p class="ab-header__eyebrow">Blog</p>
                <h1 class="ab-header__title">Subcategories</h1>
                <p class="ab-header__sub">Blogs are assigned to a subcategory, which belongs to a category.</p>
            </div>
            <div class="ab-header__actions">
                <a href="{{ route('admin_sub_category_create') }}" class="ab-btn ab-btn--primary">
                    <i class="fas fa-plus" aria-hidden="true"></i>
                    Add subcategory
                </a>
            </div>
        </header>

        <div class="ab-panel">
            @if($sub_categories->isEmpty())
                <div class="ab-empty">
                    <h3>No subcategories yet</h3>
                    <p>Add a subcategory so blogs can be classified.</p>
                    <a href="{{ route('admin_sub_category_create') }}" class="ab-btn ab-btn--primary">Add subcategory</a>
                </div>
            @else
                <div class="ab-table-wrap">
                    <table class="ab-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Subcategory</th>
                                <th>Category</th>
                                <th>Menu</th>
                                <th>Home</th>
                                <th>Order</th>
                                <th>Language</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sub_categories as $row)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $row->sub_category_name }}</td>
                                    <td>{{ optional($row->rCategory)->category_name ?? 'N/A' }}</td>
                                    <td><span class="ab-pill {{ $row->show_on_menu == 'Show' ? 'ab-pill--ok' : '' }}">{{ $row->show_on_menu }}</span></td>
                                    <td><span class="ab-pill {{ $row->show_on_home == 'Show' ? 'ab-pill--ok' : '' }}">{{ $row->show_on_home }}</span></td>
                                    <td>{{ $row->sub_category_order }}</td>
                                    <td>{{ optional($row->rLanguage)->name ?? 'N/A' }}</td>
                                    <td>
                                        <div class="ab-actions">
                                            <a href="{{ route('admin_sub_category_edit', $row->id) }}" class="ab-btn ab-btn--ghost ab-btn--sm">Edit</a>
                                            <form action="{{ route('admin_sub_category_delete', $row->id) }}" method="POST" data-ab-delete data-ab-name="{{ $row->sub_category_name }}" data-ab-warn="Blogs in this subcategory may be affected." data-ab-confirm="Delete subcategory">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="ab-btn ab-btn--ghost ab-btn--sm">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
