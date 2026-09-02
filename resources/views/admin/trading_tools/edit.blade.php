@extends('admin.layout.app')
@include('admin.partials._ab_assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')
@section('heading', 'Edit Trading Tool')

@section('main_content')
<div class="ab-page ab-page--hub">
    <div class="ab-wrap">
        <header class="ab-header">
            <div>
                <p class="ab-header__eyebrow">Trading Tools</p>
                <h1 class="ab-header__title">{{ $tool->name }}</h1>
                <p class="ab-header__sub">{{ $tool->slug }}</p>
            </div>
            <div class="ab-header__actions">
                <a href="{{ route('admin_trading_tools_index') }}" class="ab-btn ab-btn--ghost">All tools</a>
            </div>
        </header>

        @if($errors->any())
            <div class="ab-banner" role="alert">
                <h3>Please fix {{ $errors->count() }} {{ \Illuminate\Support\Str::plural('issue', $errors->count()) }} before saving</h3>
                <ol>
                    @foreach($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ol>
            </div>
        @endif

        <form action="{{ route('admin_trading_tools_update', $tool->id) }}" method="POST">
            @csrf
            <section class="ab-section">
                <div class="ab-section__head">
                    <h2>Tool details</h2>
                    <p>The slug stays the same so public URLs do not change.</p>
                </div>
                <div class="ab-section__body">
                    <div class="row">
                        <div class="col-md-8 form-group">
                            <label for="name">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $tool->name) }}" required>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="icon">Icon class</label>
                            <input type="text" name="icon" id="icon" class="form-control" value="{{ old('icon', $tool->icon) }}" placeholder="fas fa-calculator">
                        </div>
                        <div class="col-md-8 form-group">
                            <label for="short_description">Short description</label>
                            <input type="text" name="short_description" id="short_description" class="form-control" value="{{ old('short_description', $tool->short_description) }}">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="sort_order">Sort order</label>
                            <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order', $tool->sort_order) }}" min="0">
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control snote" rows="6">{{ old('description', $tool->description) }}</textarea>
                        </div>
                        <div class="col-md-12 form-group mb-0">
                            <label class="ab-check">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" id="is_active" value="1" @checked(old('is_active', $tool->is_active))>
                                Visible on the public dashboard
                            </label>
                        </div>
                    </div>
                </div>
            </section>
            <div class="ab-save">
                <button type="submit" class="ab-btn ab-btn--primary">Save changes</button>
                <a href="{{ route('admin_trading_tools_index') }}" class="ab-btn ab-btn--ghost">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
