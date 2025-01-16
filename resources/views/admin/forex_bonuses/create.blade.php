@extends('admin.layout.app')

@section('heading', 'Add Category')

@section('button')
<a href="{{ route('admin_category_show') }}" class="btn btn-primary"><i class="fas fa-eye"></i> View</a>
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

                <!-- Title -->
                <div class="form-group">
                    <label for="title">Blog Title</label>
                    <input type="text" name="title" id="title" class="form-control" required>
                </div>

                <!-- Publish Date -->
                <div class="form-group">
                    <label for="publish_date">Publish Date</label>
                    <input type="date" name="publish_date" id="publish_date" class="form-control" required>
                </div>

                <!-- Author Name -->
                <div class="form-group">
                    <label for="author_name">Author Name</label>
                    <input type="text" name="author_name" id="author_name" class="form-control" required>
                </div>

                <!-- Promo Type -->
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

                <!-- Description -->
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-control snote" rows="5"
                        required></textarea>
                </div>

                <!-- Feature Image -->
                <div class="form-group">
                    <label for="feature_image">Feature Image</label>
                    <input type="file" name="feature_image" id="feature_image" class="form-control" accept="image/*"
                        required>
                </div>

                <!-- Link -->
                <div class="form-group">
                    <label for="link">Link</label>
                    <input type="url" name="link" id="link" class="form-control" required>
                </div>

                <!-- Participate -->
                <div class="form-group">
                    <label for="participate">Country Restrictions</label>
                    <textarea name="participate" id="participate" class="form-control snote" rows="3"
                        required></textarea>
                </div>

                <!-- How to Participate -->
                <div class="form-group">
                    <label for="how_to_participate">How to Participate</label>
                    <textarea name="how_to_participate" id="how_to_participate" class="form-control snote" rows="3"
                        required></textarea>
                </div>

                <!-- Details -->
                <div class="form-group">
                    <label for="details">Details</label>
                    <textarea name="details" id="details" class="form-control snote" rows="3" required></textarea>
                </div>

                <!-- General Terms -->
                <div class="form-group">
                    <label for="general_terms">General Terms</label>
                    <textarea name="general_terms" id="general_terms" class="form-control snote" rows="3"
                        required></textarea>
                </div>

                <!-- Prize -->
                <div class="form-group">
                    <label for="prize">Prize</label>
                    <textarea name="prize" id="prize" class="form-control snote" rows="3" required></textarea>
                </div>

                <div class="form-group">
                    <label for="slug">Slug</label>
                    <input type="text" name="slug" id="slug" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="eligibility_criteria">Eligibility Criteria</label>
                    <textarea name="eligibility_criteria" id="eligibility_criteria" class="form-control snote"
                        rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label for="expiry_date">Expiry Date</label>
                    <input type="date" name="expiry_date" id="expiry_date" class="form-control">
                </div>

                <div class="form-group">
                    <label for="min_deposit">Minimum Deposit</label>
                    <input type="number" step="0.01" name="min_deposit" id="min_deposit" class="form-control">
                </div>

                <div class="form-group">
                    <label for="bonus_type_details">Bonus Type Details</label>
                    <textarea name="bonus_type_details" id="bonus_type_details" class="form-control snote"
                        rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label for="terms_conditions_url">Terms and Conditions URL</label>
                    <input type="url" name="terms_conditions_url" id="terms_conditions_url" class="form-control">
                </div>

                <div class="form-group">
                    <label for="affiliate_link">Affiliate Link</label>
                    <input type="url" name="affiliate_link" id="affiliate_link" class="form-control">
                </div>

                <div class="form-group">
                    <label for="bonus_category">Bonus Category</label>
                    <input type="text" name="bonus_category" id="bonus_category" class="form-control">
                </div>

                <div class="form-group">
                    <label for="promotion_status">Promotion Status</label>
                    <select name="promotion_status" id="promotion_status" class="form-control">
                        <option value="ongoing">Ongoing</option>
                        <option value="limited-time">Limited Time</option>
                        <option value="expired">Expired</option>
                    </select>
                </div>


                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary mb-3">Create Forex Bonus</button>
            </form>
        </div>
    </div>
</div>


@endsection