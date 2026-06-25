@extends('admin.layout.app')

@section('heading', 'Settings')

@section('main_content')
<div class="section-body py-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Website Settings</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin_setting_update') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <!-- Tabs Navigation -->
                            <ul class="nav nav-tabs mb-4" id="settingsTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Home Page</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="logo-tab" data-toggle="tab" href="#logo" role="tab" aria-controls="logo" aria-selected="false">Logo & Favicon</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="topbar-tab" data-toggle="tab" href="#topbar" role="tab" aria-controls="topbar" aria-selected="false">Top Bar</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="theme-tab" data-toggle="tab" href="#theme" role="tab" aria-controls="theme" aria-selected="false">Theme Color</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="analytic-tab" data-toggle="tab" href="#analytic" role="tab" aria-controls="analytic" aria-selected="false">Google Analytics</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="disqus-tab" data-toggle="tab" href="#disqus" role="tab" aria-controls="disqus" aria-selected="false">Disqus Comment</a>
                                </li>
                            </ul>
                            <!-- Tabs Content -->
                            <div class="tab-content" id="settingsTabsContent">
                                <!-- Home Page Tab -->
                                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                    <h6 class="mb-3 font-weight-bold text-muted">Home Page Settings</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">News Ticker Total <span class="text-danger">*</span></label>
                                                <input type="number" name="news_ticker_total" class="form-control" value="{{ old('news_ticker_total', $setting_data->news_ticker_total) }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">News Ticker Status</label>
                                                <select name="news_ticker_status" class="form-control custom-select">
                                                    <option value="Show" @if($setting_data->news_ticker_status == 'Show') selected @endif>Show</option>
                                                    <option value="Hide" @if($setting_data->news_ticker_status == 'Hide') selected @endif>Hide</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Video Item Total <span class="text-danger">*</span></label>
                                                <input type="number" name="video_total" class="form-control" value="{{ old('video_total', $setting_data->video_total) }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">Video Item Status</label>
                                                <select name="video_status" class="form-control custom-select">
                                                    <option value="Show" @if($setting_data->video_status == 'Show') selected @endif>Show</option>
                                                    <option value="Hide" @if($setting_data->video_status == 'Hide') selected @endif>Hide</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Logo & Favicon Tab -->
                                <div class="tab-pane fade" id="logo" role="tabpanel" aria-labelledby="logo-tab">
                                    <h6 class="mb-3 font-weight-bold text-muted">Logo & Favicon Settings</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Existing Logo</label>
                                                <div class="mb-3">
                                                    <img src="{{ asset('uploads/'.$setting_data->logo) }}" alt="Logo" class="img-fluid rounded" style="max-height: 80px;">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">Change Logo</label>
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="logo" name="logo">
                                                    <label class="custom-file-label" for="logo">Choose file</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Existing Favicon</label>
                                                <div class="mb-3">
                                                    <img src="{{ asset('uploads/'.$setting_data->favicon) }}" alt="Favicon" class="img-fluid rounded" style="max-height: 30px;">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">Change Favicon</label>
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="favicon" name="favicon">
                                                    <label class="custom-file-label" for="favicon">Choose file</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Top Bar Tab -->
                                <div class="tab-pane fade" id="topbar" role="tabpanel" aria-labelledby="topbar-tab">
                                    <h6 class="mb-3 font-weight-bold text-muted">Top Bar Settings</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Date Status</label>
                                                <select name="top_bar_date_status" class="form-control custom-select">
                                                    <option value="Show" @if($setting_data->top_bar_date_status == 'Show') selected @endif>Show</option>
                                                    <option value="Hide" @if($setting_data->top_bar_date_status == 'Hide') selected @endif>Hide</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">Email Status</label>
                                                <select name="top_bar_email_status" class="form-control custom-select">
                                                    <option value="Show" @if($setting_data->top_bar_email_status == 'Show') selected @endif>Show</option>
                                                    <option value="Hide" @if($setting_data->top_bar_email_status == 'Hide') selected @endif>Hide</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Email Address <span class="text-danger">*</span></label>
                                                <input type="email" name="top_bar_email" class="form-control" value="{{ old('top_bar_email', $setting_data->top_bar_email) }}" placeholder="Enter email address" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Theme Color Tab -->
                                <div class="tab-pane fade" id="theme" role="tabpanel" aria-labelledby="theme-tab">
                                    <h6 class="mb-3 font-weight-bold text-muted">Theme Color Settings</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Theme Color 1</label>
                                                <input type="text" name="theme_color_1" class="form-control jscolor" value="{{ old('theme_color_1', $setting_data->theme_color_1) }}" placeholder="e.g., #007bff">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Theme Color 2</label>
                                                <input type="text" name="theme_color_2" class="form-control jscolor" value="{{ old('theme_color_2', $setting_data->theme_color_2) }}" placeholder="e.g., #28a745">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Google Analytics Tab -->
                                <div class="tab-pane fade" id="analytic" role="tabpanel" aria-labelledby="analytic-tab">
                                    <h6 class="mb-3 font-weight-bold text-muted">Google Analytics Settings</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Analytics ID</label>
                                                <input type="text" name="analytic_id" class="form-control" value="{{ old('analytic_id', $setting_data->analytic_id) }}" placeholder="e.g., UA-XXXXX-Y">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Status</label>
                                                <select name="analytic_status" class="form-control custom-select">
                                                    <option value="Show" @if($setting_data->analytic_status == 'Show') selected @endif>Show</option>
                                                    <option value="Hide" @if($setting_data->analytic_status == 'Hide') selected @endif>Hide</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Disqus Comment Tab -->
                                <div class="tab-pane fade" id="disqus" role="tabpanel" aria-labelledby="disqus-tab">
                                    <h6 class="mb-3 font-weight-bold text-muted">Disqus Comment Settings</h6>
                                    <div class="form-group">
                                        <label class="font-weight-bold">Disqus Code</label>
                                        <textarea name="disqus_code" class="form-control" rows="8" placeholder="Enter Disqus code">{{ old('disqus_code', $setting_data->disqus_code) }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <!-- Submit Button -->
                            <div class="form-group text-center mt-4">
                                <button type="submit" class="btn btn-primary btn-lg px-5">Update Settings</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection