@php
    $isEdit = isset($ad) && $ad->exists;
    $pagesText = is_array($ad->pages) ? implode("\n", $ad->pages) : '';
@endphp

@extends('admin.layout.app')
@include('admin.partials._ab_assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')
@section('heading', $isEdit ? 'Edit Ad' : 'Create Ad')

@section('main_content')
<div class="ab-page ab-page--hub">
    <div class="ab-wrap">
        @include('admin.ads._nav', ['active' => 'popups'])
        <header class="ab-header">
            <div>
                <p class="ab-header__eyebrow">Popup Ads</p>
                <h1 class="ab-header__title">{{ $isEdit ? $ad->title : 'Add Popup Ad' }}</h1>
                <p class="ab-header__sub">{{ $isEdit ? 'Update the campaign, trigger, and targeting.' : 'Create a popup or campaign ad for public pages.' }}</p>
            </div>
            <div class="ab-header__actions">
                <a href="{{ route('admin_ads_index') }}" class="ab-btn ab-btn--ghost">All popup ads</a>
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

        <form action="{{ $isEdit ? route('admin_ads_update', $ad->id) : route('admin_ads_store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <section class="ab-section">
                <div class="ab-section__head">
                    <h2>Basics</h2>
                    <p>Title, type, and where this campaign sits.</p>
                </div>
                <div class="ab-section__body">
                    <div class="row">
                        <div class="col-md-8 form-group">
                            <label for="title">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $ad->title) }}" required>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="ad_type">Type <span class="text-danger">*</span></label>
                            <select name="type" id="ad_type" class="form-control" required>
                                @foreach(['popup','banner','image','video','text','custom'] as $t)
                                    <option value="{{ $t }}" @selected(old('type', $ad->type) === $t)>{{ ucfirst($t) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="category">Campaign label</label>
                            <input type="text" name="category" id="category" class="form-control" value="{{ old('category', $ad->category) }}" placeholder="e.g. spring-promo">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="position">Position</label>
                            <input type="text" name="position" id="position" class="form-control" value="{{ old('position', $ad->position) }}" placeholder="popup / sidebar">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="priority">Priority</label>
                            <input type="number" name="priority" id="priority" class="form-control" value="{{ old('priority', $ad->priority ?? 0) }}" min="0">
                        </div>
                    </div>
                </div>
            </section>

            <section class="ab-section">
                <div class="ab-section__head">
                    <h2>Creative</h2>
                    <p>Image, link, video, or custom HTML.</p>
                </div>
                <div class="ab-section__body">
                    <div class="form-group">
                        <label for="image">Image</label>
                        @if($isEdit && $ad->image_url)
                            <div class="mb-2"><img src="{{ $ad->image_url }}" alt="" class="ab-ad-preview" style="max-width:220px"></div>
                        @endif
                        <input type="file" name="image" id="image" class="form-control-file" accept="image/*">
                    </div>
                    <div class="form-group">
                        <label for="link">Click-through link</label>
                        <input type="url" name="link" id="link" class="form-control" value="{{ old('link', $ad->link) }}" placeholder="https://">
                    </div>
                    <div class="form-group">
                        <label for="video_url">Video URL</label>
                        <input type="url" name="video_url" id="video_url" class="form-control" value="{{ old('video_url', $ad->video_url) }}">
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $ad->description) }}</textarea>
                    </div>
                    <div class="form-group mb-0">
                        <label for="html_code">Custom HTML</label>
                        <textarea name="html_code" id="html_code" class="form-control" rows="4" data-admin-editor="off">{{ old('html_code', $ad->html_code) }}</textarea>
                    </div>
                </div>
            </section>

            <section class="ab-section">
                <div class="ab-section__head">
                    <h2>Trigger and schedule</h2>
                    <p>When the popup appears, and optional start/end dates.</p>
                </div>
                <div class="ab-section__body">
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="trigger_type">Trigger type</label>
                            <select name="trigger_type" id="trigger_type" class="form-control">
                                <option value="scroll" @selected(old('trigger_type', $ad->trigger_type) === 'scroll')>Scroll (%)</option>
                                <option value="time" @selected(old('trigger_type', $ad->trigger_type) === 'time')>Time (seconds)</option>
                                <option value="stay" @selected(old('trigger_type', $ad->trigger_type) === 'stay')>Stay (minutes)</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="trigger_value">Trigger value</label>
                            <input type="number" name="trigger_value" id="trigger_value" class="form-control" value="{{ old('trigger_value', $ad->trigger_value ?? 50) }}" min="0">
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="ab-check mt-4">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" id="is_active" value="1" @checked(old('is_active', $ad->is_active))>
                                Active
                            </label>
                            <label class="ab-check">
                                <input type="hidden" name="repeatable" value="0">
                                <input type="checkbox" name="repeatable" id="repeatable" value="1" @checked(old('repeatable', $ad->repeatable))>
                                Repeatable
                            </label>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="start_date">Start date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date', optional($ad->start_date)->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="end_date">End date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date', optional($ad->end_date)->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-12 form-group mb-0">
                            <label for="pages">Target pages</label>
                            <textarea name="pages" id="pages" class="form-control" rows="4" data-admin-editor="off" placeholder="/&#10;/scam-brokers">{{ old('pages', $pagesText) }}</textarea>
                            <p class="ab-note mt-1 mb-0">One path per line. Leave empty for all pages. Use * as a wildcard.</p>
                        </div>
                    </div>
                </div>
            </section>

            <div class="ab-save">
                <button type="submit" class="ab-btn ab-btn--primary">{{ $isEdit ? 'Save changes' : 'Create Ad' }}</button>
                <a href="{{ route('admin_ads_index') }}" class="ab-btn ab-btn--ghost">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
