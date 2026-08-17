@php
    $userReview = $userReview ?? null;
    $reviewAccountTypes = $reviewAccountTypes ?? [];
    $lengthOptions = \App\Models\Review::LENGTH_OF_USE_OPTIONS;

    if (session('review_timeout_' . $broker->id) && now()->greaterThan(session('review_timeout_' . $broker->id))) {
        session()->forget(['review_submitted_' . $broker->id, 'review_timeout_' . $broker->id]);
    }

    $dimensionFields = [
        'rating_cost' => 'Cost',
        'rating_platforms' => 'Platforms',
        'rating_customer_support' => 'Customer Support',
    ];
@endphp

<div class="br-rate-review" id="br-rate-review">
    <div class="br-rate-review__head">
        <h3 class="br-rate-review__title">Rate and Review</h3>
        <p class="br-rate-review__desc">Tell us how long you used {{ $broker->name }}, which account you traded, then rate cost, platforms, and support.</p>
    </div>

    @if($userReview && $userReview->isApproved())
        <div class="br-comment-alert br-comment-alert--success">
            Your review is published in Community Comments below. Thanks for sharing your experience.
        </div>
    @elseif($userReview && $userReview->isDeclined())
        <div class="br-comment-alert br-comment-alert--error">
            Your review was not approved. Contact support if you think this was a mistake.
        </div>
    @elseif($userReview && $userReview->isPending())
        <p class="br-rate-review__pending">This review is awaiting moderation. You can update it until it is approved.</p>
        <form action="{{ route('user.reviews.update', $userReview) }}" method="POST" class="br-comment-form" data-br-review-form>
            @csrf
            @method('PUT')
            @include('front.brokers.partials.reviews.form-fields', [
                'review' => $userReview,
                'dimensionFields' => $dimensionFields,
                'lengthOptions' => $lengthOptions,
                'reviewAccountTypes' => $reviewAccountTypes,
                'idPrefix' => 'edit',
            ])
            <div class="br-comment-form__actions">
                <button type="submit" class="br-btn br-btn--primary">Save changes</button>
                <a href="{{ route('user.profile', ['tab' => 'overview']) }}#ua-reviews" class="br-btn br-btn--secondary br-btn--sm">Manage in profile</a>
            </div>
        </form>
    @elseif(session('review_submitted_' . $broker->id))
        <div class="br-comment-alert br-comment-alert--success">
            Your review has been submitted and is pending moderation. Thank you!
        </div>
    @elseif(!auth('web')->check())
        <div class="br-rate-review__guest">
            <p>Log in to rate {{ $broker->name }} and leave a review.</p>
            <button type="button" class="br-btn br-btn--primary" data-br-open-login>
                Write a review
            </button>
        </div>
    @elseif(empty($reviewAccountTypes))
        <div class="br-comment-alert br-comment-alert--error">
            Account types are not available for this broker yet, so reviews cannot be submitted right now.
        </div>
    @else
        <form action="{{ route('reviews.store', $broker->id) }}" method="POST" class="br-comment-form" data-br-review-form>
            @csrf
            <input type="hidden" name="broker_id" value="{{ $broker->id }}">
            @include('front.brokers.partials.reviews.form-fields', [
                'review' => null,
                'dimensionFields' => $dimensionFields,
                'lengthOptions' => $lengthOptions,
                'reviewAccountTypes' => $reviewAccountTypes,
                'idPrefix' => 'new',
            ])
            <button type="submit" class="br-btn br-btn--primary" data-br-require-auth>Post review</button>
        </form>
    @endif
</div>
