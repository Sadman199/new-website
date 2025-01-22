@extends('admin.layout.app')
@section('heading', 'Add Bonus')
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
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <!-- Form to create a new Forex Bonus -->
            <form action="{{ route('admin_forex_bonus_store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <!-- Title -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="title">Blog Title</label>
                            <input type="text" name="title" id="title" class="form-control" required>
                        </div>
                    </div>
                    <!-- Publish Date -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="publish_date">Publish Date</label>
                            <input type="date" name="publish_date" id="publish_date" class="form-control" required>
                        </div>
                    </div>
                    <!-- Author Name -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="author_name">Author Name</label>
                            <input type="text" name="author_name" id="author_name" class="form-control" required>
                        </div>
                    </div>
                    <!-- Promo Type -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="promo_type">Promo Type</label>
                            <select name="promo_type" id="promo_type" class="form-control" required>
                                <option value="Forex Deposit Bonus">Forex Deposit Bonus</option>
                                <option value="Forex No Deposit Bonus">Forex No Deposit Bonus</option>
                                <option value="Forex Live Contest">Forex Live Contest</option>
                                <option value="Forex Demo Contest">Forex Demo Contest</option>
                                <option value="Forex Cashback Rebate">Forex Cashback Rebate</option>
                                <option value="Crypto Bonus Promotion">Crypto Bonus Promotion</option>
                            </select>
                        </div>
                    </div>
                    <!-- Slug -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <input type="text" name="slug" id="slug" class="form-control" required>
                        </div>
                    </div>
                    <!-- Feature Image -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="feature_image">Feature Image</label>
                            <input type="file" name="feature_image" id="feature_image" class="form-control"
                                accept="image/*" required>
                        </div>
                        <div class="form-group mt-3">
                            <label for="image_preview">Image Preview:</label>
                            <img id="image_preview" src="#" alt="Preview Image"
                                style="display: none; width: 150px; height: auto; border: 1px solid #ddd; padding: 5px;">
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
                            <input type="url" name="link" id="link" class="form-control" required>
                        </div>
                    </div>
                    <!-- Affiliate Link -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="affiliate_link">Affiliate Link</label>
                            <input type="url" name="affiliate_link" id="affiliate_link" class="form-control">
                        </div>
                    </div>
                    <!-- Minimum Deposit -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="min_deposit">Minimum Deposit</label>
                            <input type="number" step="0.01" name="min_deposit" id="min_deposit" class="form-control"
                                placeholder="Enter minimum deposit amount">
                        </div>
                    </div>
                    <!-- Terms and Conditions URL -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="terms_conditions_url">Terms and Conditions URL</label>
                            <input type="url" name="terms_conditions_url" id="terms_conditions_url"
                                class="form-control">
                        </div>
                    </div>
                    <!-- Bonus Category -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="bonus_category">Bonus Category</label>
                            <input type="text" name="bonus_category" id="bonus_category" class="form-control">
                        </div>
                    </div>
                    <!-- Expiry Date -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="expiry_date">Expiry Date</label>
                            <input type="date" name="expiry_date" id="expiry_date" class="form-control">
                        </div>
                    </div>
                    <!-- Description -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control snote" rows="5"
                                required></textarea>
                        </div>
                    </div>
                    <!-- Participate -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="participate">Country Restrictions</label>
                            <textarea name="participate" id="participate" class="form-control snote" rows="3"
                                required></textarea>
                        </div>
                    </div>
                    <!-- How to Participate -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="how_to_participate">How to Participate</label>
                            <textarea name="how_to_participate" id="how_to_participate" class="form-control snote"
                                rows="3" required></textarea>
                        </div>
                    </div>
                    <!-- Details -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="details">Details</label>
                            <textarea name="details" id="details" class="form-control snote" rows="3"
                                required></textarea>
                        </div>
                    </div>
                    <!-- General Terms -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="general_terms">General Terms</label>
                            <textarea name="general_terms" id="general_terms" class="form-control snote" rows="3"
                                required></textarea>
                        </div>
                    </div>
                    <!-- Prize -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="prize">Prize</label>
                            <textarea name="prize" id="prize" class="form-control snote" rows="3" required></textarea>
                        </div>
                    </div>
                    <!-- Eligibility Criteria -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="eligibility_criteria">Eligibility Criteria</label>
                            <textarea name="eligibility_criteria" id="eligibility_criteria" class="form-control snote"
                                rows="3"></textarea>
                        </div>
                    </div>
                    <!-- Bonus Type Details -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="bonus_type_details">Bonus Type Details</label>
                            <textarea name="bonus_type_details" id="bonus_type_details" class="form-control snote"
                                rows="3"></textarea>
                        </div>
                    </div>
                    <!-- Promotion Status -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="promotion_status">Promotion Status</label>
                            <select name="promotion_status" id="promotion_status" class="form-control">
                                <option value="ongoing">Ongoing</option>
                                <option value="limited-time">Limited Time</option>
                                <option value="expired">Expired</option>
                            </select>
                        </div>
                    </div>
                    <!-- Submit Button -->
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary mb-3">Create Forex Bonus</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection