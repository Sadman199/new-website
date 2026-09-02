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
                <h1 class="ab-header__title">Add subcategory</h1>
                <p class="ab-header__sub">Each blog is assigned to one subcategory.</p>
            </div>
            <div class="ab-header__actions">
                <a href="{{ route('admin_sub_category_show') }}" class="ab-btn ab-btn--ghost">Back to subcategories</a>
            </div>
        </header>

        <form class="ab-blog-form" action="{{ route('admin_sub_category_store') }}" method="post">
            @csrf
            <div class="ab-layout ab-layout--post">
                <div class="ab-form-main">
                    <section class="ab-section">
                        <div class="ab-section__head">
                            <h2>Subcategory details</h2>
                        </div>
                        <div class="ab-section__body">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="sub_category_name">Subcategory name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="sub_category_name" id="sub_category_name" value="{{ old('sub_category_name') }}" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="slug">Slug <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="slug" id="slug" value="{{ old('slug') }}" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Category <span class="text-danger">*</span></label>
                                    <select name="category_id" class="form-control" required>
                                        @foreach($categories as $row)
                                            <option value="{{ $row->id }}" @selected((string) old('category_id') === (string) $row->id)>{{ $row->category_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Order <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="sub_category_order" value="{{ old('sub_category_order') }}" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    @include('admin.partials.language_id_field')
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Show on menu?</label>
                                    <select name="show_on_menu" class="form-control">
                                        <option value="Show">Show</option>
                                        <option value="Hide">Hide</option>
                                    </select>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Show on home?</label>
                                    <select name="show_on_home" class="form-control">
                                        <option value="Show">Show</option>
                                        <option value="Hide">Hide</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                <aside class="ab-side">
                    <div class="ab-save ab-save--side">
                        <button type="submit" class="ab-btn ab-btn--primary">Create subcategory</button>
                        <a href="{{ route('admin_sub_category_show') }}" class="ab-btn ab-btn--ghost">Cancel</a>
                    </div>
                </aside>
            </div>
        </form>
    </div>
</div>
@endsection
