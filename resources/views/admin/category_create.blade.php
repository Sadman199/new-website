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
                <h1 class="ab-header__title">Add category</h1>
                <p class="ab-header__sub">Categories appear in the blog classification dropdown.</p>
            </div>
            <div class="ab-header__actions">
                <a href="{{ route('admin_category_show') }}" class="ab-btn ab-btn--ghost">Back to categories</a>
            </div>
        </header>

        <form class="ab-blog-form" action="{{ route('admin_category_store') }}" method="post">
            @csrf
            <div class="ab-layout ab-layout--post">
                <div class="ab-form-main">
                    <section class="ab-section">
                        <div class="ab-section__head">
                            <h2>Category details</h2>
                        </div>
                        <div class="ab-section__body">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Category name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="category_name" id="category_name" value="{{ old('category_name') }}" placeholder="Enter category name" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Slug</label>
                                    <input type="text" class="form-control" name="slug" value="{{ old('slug') }}" placeholder="e.g. category-name">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>Show on menu?</label>
                                    <select name="show_on_menu" class="form-control" required>
                                        <option value="Show">Show</option>
                                        <option value="Hide">Hide</option>
                                    </select>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>Order <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="category_order" value="{{ old('category_order') }}" required>
                                </div>
                                <div class="col-md-4 form-group">
                                    @include('admin.partials.language_id_field')
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                <aside class="ab-side">
                    <div class="ab-save ab-save--side">
                        <button type="submit" class="ab-btn ab-btn--primary">Create category</button>
                        <a href="{{ route('admin_category_show') }}" class="ab-btn ab-btn--ghost">Cancel</a>
                    </div>
                </aside>
            </div>
        </form>
    </div>
</div>
@endsection
