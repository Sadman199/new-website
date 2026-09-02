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
                <h1 class="ab-header__title">Edit subcategory</h1>
                <p class="ab-header__sub">{{ $sub_category_single->sub_category_name }}</p>
            </div>
            <div class="ab-header__actions">
                <a href="{{ route('admin_sub_category_show') }}" class="ab-btn ab-btn--ghost">Back to subcategories</a>
            </div>
        </header>

        <form class="ab-blog-form" action="{{ route('admin_sub_category_update', $sub_category_single->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="ab-layout ab-layout--post">
                <div class="ab-form-main">
                    <section class="ab-section">
                        <div class="ab-section__head">
                            <h2>Subcategory details</h2>
                        </div>
                        <div class="ab-section__body">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Subcategory name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="sub_category_name" value="{{ old('sub_category_name', $sub_category_single->sub_category_name) }}" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Slug <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="slug" value="{{ old('slug', $sub_category_single->slug) }}" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Category <span class="text-danger">*</span></label>
                                    <select name="category_id" class="form-control" required>
                                        @foreach($categories as $row)
                                            <option value="{{ $row->id }}" @selected((string) old('category_id', $sub_category_single->category_id) === (string) $row->id)>{{ $row->category_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Order <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="sub_category_order" value="{{ old('sub_category_order', $sub_category_single->sub_category_order) }}" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    @include('admin.partials.language_id_field', ['language_id' => $sub_category_single->language_id])
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Show on menu?</label>
                                    <select name="show_on_menu" class="form-control">
                                        <option value="Show" @selected($sub_category_single->show_on_menu == 'Show')>Show</option>
                                        <option value="Hide" @selected($sub_category_single->show_on_menu == 'Hide')>Hide</option>
                                    </select>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Show on home?</label>
                                    <select name="show_on_home" class="form-control">
                                        <option value="Show" @selected($sub_category_single->show_on_home == 'Show')>Show</option>
                                        <option value="Hide" @selected($sub_category_single->show_on_home == 'Hide')>Hide</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                <aside class="ab-side">
                    <div class="ab-save ab-save--side">
                        <button type="submit" class="ab-btn ab-btn--primary">Save changes</button>
                        <a href="{{ route('admin_sub_category_show') }}" class="ab-btn ab-btn--ghost">Cancel</a>
                    </div>
                </aside>
            </div>
        </form>
    </div>
</div>
@endsection
