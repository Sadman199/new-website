@extends('admin.layout.app')
@include('admin.posts._assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')
@section('heading', 'Content types')

@section('main_content')
<div class="ab-page ab-page--blog">
    <div class="ab-wrap">
        @include('admin.posts._nav', ['active' => 'types'])
        <header class="ab-header">
            <div>
                <p class="ab-header__eyebrow">Blog</p>
                <h1 class="ab-header__title">Content types</h1>
                <p class="ab-header__sub">Add or rename types used on Create Blog and Edit Blog. Typing a new name on those pages also creates a type.</p>
            </div>
        </header>

        <div class="ab-layout ab-layout--post">
            <div class="ab-form-main">
                <section class="ab-section">
                    <div class="ab-section__head">
                        <h2>Existing types</h2>
                        <p>Types in use cannot be deleted until blogs are reassigned.</p>
                    </div>
                    <div class="ab-section__body p-0">
                        @if($types->isEmpty())
                            <div class="ab-empty">
                                <h3>No content types yet</h3>
                                <p>Add the first type using the form on the right.</p>
                            </div>
                        @else
                            <div class="ab-table-wrap">
                                <table class="ab-table">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Slug</th>
                                            <th>Order</th>
                                            <th>Status</th>
                                            <th>Blogs</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($types as $row)
                                            <tr>
                                                <td>
                                                    <input form="type-{{ $row->id }}" type="text" name="name" class="ab-input" value="{{ $row->name }}" required maxlength="80">
                                                </td>
                                                <td>
                                                    <input form="type-{{ $row->id }}" type="text" name="slug" class="ab-input" value="{{ $row->slug }}" maxlength="80">
                                                </td>
                                                <td>
                                                    <input form="type-{{ $row->id }}" type="number" name="sort_order" class="ab-input" value="{{ $row->sort_order }}" min="0" max="999" style="width: 5rem">
                                                </td>
                                                <td>
                                                    <label class="ab-check mb-0">
                                                        <input form="type-{{ $row->id }}" type="hidden" name="is_active" value="0">
                                                        <input form="type-{{ $row->id }}" type="checkbox" name="is_active" value="1" @checked($row->is_active)>
                                                        Active
                                                    </label>
                                                </td>
                                                <td>{{ number_format($row->posts_count) }}</td>
                                                <td>
                                                    <div class="ab-actions">
                                                        <form id="type-{{ $row->id }}" action="{{ route('admin_post_content_types_update', $row->id) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="ab-btn ab-btn--ghost ab-btn--sm">Save</button>
                                                        </form>
                                                        <form action="{{ route('admin_post_content_types_destroy', $row->id) }}" method="POST" data-ab-delete data-ab-name="{{ $row->name }}" data-ab-warn="Only unused types can be deleted." data-ab-confirm="Delete type">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="ab-btn ab-btn--ghost ab-btn--sm" @disabled($row->posts_count > 0)>Delete</button>
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
                </section>
            </div>

            <aside class="ab-side">
                <section class="ab-section">
                    <div class="ab-section__head">
                        <h2>Add type</h2>
                        <p>New types appear immediately in the blog form.</p>
                    </div>
                    <div class="ab-section__body">
                        <form action="{{ route('admin_post_content_types_store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="type_name">Name <span class="text-danger">*</span></label>
                                <input id="type_name" type="text" name="name" class="form-control" value="{{ old('name') }}" required maxlength="80" placeholder="e.g. Interview">
                                @error('name')<small class="ab-error">{{ $message }}</small>@enderror
                            </div>
                            <div class="form-group">
                                <label for="type_slug">Slug</label>
                                <input id="type_slug" type="text" name="slug" class="form-control" value="{{ old('slug') }}" maxlength="80" placeholder="auto from name">
                                @error('slug')<small class="ab-error">{{ $message }}</small>@enderror
                            </div>
                            <div class="form-group">
                                <label for="type_order">Order</label>
                                <input id="type_order" type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $type->sort_order) }}" min="0" max="999">
                            </div>
                            <label class="ab-check">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true))>
                                Active
                            </label>
                            <div class="ab-save ab-save--side mt-3">
                                <button type="submit" class="ab-btn ab-btn--primary">Add content type</button>
                            </div>
                        </form>
                    </div>
                </section>
            </aside>
        </div>
    </div>
</div>
@endsection
