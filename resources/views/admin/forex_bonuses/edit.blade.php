@extends('admin.layout.app')
@section('heading', 'Edit Forex Bonus')
@section('button')
<a href="{{ route('admin_forex_bonus_show') }}" class="btn btn-primary"><i class="fas fa-eye"></i> View</a>
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

            <!-- Form to update a Forex Bonus -->
            <form id="forexBonusForm" action="{{ route('admin_forex_bonus_update', $forexBonus->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div id="accordion">
                    <!-- Basic Information Section -->
                    <div class="card mb-2">
                        <div class="card-header" id="headingBasic">
                            <h5 class="mb-0">
                                <a href="#collapseBasic" class="btn btn-link" data-toggle="collapse" aria-expanded="true" aria-controls="collapseBasic">
                                    Basic Information
                                </a>
                            </h5>
                        </div>
                        <div id="collapseBasic" class="collapse show" aria-labelledby="headingBasic" data-parent="#accordion">
                            <div class="card-body">
                                <div class="row">
                                    <!-- Title -->
                                    <div class="col-md-6 mb-3">
                                        <label for="title">Blog Title</label>
                                        <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $forexBonus->title) }}" required>
                                    </div>
                                    <!-- Publish Date -->
                                    <div class="col-md-6 mb-3">
                                        <label for="publish_date">Publish Date</label>
                                        <input type="date" name="publish_date" id="publish_date" class="form-control" value="{{ old('publish_date', $forexBonus->publish_date) }}" required>
                                    </div>
                                    <!-- Author Name -->
                                    <div class="col-md-6 mb-3">
                                        <label for="author_name">Author Name</label>
                                        <input type="text" name="author_name" id="author_name" class="form-control" value="{{ old('author_name', $forexBonus->author_name) }}" required>
                                    </div>
                                    <!-- Promo Type -->
                                    <div class="col-md-6 mb-3">
                                        <label for="promo_type">Promo Type</label>
                                        <select name="promo_type" id="promo_type" class="form-control" required>
                                            <option value="Forex Deposit Bonus" {{ old('promo_type', $forexBonus->promo_type) == 'Forex Deposit Bonus' ? 'selected' : '' }}>Forex Deposit Bonus</option>
                                            <option value="Forex No Deposit Bonus" {{ old('promo_type', $forexBonus->promo_type) == 'Forex No Deposit Bonus' ? 'selected' : '' }}>Forex No Deposit Bonus</option>
                                            <option value="Forex Live Contest" {{ old('promo_type', $forexBonus->promo_type) == 'Forex Live Contest' ? 'selected' : '' }}>Forex Live Contest</option>
                                            <option value="Forex Demo Contest" {{ old('promo_type', $forexBonus->promo_type) == 'Forex Demo Contest' ? 'selected' : '' }}>Forex Demo Contest</option>
                                            <option value="Forex Cashback Rebate" {{ old('promo_type', $forexBonus->promo_type) == 'Forex Cashback Rebate' ? 'selected' : '' }}>Forex Cashback Rebate</option>
                                            <option value="Crypto Bonus Promotion" {{ old('promo_type', $forexBonus->promo_type) == 'Crypto Bonus Promotion' ? 'selected' : '' }}>Crypto Bonus Promotion</option>
                                        </select>
                                    </div>
                                    <!-- Slug -->
                                    <div class="col-md-6 mb-3">
                                        <label for="slug">Slug</label>
                                        <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $forexBonus->slug) }}" required>
                                    </div>
                                    <!-- Feature Image -->
                                    <div class="col-md-6 mb-3">
                                        <label for="feature_image">Feature Image</label>
                                        <input type="file" name="feature_image" id="feature_image" class="form-control-file" accept="image/*">
                                        <div class="mt-3">
                                            <label>Image Preview:</label>
                                            <img id="image_preview" src="{{ $forexBonus->feature_image ? asset($forexBonus->feature_image) : '#' }}" alt="Preview Image" style="max-width: 150px; height: auto; border: 1px solid #ddd; padding: 5px; {{ $forexBonus->feature_image ? '' : 'display: none;' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Links and Financials Section -->
                    <div class="card mb-2">
                        <div class="card-header" id="headingLinks">
                            <h5 class="mb-0">
                                <a href="#collapseLinks" class="btn btn-link collapsed" data-toggle="collapse" aria-expanded="false" aria-controls="collapseLinks">
                                    Links and Financials
                                </a>
                            </h5>
                        </div>
                        <div id="collapseLinks" class="collapse" aria-labelledby="headingLinks" data-parent="#accordion">
                            <div class="card-body">
                                <div class="row">
                                    <!-- Link -->
                                    <div class="col-md-6 mb-3">
                                        <label for="link">Link</label>
                                        <input type="url" name="link" id="link" class="form-control" value="{{ old('link', $forexBonus->link) }}" required>
                                    </div>
                                    <!-- Affiliate Link -->
                                    <div class="col-md-6 mb-3">
                                        <label for="affiliate_link">Affiliate Link</label>
                                        <input type="url" name="affiliate_link" id="affiliate_link" class="form-control" value="{{ old('affiliate_link', $forexBonus->affiliate_link) }}">
                                    </div>
                                    <!-- Minimum Deposit -->
                                    <div class="col-md-6 mb-3">
                                        <label for="min_deposit">Minimum Deposit</label>
                                        <input type="number" step="0.01" name="min_deposit" id="min_deposit" class="form-control" value="{{ old('min_deposit', $forexBonus->min_deposit) }}" placeholder="Enter minimum deposit amount">
                                    </div>
                                    <!-- Terms and Conditions URL -->
                                    <div class="col-md-6 mb-3">
                                        <label for="terms_conditions_url">Terms and Conditions URL</label>
                                        <input type="url" name="terms_conditions_url" id="terms_conditions_url" class="form-control" value="{{ old('terms_conditions_url', $forexBonus->terms_conditions_url) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bonus Details Section -->
                    <div class="card mb-2">
                        <div class="card-header" id="headingBonus">
                            <h5 class="mb-0">
                                <a href="#collapseBonus" class="btn btn-link collapsed" data-toggle="collapse" aria-expanded="false" aria-controls="collapseBonus">
                                    Bonus Details
                                </a>
                            </h5>
                        </div>
                        <div id="collapseBonus" class="collapse" aria-labelledby="headingBonus" data-parent="#accordion">
                            <div class="card-body">
                                <div class="row">
                                    <!-- Bonus Category -->
                                    <div class="col-md-6 mb-3">
                                        <label for="bonus_category">Bonus Category</label>
                                        <input type="text" name="bonus_category" id="bonus_category" class="form-control" value="{{ old('bonus_category', $forexBonus->bonus_category) }}" placeholder="Enter bonus category">
                                    </div>
                                    <!-- Expiry Date -->
                                    <div class="col-md-6 mb-3">
                                        <label for="expiry_date">Expiry Date</label>
                                        <input type="date" name="expiry_date" id="expiry_date" class="form-control" value="{{ old('expiry_date', $forexBonus->expiry_date) }}">
                                    </div>
                                    <!-- Promotion Status -->
                                    <div class="col-md-6 mb-3">
                                        <label for="promotion_status">Promotion Status</label>
                                        <select name="promotion_status" id="promotion_status" class="form-control">
                                            <option value="ongoing" {{ old('promotion_status', $forexBonus->promotion_status) == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                            <option value="limited-time" {{ old('promotion_status', $forexBonus->promotion_status) == 'limited-time' ? 'selected' : '' }}>Limited Time</option>
                                            <option value="expired" {{ old('promotion_status', $forexBonus->promotion_status) == 'expired' ? 'selected' : '' }}>Expired</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Details Section -->
                    <div class="card mb-2">
                        <div class="card-header" id="headingContent">
                            <h5 class="mb-0">
                                <a href="#collapseContent" class="btn btn-link collapsed" data-toggle="collapse" aria-expanded="false" aria-controls="collapseContent">
                                    Content Details
                                </a>
                            </h5>
                        </div>
                        <div id="collapseContent" class="collapse" aria-labelledby="headingContent" data-parent="#accordion">
                            <div class="card-body">
                                <div class="row">
                                    <!-- Description -->
                                    <div class="col-md-6 mb-3">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description" class="form-control snote" rows="4" required placeholder="Provide a detailed description">{{ old('description', $forexBonus->description) }}</textarea>
                                    </div>
                                    <!-- Country Restrictions -->
                                    <div class="col-md-6 mb-3">
                                        <label for="participate">Country Restrictions</label>
                                        <textarea name="participate" id="participate" class="form-control snote" rows="4" required placeholder="Mention any country restrictions">{{ old('participate', $forexBonus->participate) }}</textarea>
                                    </div>
                                    <!-- How to Participate -->
                                    <div class="col-md-6 mb-3">
                                        <label for="how_to_participate">How to Participate</label>
                                        <textarea name="how_to_participate" id="how_to_participate" class="form-control snote" rows="4" required placeholder="Explain how users can participate">{{ old('how_to_participate', $forexBonus->how_to_participate) }}</textarea>
                                    </div>
                                    <!-- Details -->
                                    <div class="col-md-6 mb-3">
                                        <label for="details">Details</label>
                                        <textarea name="details" id="details" class="form-control snote" rows="4" required placeholder="Provide detailed information">{{ old('details', $forexBonus->details) }}</textarea>
                                    </div>
                                    <!-- General Terms -->
                                    <div class="col-md-6 mb-3">
                                        <label for="general_terms">General Terms</label>
                                        <textarea name="general_terms" id="general_terms" class="form-control snote" rows="4" required placeholder="Enter general terms">{{ old('general_terms', $forexBonus->general_terms) }}</textarea>
                                    </div>
                                    <!-- Prize -->
                                    <div class="col-md-6 mb-3">
                                        <label for="prize">Prize</label>
                                        <textarea name="prize" id="prize" class="form-control snote" rows="4" required placeholder="Enter prize details">{{ old('prize', $forexBonus->prize) }}</textarea>
                                    </div>
                                    <!-- Eligibility Criteria -->
                                    <div class="col-md-6 mb-3">
                                        <label for="eligibility_criteria">Eligibility Criteria</label>
                                        <textarea name="eligibility_criteria" id="eligibility_criteria" class="form-control snote" rows="4" placeholder="Specify eligibility criteria">{{ old('eligibility_criteria', $forexBonus->eligibility_criteria) }}</textarea>
                                    </div>
                                    <!-- Bonus Type Details -->
                                    <div class="col-md-6 mb-3">
                                        <label for="bonus_type_details">Bonus Type Details</label>
                                        <textarea name="bonus_type_details" id="bonus_type_details" class="form-control snote" rows="4" placeholder="Provide bonus type details">{{ old('bonus_type_details', $forexBonus->bonus_type_details) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SEO Settings Section -->
                    <div class="card mb-2">
                        <div class="card-header" id="headingSEO">
                            <h5 class="mb-0">
                                <a href="#collapseSEO" class="btn btn-link collapsed" data-toggle="collapse" aria-expanded="false" aria-controls="collapseSEO">
                                    SEO Settings
                                </a>
                            </h5>
                        </div>
                        <div id="collapseSEO" class="collapse" aria-labelledby="headingSEO" data-parent="#accordion">
                            <div class="card-body">
                                <div class="row">
                                    <!-- Meta Title -->
                                    <div class="col-md-6 mb-3">
                                        <label for="meta_title">Meta Title</label>
                                        <input type="text" class="form-control" id="meta_title" name="meta_title" value="{{ old('meta_title', $forexBonus->meta_title) }}" maxlength="255" placeholder="Enter Meta Title">
                                    </div>
                                    <!-- Meta Keywords -->
                                    <div class="col-md-6 mb-3">
                                        <label for="meta_keywords">Meta Keywords</label>
                                        <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords', $forexBonus->meta_keywords) }}" placeholder="e.g. forex, no deposit bonus, broker bonus">
                                        <small class="form-text text-muted">Separate keywords with commas</small>
                                    </div>
                                    <!-- Meta Description -->
                                    <div class="col-md-12 mb-3">
                                        <label for="meta_description">Meta Description</label>
                                        <textarea class="form-control snote" id="meta_description" name="meta_description" rows="4" maxlength="255" placeholder="Enter Meta Description (max 255 characters)">{{ old('meta_description', $forexBonus->meta_description) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-3">
                    <button type="submit" id="submitButton" class="btn btn-primary">Update Forex Bonus</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript -->

@endsection