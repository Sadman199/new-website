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
                <!-- Include the method directive for PUT request -->

                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" name="title" id="title" class="form-control" value="{{ $forexBonus->title }}"
                        required>
                </div>

                <div class="form-group">
                    <label for="slug">Slug</label>
                    <input type="text" name="slug" id="slug" class="form-control"
                        value="{{ old('slug', $forexBonus->slug) }}">
                </div>

                <div class="form-group">
                    <label for="publish_date">Publish Date</label>
                    <input type="date" name="publish_date" class="form-control" id="publish_date"
                        value="{{ $forexBonus->publish_date }}" required>
                </div>

                <div class="form-group">
                    <label for="author_name">Author Name</label>
                    <input type="text" name="author_name" class="form-control" id="author_name"
                        value="{{ $forexBonus->author_name }}" required>
                </div>

                <div class="form-group">
                    <label for="promo_type">Promo Type</label>
                    <select name="promo_type" class="form-control" id="promo_type" required>
                        <option value="Forex Deposit Bonus"
                            {{ $forexBonus->promo_type == 'Forex Deposit Bonus' ? 'selected' : '' }}>Forex Deposit Bonus
                        </option>
                        <option value="Forex No Deposit Bonus"
                            {{ $forexBonus->promo_type == 'Forex No Deposit Bonus' ? 'selected' : '' }}>Forex No Deposit
                            Bonus</option>
                        <option value="Forex Live Contest"
                            {{ $forexBonus->promo_type == 'Forex Live Contest' ? 'selected' : '' }}>Forex Live Contest
                        </option>
                        <option value="Forex Demo Contest"
                            {{ $forexBonus->promo_type == 'Forex Demo Contest' ? 'selected' : '' }}>Forex Demo Contest
                        </option>
                        <option value="Forex Cashback Rebate"
                            {{ $forexBonus->promo_type == 'Forex Cashback Rebate' ? 'selected' : '' }}>Forex Cashback
                            Rebate</option>
                        <option value="Crypto Bonus Promotion"
                            {{ $forexBonus->promo_type == 'Crypto Bonus Promotion' ? 'selected' : '' }}>Crypto Bonus
                            Promotion</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" class="form-control snote" id="description"
                        required>{{ $forexBonus->description }}</textarea>
                </div>

                <div class="form-group">
                    <label for="feature_image">Feature Image</label>
                    <input type="file" name="feature_image" class="form-control" id="feature_image">
                    <!-- Display current image if available -->
                    @if($forexBonus->feature_image)
                    <img src="{{ asset('storage/' . $forexBonus->feature_image) }}" alt="Current Feature Image"
                        width="150">
                    @endif
                </div>

                <div class="form-group">
                    <label for="link">Link</label>
                    <input type="url" name="link" id="link" class="form-control" value="{{ $forexBonus->link }}"
                        required>
                </div>

                <div class="form-group">
                    <label for="participate">Country Restrictions</label>
                    <textarea type="text" name="participate" class="form-control snote" id="participate"
                         required>{{ $forexBonus->participate }}</textarea>
                </div>

                <div class="form-group">
                    <label for="how_to_participate">How to Participate</label>
                    <textarea name="how_to_participate" class="form-control snote" id="how_to_participate"
                        required>{{ $forexBonus->how_to_participate }}</textarea>
                </div>

                <div class="form-group">
                    <label for="details">Details</label>
                    <textarea name="details" class="form-control snote" id="details"
                        required>{{ $forexBonus->details }}</textarea>
                </div>

                <div class="form-group">
                    <label for="general_terms">General Terms</label>
                    <textarea name="general_terms" class="form-control snote" id="general_terms"
                        required>{{ $forexBonus->general_terms }}</textarea>
                </div>

                <div class="form-group">
                    <label for="prize">Prize</label>
                    <input type="text" name="prize" class="form-control" id="prize"
                        value="{!! $forexBonus->prize !!}" required> <!-- Raw HTML content -->
                </div>



                <!-- New Fields -->
                <div class="form-group">
                    <label for="eligibility_criteria">Eligibility Criteria</label>
                    <textarea name="eligibility_criteria" id="eligibility_criteria" class="form-control snote"
                        rows="3">{{ old('eligibility_criteria', $forexBonus->eligibility_criteria) }}</textarea>
                </div>

                <div class="form-group">
                    <label for="expiry_date">Expiry Date</label>
                    <input type="date" name="expiry_date" id="expiry_date" class="form-control"
                        value="{{ old('expiry_date', $forexBonus->expiry_date) }}">
                </div>

                <div class="form-group">
                    <label for="min_deposit">Minimum Deposit</label>
                    <input type="number" name="min_deposit" id="min_deposit" class="form-control"
                        value="{{ old('min_deposit', $forexBonus->min_deposit) }}" step="0.01" min="0">
                </div>

                <div class="form-group">
                    <label for="bonus_type_details">Bonus Type Details</label>
                    <textarea name="bonus_type_details" id="bonus_type_details" class="form-control snote"
                        rows="3">{{ old('bonus_type_details', $forexBonus->bonus_type_details) }}</textarea>
                </div>

                <div class="form-group">
                    <label for="terms_conditions_url">Terms and Conditions URL</label>
                    <input type="url" name="terms_conditions_url" id="terms_conditions_url" class="form-control"
                        value="{{ old('terms_conditions_url', $forexBonus->terms_conditions_url) }}">
                </div>

                <div class="form-group">
                    <label for="affiliate_link">Affiliate Link</label>
                    <input type="url" name="affiliate_link" id="affiliate_link" class="form-control"
                        value="{{ old('affiliate_link', $forexBonus->affiliate_link) }}">
                </div>

                <div class="form-group">
                    <label for="bonus_category">Bonus Category</label>
                    <input type="text" name="bonus_category" id="bonus_category" class="form-control"
                        value="{{ old('bonus_category', $forexBonus->bonus_category) }}">
                </div>

                <div class="form-group">
                    <label for="promotion_status">Promotion Status</label>
                    <select name="promotion_status" id="promotion_status" class="form-control" required>
                        <option value="ongoing" {{ $forexBonus->promotion_status == 'ongoing' ? 'selected' : '' }}>
                            Ongoing</option>
                        <option value="limited-time"
                            {{ $forexBonus->promotion_status == 'limited-time' ? 'selected' : '' }}>Limited-Time
                        </option>
                        <option value="expired" {{ $forexBonus->promotion_status == 'expired' ? 'selected' : '' }}>
                            Expired</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Update Forex Bonus</button>
            </form>
        </div>
    </div>
</div>
@endsection