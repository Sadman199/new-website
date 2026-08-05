@php
    $isEdit = isset($ad) && $ad->exists;
    $pagesText = is_array($ad->pages) ? implode("\n", $ad->pages) : '';
@endphp

@extends('admin.layout.app')

@section('heading', $isEdit ? 'Edit Ad' : 'Create Ad')

@section('button')
<a href="{{ route('admin_ads_index') }}" class="btn btn-primary"><i class="fas fa-eye"></i> View All</a>
@endsection

@section('main_content')
<div class="section-body py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">{{ $isEdit ? 'Update Advertisement' : 'New Advertisement / Popup' }}</h5>
                    </div>
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0 pl-3">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ $isEdit ? route('admin_ads_update', $ad->id) : route('admin_ads_store') }}" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="form-row">
                                <div class="form-group col-md-8">
                                    <label class="font-weight-bold">Title *</label>
                                    <input type="text" name="title" class="form-control" value="{{ old('title', $ad->title) }}" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="font-weight-bold">Type *</label>
                                    <select name="type" id="ad_type" class="form-control" required>
                                        @foreach(['popup','banner','image','video','text','custom'] as $t)
                                            <option value="{{ $t }}" {{ old('type', $ad->type) === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label class="font-weight-bold">Category / Campaign</label>
                                    <input type="text" name="category" class="form-control" value="{{ old('category', $ad->category) }}" placeholder="e.g. spring-promo">
                                </div>
                                <div class="form-group col-md-3">
                                    <label class="font-weight-bold">Position</label>
                                    <input type="text" name="position" class="form-control" value="{{ old('position', $ad->position) }}" placeholder="popup / sidebar">
                                </div>
                                <div class="form-group col-md-3">
                                    <label class="font-weight-bold">Priority</label>
                                    <input type="number" name="priority" class="form-control" value="{{ old('priority', $ad->priority ?? 0) }}" min="0">
                                    <small class="text-muted">Higher shows first</small>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Image</label>
                                @if($isEdit && $ad->image)
                                    <div class="mb-2">
                                        <img src="{{ $ad->image_url }}" alt="" style="max-width:200px;max-height:120px" class="rounded border">
                                    </div>
                                @endif
                                <input type="file" name="image" class="form-control-file" accept="image/*">
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Click-through Link</label>
                                <input type="url" name="link" class="form-control" value="{{ old('link', $ad->link) }}" placeholder="https://...">
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Video URL</label>
                                <input type="url" name="video_url" class="form-control" value="{{ old('video_url', $ad->video_url) }}" placeholder="YouTube / Vimeo / MP4 URL">
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Description</label>
                                <textarea name="description" class="form-control" rows="3">{{ old('description', $ad->description) }}</textarea>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Custom HTML</label>
                                <textarea name="html_code" class="form-control" rows="4" placeholder="Optional HTML for custom/popup content">{{ old('html_code', $ad->html_code) }}</textarea>
                            </div>

                            <hr>
                            <h6 class="font-weight-bold text-primary">Popup Trigger Settings</h6>

                            <div class="form-row" id="trigger_fields">
                                <div class="form-group col-md-4">
                                    <label class="font-weight-bold">Trigger Type</label>
                                    <select name="trigger_type" class="form-control">
                                        <option value="scroll" {{ old('trigger_type', $ad->trigger_type) === 'scroll' ? 'selected' : '' }}>Scroll (%)</option>
                                        <option value="time" {{ old('trigger_type', $ad->trigger_type) === 'time' ? 'selected' : '' }}>Time (seconds)</option>
                                        <option value="stay" {{ old('trigger_type', $ad->trigger_type) === 'stay' ? 'selected' : '' }}>Stay (minutes)</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="font-weight-bold">Trigger Value</label>
                                    <input type="number" name="trigger_value" class="form-control" value="{{ old('trigger_value', $ad->trigger_value ?? 50) }}" min="0">
                                    <small class="text-muted">Scroll: 0–100 (%). Time: seconds. Stay: minutes.</small>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="font-weight-bold d-block">Options</label>
                                    <div class="custom-control custom-checkbox mt-2">
                                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $ad->is_active) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">Active</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mt-2">
                                        <input type="checkbox" class="custom-control-input" id="repeatable" name="repeatable" value="1" {{ old('repeatable', $ad->repeatable) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="repeatable">Repeatable (show more than once)</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label class="font-weight-bold">Start Date</label>
                                    <input type="date" name="start_date" class="form-control" value="{{ old('start_date', optional($ad->start_date)->format('Y-m-d')) }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="font-weight-bold">End Date</label>
                                    <input type="date" name="end_date" class="form-control" value="{{ old('end_date', optional($ad->end_date)->format('Y-m-d')) }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Target Pages</label>
                                <textarea name="pages" class="form-control" rows="4" placeholder="/&#10;/scam-brokers&#10;/broker-reviews*">{{ old('pages', $pagesText) }}</textarea>
                                <small class="text-muted">One path per line. Leave empty = all pages. Use <code>*</code> as wildcard (e.g. <code>/broker-reviews*</code>).</small>
                            </div>

                            <div class="form-group text-center mt-4">
                                <button type="submit" class="btn btn-primary btn-lg px-5">{{ $isEdit ? 'Update Ad' : 'Create Ad' }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
