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
                                    <!-- Publish Date -->
                                    <div class="col-md-6 mb-3">
                                        <label for="publish_date">Publish Date</label>
                                        <input type="date" name="publish_date" id="publish_date" class="form-control">
                                    </div>
                                    <!-- Author Name -->
                                    <div class="col-md-6 mb-3">
                                        <label for="author_name">Author Name</label>
                                        <input type="text" name="author_name" id="author_name" class="form-control">
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
                                        <label for="feature_image">Feature Image</label>
                                        <input type="file" name="feature_image" id="feature_image" class="form-control-file" accept="image/*">
                                        <div class="mt-3">
                                            <label>Image Preview:</label>
                                            <img id="image_preview" src="#" alt="Preview Image" style="display: none; max-width: 150px; height: auto; border: 1px solid #ddd; padding: 5px;">
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
                                    <div class="col-md-6 mb-3">
                                        <label for="min_deposit">Minimum Deposit</label>
                                        <input type="number" step="0.01" name="min_deposit" id="min_deposit" class="form-control" placeholder="Enter minimum deposit amount">
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
                                    <div class="col-md-6 mb-3">
                                        <label for="promotion_status">Promotion Status</label>
                                        <select name="promotion_status" id="promotion_status" class="form-control">
                                            <option value="ongoing">Ongoing</option>
                                            <option value="limited-time">Limited Time</option>
                                            <option value="expired">Expired</option>
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

<!-- Image Preview Script -->
<script>
$(document).ready(function() {
    $('#feature_image').on('change', function() {
        const file = this.files[0];
        const preview = $('#image_preview');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.attr('src', e.target.result);
                preview.show();
            };
            reader.readAsDataURL(file);
        } else {
            preview.attr('src', '#');
            preview.hide();
        }
    });
});
</script>
@endsection