@extends('admin.layout.app')
@include('admin.posts._assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')
@section('heading', 'Blog')

@section('main_content')
<div class="ab-page ab-page--blog">
    <div class="ab-wrap">
        @include('admin.posts._nav', ['active' => 'categories'])
        <header class="ab-header">
            <div>
                <p class="ab-header__eyebrow">Blog</p>
                <h1 class="ab-header__title">Categories</h1>
                <p class="ab-header__sub">Organize blogs by category. Subcategories live in the next tab.</p>
            </div>
            <div class="ab-header__actions">
                <a href="{{ route('admin_category_create') }}" class="ab-btn ab-btn--primary">
                    <i class="fas fa-plus" aria-hidden="true"></i>
                    Add category
                </a>
            </div>
        </header>

        <div class="ab-panel">
            @if($categories->isEmpty())
                <div class="ab-empty">
                    <h3>No categories yet</h3>
                    <p>Add the first category to start grouping blogs.</p>
                    <a href="{{ route('admin_category_create') }}" class="ab-btn ab-btn--primary">Add category</a>
                </div>
            @else
                <div class="ab-table-wrap">
                    <table class="ab-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Category</th>
                                <th>Menu</th>
                                <th>Order</th>
                                <th>Language</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $row)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $row->category_name }}</td>
                                    <td>
                                        <span class="ab-pill {{ $row->show_on_menu == 'Show' ? 'ab-pill--ok' : '' }}">{{ $row->show_on_menu }}</span>
                                    </td>
                                    <td>{{ $row->category_order }}</td>
                                    <td>{{ optional($row->rLanguage)->name }}</td>
                                    <td>
                                        <div class="ab-actions">
                                            <a href="{{ route('admin_category_edit', $row->id) }}" class="ab-btn ab-btn--ghost ab-btn--sm">Edit</a>
                                            <form action="{{ route('admin_category_delete', $row->id) }}" method="POST" data-ab-delete data-ab-name="{{ $row->category_name }}" data-ab-warn="Blogs in this category may be affected." data-ab-confirm="Delete category">
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
