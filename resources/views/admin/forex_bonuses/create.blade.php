@extends('admin.layout.app')
@section('heading', 'Add Forex Bonus')
@section('button')
@endsection
@section('main_content')
<div class="section-body">
    <div class="card">
        <div class="card-body">
            <!-- Display validation errors -->
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Form to create a new Forex Bonus -->
            <form action="{{ route('admin_forex_bonus_store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="accordion">
                    <!-- Basic Information Section -->
                    <div class="card mb-2">
                        <div class="card-header" id="headingBasic">
                            <h5 class="mb-0">
                                <button class="btn btn-link" data-toggle="collapse" data-target="#collapseBasic" aria-expanded="true" aria-controls="collapseBasic">
                                    Basic Information
                                </button>
                            </h5>
                        </div>
                        <div id="collapseBasic" class="collapse show" aria-labelledby="headingBasic" data-parent="#accordion">
                            <div class="card-body">
                                <div class="row">
                                    <!-- Title -->
                                    <div class="col-md-6 mb-3">
                                        <label for="title">Blog Title</label>
                                        <input type="text" name="title" id="title" class="form-control">
                                    </div>
                                    <!-- Broker -->
                                    <div class="col-md-6 mb-3">
                                        <label for="broker_id">Broker</label>
                                        <select name="broker_id" id="broker_id" class="form-control">
                                            <option value="">— Select broker —</option>
                                            @foreach($brokers as $broker)
                                                <option value="{{ $broker->id }}" @selected(old('broker_id') == $broker->id)>{{ $broker->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!-- Publish Date -->
                                    <div class="col-md-6 mb-3">
                                        <label for="publish_date">Publish Date</label>
                                        <input type="date" name="publish_date" id="publish_date" class="form-control" value="{{ old('publish_date', date('Y-m-d')) }}">
                                    </div>
                                    <!-- Display Author (fallback label) -->
                                    <div class="col-md-6 mb-3">
                                        <label for="author_name">Display Author Label</label>
                                        <input type="text" name="author_name" id="author_name" class="form-control" value="{{ old('author_name') }}" placeholder="Optional — auto-filled from Written credit">
                                    </div>
                                    <!-- Promo Type -->
                                    <div class="col-md-6 mb-3">
                                        <label for="promo_type">Promo Type</label>
                                        <select name="promo_type" id="promo_type" class="form-control">
                                            <option value="Forex Deposit Bonus">Forex Deposit Bonus</option>
                                            <option value="Forex No Deposit Bonus">Forex No Deposit Bonus</option>
                                            <option value="Forex Live Contest">Forex Live Contest</option>
                                            <option value="Forex Demo Contest">Forex Demo Contest</option>
                                            <option value="Forex Cashback Rebate">Forex Cashback Rebate</option>
                                            <option value="Crypto Bonus Promotion">Crypto Bonus Promotion</option>
                                        </select>
                                    </div>
                                    <!-- Slug -->
                                    <div class="col-md-6 mb-3">
                                        <label for="slug">Slug</label>
                                        <input type="text" name="slug" id="slug" class="form-control">
                                    </div>
                                    <!-- Feature Image -->
                                    <div class="col-md-6 mb-3">
                                        @include('admin.partials._image_upload_preview', [
                                            'inputId' => 'feature_image',
                                            'previewId' => 'image_preview',
                                            'label' => 'Feature Image',
                                            'required' => true,
                                        ])
                                    </div>
                                </div>

                                @include('admin.partials._editorial_fields', ['model' => null, 'editorialOptions' => $editorialOptions])
                            </div>
                        </div>
                    </div>

                    <!-- Links and Financials Section -->
                    <div class="card mb-2">
                        <div class="card-header" id="headingLinks">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseLinks" aria-expanded="false" aria-controls="collapseLinks">
                                    Links and Financials
                                </button>
                            </h5>
                        </div>
                        <div id="collapseLinks" class="collapse" aria-labelledby="headingLinks" data-parent="#accordion">
                            <div class="card-body">
                                <div class="row">
                                    <!-- Link -->
                                    <div class="col-md-6 mb-3">
                                        <label for="link">Link</label>
                                        <input type="url" name="link" id="link" class="form-control">
                                    </div>
                                    <!-- Affiliate Link -->
                                    <div class="col-md-6 mb-3">
                                        <label for="affiliate_link">Affiliate Link</label>
                                        <input type="url" name="affiliate_link" id="affiliate_link" class="form-control">
                                    </div>
                                    <!-- Minimum Deposit -->
                                    <div class="col-md-4 mb-3">
                                        <label for="min_deposit">Minimum Deposit ($)</label>
                                        <input type="number" step="0.01" name="min_deposit" id="min_deposit" class="form-control" value="{{ old('min_deposit') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="bonus_amount">Bonus Amount ($)</label>
                                        <input type="number" step="0.01" name="bonus_amount" id="bonus_amount" class="form-control" value="{{ old('bonus_amount') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="bonus_percentage">Bonus Percentage (%)</label>
                                        <input type="number" step="0.01" name="bonus_percentage" id="bonus_percentage" class="form-control" value="{{ old('bonus_percentage') }}">
                                    </div>
                                    <!-- Terms and Conditions URL -->
                                    <div class="col-md-6 mb-3">
                                        <label for="terms_conditions_url">Terms and Conditions URL</label>
                                        <input type="url" name="terms_conditions_url" id="terms_conditions_url" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bonus Details Section -->
                    <div class="card mb-2">
                        <div class="card-header" id="headingBonus">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseBonus" aria-expanded="false" aria-controls="collapseBonus">
                                    Bonus Details
                                </button>
                            </h5>
                        </div>
                        <div id="collapseBonus" class="collapse" aria-labelledby="headingBonus" data-parent="#accordion">
                            <div class="card-body">
                                <div class="row">
                                    <!-- Bonus Category -->
                                    <div class="col-md-6 mb-3">
                                        <label for="bonus_category">Bonus Category</label>
                                        <input type="text" name="bonus_category" id="bonus_category" class="form-control">
                                    </div>
                                    <!-- Expiry Date -->
                                    <div class="col-md-6 mb-3">
                                        <label for="expiry_date">Expiry Date</label>
                                        <input type="date" name="expiry_date" id="expiry_date" class="form-control">
                                    </div>
                                    <!-- Promotion Status -->
                                    <div class="col-md-4 mb-3">
                                        <label for="promotion_status">Promotion Status</label>
                                        <select name="promotion_status" id="promotion_status" class="form-control">
                                            <option value="ongoing">Ongoing</option>
                                            <option value="limited-time">Limited Time</option>
                                            <option value="expired">Expired</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3 d-flex align-items-end">
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input" id="is_featured" name="is_featured" value="1" @checked(old('is_featured'))>
                                            <label class="custom-control-label font-weight-bold" for="is_featured">Featured on homepage</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Details Section -->
                    <div class="card mb-2">
                        <div class="card-header" id="headingContent">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseContent" aria-expanded="false" aria-controls="collapseContent">
                                    Content Details
                                </button>
                            </h5>
                        </div>
                        <div id="collapseContent" class="collapse" aria-labelledby="headingContent" data-parent="#accordion">
                            <div class="card-body">
                                <div class="row">
                                    <!-- Description -->
                                    <div class="col-md-6 mb-3">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description" class="form-control snote" rows="4" required></textarea>
                                    </div>
                                    <!-- Country Restrictions -->
                                    <div class="col-md-6 mb-3">
                                        <label for="participate">Country Restrictions</label>
                                        <textarea name="participate" id="participate" class="form-control snote" rows="4" required></textarea>
                                    </div>
                                    <!-- How to Participate -->
                                    <div class="col-md-6 mb-3">
                                        <label for="how_to_participate">How to Participate</label>
                                        <textarea name="how_to_participate" id="how_to_participate" class="form-control snote" rows="4" required></textarea>
                                    </div>
                                    <!-- Details -->
                                    <div class="col-md-6 mb-3">
                                        <label for="details">Details</label>
                                        <textarea name="details" id="details" class="form-control snote" rows="4" required></textarea>
                                    </div>
                                    <!-- General Terms -->
                                    <div class="col-md-6 mb-3">
                                        <label for="general_terms">General Terms</label>
                                        <textarea name="general_terms" id="general_terms" class="form-control snote" rows="4" required></textarea>
                                    </div>
                                    <!-- Prize -->
                                    <div class="col-md-6 mb-3">
                                        <label for="prize">Prize</label>
                                        <textarea name="prize" id="prize" class="form-control snote" rows="4" required></textarea>
                                    </div>
                                    <!-- Eligibility Criteria -->
                                    <div class="col-md-6 mb-3">
                                        <label for="eligibility_criteria">Eligibility Criteria</label>
                                        <textarea name="eligibility_criteria" id="eligibility_criteria" class="form-control snote" rows="4"></textarea>
                                    </div>
                                    <!-- Bonus Type Details -->
                                    <div class="col-md-6 mb-3">
                                        <label for="bonus_type_details">Bonus Type Details</label>
                                        <textarea name="bonus_type_details" id="bonus_type_details" class="form-control snote" rows="4"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SEO Settings Section -->
                    <div class="card mb-2">
                        <div class="card-header" id="headingSEO">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseSEO" aria-expanded="false" aria-controls="collapseSEO">
                                    SEO Settings
                                </button>
                            </h5>
                        </div>
                        <div id="collapseSEO" class="collapse" aria-labelledby="headingSEO" data-parent="#accordion">
                            <div class="card-body">
                                <div class="row">
                                    <!-- Meta Title -->
                                    <div class="col-md-6 mb-3">
                                        <label for="meta_title">Meta Title</label>
                                        <input type="text" class="form-control" id="meta_title" name="meta_title" value="{{ old('meta_title') }}" maxlength="255" placeholder="Enter Meta Title">
                                    </div>
                                    <!-- Meta Keywords -->
                                    <div class="col-md-6 mb-3">
                                        <label for="meta_keywords">Meta Keywords</label>
                                        <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords') }}" placeholder="e.g. forex, no deposit bonus, broker bonus">
                                        <small class="form-text text-muted">Separate keywords with commas</small>
                                    </div>
                                    <!-- Meta Description -->
                                    <div class="col-md-12 mb-3">
                                        <label for="meta_description">Meta Description</label>
                                        <textarea class="form-control snote" id="meta_description" name="meta_description" rows="4" maxlength="255" placeholder="Enter Meta Description (max 255 characters)">{{ old('meta_description') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Create Forex Bonus</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection