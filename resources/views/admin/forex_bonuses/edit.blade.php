@extends('admin.layout.app')

@section('heading', 'Edit Post')

@section('button')
<a href="{{ route('admin_post_show') }}" class="btn btn-primary"><i class="fas fa-eye"></i> View</a>
@endsection

@section('main_content')
<div class="section-body">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin_forex_bonus_update', $forexBonus->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <!-- Title -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="title">Blog Title</label>
                            <input type="text" name="title" id="title" class="form-control"
                                value="{{ old('title', $forexBonus->title) }}" required>
                        </div>
                    </div>

                    <!-- Publish Date -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="publish_date">Publish Date</label>
                            <input type="date" name="publish_date" id="publish_date" class="form-control"
                                value="{{ old('publish_date', $forexBonus->publish_date) }}" required>
                        </div>
                    </div>

                    <!-- Author Name -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="author_name">Author Name</label>
                            <input type="text" name="author_name" id="author_name" class="form-control"
                                value="{{ old('author_name', $forexBonus->author_name) }}" required>
                        </div>
                    </div>

                    <!-- Promo Type -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="promo_type">Promo Type</label>
                            <select name="promo_type" id="promo_type" class="form-control" required>
                                <option value="Forex Deposit Bonus"
                                    {{ old('promo_type', $forexBonus->promo_type) == 'Forex Deposit Bonus' ? 'selected' : '' }}>
                                    Forex Deposit Bonus</option>
                                <option value="Forex No Deposit Bonus"
                                    {{ old('promo_type', $forexBonus->promo_type) == 'Forex No Deposit Bonus' ? 'selected' : '' }}>
                                    Forex No Deposit Bonus</option>
                                <option value="Forex Live Contest"
                                    {{ old('promo_type', $forexBonus->promo_type) == 'Forex Live Contest' ? 'selected' : '' }}>
                                    Forex Live Contest</option>
                                <option value="Forex Demo Contest"
                                    {{ old('promo_type', $forexBonus->promo_type) == 'Forex Demo Contest' ? 'selected' : '' }}>
                                    Forex Demo Contest</option>
                                <option value="Forex Cashback Rebate"
                                    {{ old('promo_type', $forexBonus->promo_type) == 'Forex Cashback Rebate' ? 'selected' : '' }}>
                                    Forex Cashback Rebate</option>
                                <option value="Crypto Bonus Promotion"
                                    {{ old('promo_type', $forexBonus->promo_type) == 'Crypto Bonus Promotion' ? 'selected' : '' }}>
                                    Crypto Bonus Promotion</option>
                            </select>
                        </div>
                    </div>

                    <!-- Slug -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <input type="text" name="slug" id="slug" class="form-control"
                                value="{{ old('slug', $forexBonus->slug) }}" required>
                        </div>
                    </div>



                    <!-- Feature Image -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="feature_image">Feature Image</label>
                            <input type="file" name="feature_image" id="feature_image" class="form-control"
                                accept="image/*">
                        </div>
                        <div class="form-group mt-3">
                            <label for="image_preview">Image Preview:</label>
                            <img id="image_preview"
                                src="{{ $forexBonus->feature_image ? asset($forexBonus->feature_image) : '#' }}"
                                alt="Preview Image"
                                style="width: 150px; height: auto; border: 1px solid #ddd; padding: 5px; {{ $forexBonus->feature_image ? '' : 'display: none;' }}">
                        </div>
                        <script>
                        $(document).ready(function() {
                            $('#feature_image').on('change', function() {
                                const file = this.files[0];
                                const preview = $('#image_preview');

                                if (file) {
                                    const reader = new FileReader();

                                    reader.onload = function(e) {
                                        preview.attr('src', e.target.result);
                                        preview.show(); // Show the image preview
                                    };

                                    reader.readAsDataURL(file); // Convert the file to a data URL
                                } else {
                                    preview.attr('src', '#');
                                    preview.hide(); // Hide the image preview if no file is selected
                                }
                            });
                        });
                        </script>
                    </div>

                    <!-- Link -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="link">Link</label>
                            <input type="url" name="link" id="link" class="form-control"
                                value="{{ old('link', $forexBonus->link) }}" required>
                        </div>
                    </div>

                    <!-- Affiliate Link -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="affiliate_link">Affiliate Link</label>
                            <input type="url" name="affiliate_link" id="affiliate_link" class="form-control"
                                value="{{ old('affiliate_link', $forexBonus->affiliate_link) }}">
                        </div>
                    </div>

                    <!-- Minimum Deposit -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="min_deposit">Minimum Deposit</label>
                            <input type="number" step="0.01" name="min_deposit" id="min_deposit" class="form-control"
                                value="{{ old('min_deposit', $forexBonus->min_deposit) }}"
                                placeholder="Enter minimum deposit amount">
                        </div>
                    </div>

                    <!-- Terms and Conditions URL -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="terms_conditions_url">Terms and Conditions URL</label>
                            <input type="url" name="terms_conditions_url" id="terms_conditions_url" class="form-control"
                                value="{{ old('terms_conditions_url', $forexBonus->terms_conditions_url) }}">
                        </div>
                    </div>

                    <!-- Bonus Category -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="bonus_category">Bonus Category</label>
                            <input type="text" name="bonus_category" id="bonus_category" class="form-control"
                                value="{{ old('bonus_category', $forexBonus->bonus_category) }}"
                                placeholder="Enter bonus category">
                        </div>
                    </div>

                    <!-- Expiry Date -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="expiry_date">Expiry Date</label>
                            <input type="date" name="expiry_date" id="expiry_date" class="form-control"
                                value="{{ old('expiry_date', $forexBonus->expiry_date) }}">
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control snote" rows="5" required
                                placeholder="Provide a detailed description">{{ old('description', $forexBonus->description) }}</textarea>
                        </div>
                    </div>

                    <!-- Country Restrictions -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="participate">Country Restrictions</label>
                            <textarea name="participate" id="participate" class="form-control snote" rows="3" required
                                placeholder="Mention any country restrictions">{{ old('participate', $forexBonus->participate) }}</textarea>
                        </div>
                    </div>

                    <!-- How to Participate -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="how_to_participate">How to Participate</label>
                            <textarea name="how_to_participate" id="how_to_participate" class="form-control snote"
                                rows="3" required
                                placeholder="Explain how users can participate">{{ old('how_to_participate', $forexBonus->how_to_participate) }}</textarea>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="details">Details</label>
                            <textarea name="details" id="details" class="form-control snote" rows="3" required
                                placeholder="Provide detailed information">{{ old('details', $forexBonus->details) }}</textarea>
                        </div>
                    </div>

                    <!-- General Terms -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="general_terms">General Terms</label>
                            <textarea name="general_terms" id="general_terms" class="form-control snote" rows="3"
                                required
                                placeholder="Enter general terms">{{ old('general_terms', $forexBonus->general_terms) }}</textarea>
                        </div>
                    </div>

                    <!-- Prize -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="prize">Prize</label>
                            <textarea name="prize" id="prize" class="form-control snote" rows="3" required
                                placeholder="Enter prize details">{{ old('prize', $forexBonus->prize) }}</textarea>
                        </div>
                    </div>

                    <!-- Eligibility Criteria -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="eligibility_criteria">Eligibility Criteria</label>
                            <textarea name="eligibility_criteria" id="eligibility_criteria" class="form-control snote"
                                rows="3"
                                placeholder="Specify eligibility criteria">{{ old('eligibility_criteria', $forexBonus->eligibility_criteria) }}</textarea>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Bonus Type Details -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bonus_type_details">Bonus Type Details</label>
                                <textarea name="bonus_type_details" id="bonus_type_details" class="form-control snote"
                                    rows="3"
                                    placeholder="Provide bonus type details">{{ old('bonus_type_details', $forexBonus->bonus_type_details) }}</textarea>
                            </div>
                        </div>

                        <!-- Promotion Status -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="promotion_status">Promotion Status</label>
                                <select name="promotion_status" id="promotion_status" class="form-control">
                                    <option value="ongoing"
                                        {{ old('promotion_status', $forexBonus->promotion_status) == 'ongoing' ? 'selected' : '' }}>
                                        Ongoing</option>
                                    <option value="limited-time"
                                        {{ old('promotion_status', $forexBonus->promotion_status) == 'limited-time' ? 'selected' : '' }}>
                                        Limited Time</option>
                                    <option value="expired"
                                        {{ old('promotion_status', $forexBonus->promotion_status) == 'expired' ? 'selected' : '' }}>
                                        Expired</option>
                                </select>
                            </div>
                        </div>
                        <!-- Submit Button -->
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary mb-3">Update Forex Bonus</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection