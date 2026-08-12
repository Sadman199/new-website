<section class="br-reviews" id="voices">
    <div class="br-section br-section--reviews">
        <div class="br-section__head">
            <h2 class="br-section__title">Community Comments</h2>
            <p class="br-section__desc">Real trader feedback about {{ $broker->name }}</p>
        </div>
        <div class="br-section__body">
            <div class="br-comments-summary">
                <div class="br-comments-summary__score">
                    <span class="br-comments-summary__value">{{ $reviewStats['average'] ?: '—' }}</span>
                    <span class="br-comments-summary__label">Average rating (out of 5)</span>
                </div>
                <div class="br-comments-summary__meta">
                    <strong>{{ $reviewStats['count'] }}</strong> published comments
                    <span class="br-comments-summary__sep">·</span>
                    Sorted by latest
                </div>
            </div>

            @php
                $userReview = $userReview ?? null;
                if (session('review_timeout_' . $broker->id) && now()->greaterThan(session('review_timeout_' . $broker->id))) {
                    session()->forget(['review_submitted_' . $broker->id, 'review_timeout_' . $broker->id]);
                }
            @endphp

            <div class="br-comment-compose">
                @if($userReview && $userReview->isApproved())
                    <div class="br-comment-alert br-comment-alert--success">
                        Your review is published in the list below. Thanks for sharing your experience.
                    </div>
                @elseif($userReview && $userReview->isDeclined())
                    <div class="br-comment-alert br-comment-alert--error">
                        Your review was not approved. Contact support if you think this was a mistake.
                    </div>
                @elseif($userReview && $userReview->isPending())
                    <h3 class="br-comment-compose__heading">Edit your pending comment</h3>
                    <p class="br-comment-compose__text">This review is awaiting moderation. You can update or remove it from your profile until it is approved.</p>
                    <form action="{{ route('user.reviews.update', $userReview) }}" method="POST" class="br-comment-form">
                        @csrf
                        @method('PUT')

                        <div class="br-comment-form__row">
                            <div class="br-comment-form__field">
                                <label for="review_country">Country</label>
                                <input type="text" name="country" id="review_country" value="{{ old('country', $userReview->country) }}" placeholder="Your country">
                                @error('country')
                                    <span class="br-comment-form__error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="br-comment-form__field">
                                <label>Your rating</label>
                                <div class="br-rating-input">
                                    <div class="br-rating-input__stars" id="starRating">
                                        @for($i = 1; $i <= 5; $i++)
                                            <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" {{ (int) old('rating', $userReview->rating) === $i ? 'checked' : '' }} class="br-rating-input__radio">
                                            <label for="star{{ $i }}">{{ $i }}</label>
                                        @endfor
                                    </div>
                                    <span class="br-rating-input__text" id="ratingText">{{ old('rating', $userReview->rating) }}/5</span>
                                </div>
                                @error('rating')
                                    <span class="br-comment-form__error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="br-comment-form__field">
                            <label for="review_description">Your comment</label>
                            <textarea name="description" id="review_description" rows="4" required placeholder="Describe spreads, execution, withdrawals, support…">{{ old('description', $userReview->description) }}</textarea>
                            @error('description')
                                <span class="br-comment-form__error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="br-comment-form__actions">
                            <button type="submit" class="br-btn br-btn--primary">Save changes</button>
                            <a href="{{ route('user.profile', ['tab' => 'overview']) }}#ua-reviews" class="br-btn br-btn--secondary br-btn--sm">Manage in profile</a>
                        </div>
                    </form>
                @elseif(session('review_submitted_' . $broker->id))
                    <div class="br-comment-alert br-comment-alert--success">
                        Your comment has been submitted and is pending moderation. Thank you!
                    </div>
                @elseif(!auth('web')->check())
                    <div class="br-comment-compose__guest">
                        <div>
                            <h3 class="br-comment-compose__heading">Share your experience</h3>
                            <p class="br-comment-compose__text">Log in to comment on {{ $broker->name }}.</p>
                        </div>
                        <div class="br-comment-compose__actions">
                            <a href="{{ route('user.login') }}" class="br-btn br-btn--primary br-btn--sm">Log in</a>
                            <a href="{{ route('user.register') }}" class="br-btn br-btn--secondary br-btn--sm">Register</a>
                        </div>
                    </div>
                @else
                    <h3 class="br-comment-compose__heading">Write a comment</h3>
                    <form action="{{ route('reviews.store', $broker->id) }}" method="POST" class="br-comment-form">
                        @csrf
                        <input type="hidden" name="broker_id" value="{{ $broker->id }}">

                        <div class="br-comment-form__row">
                            <div class="br-comment-form__field">
                                <label for="review_country">Country</label>
                                <input type="text" name="country" id="review_country" value="{{ old('country', auth('web')->user()->country) }}" placeholder="Your country">
                                @error('country')
                                    <span class="br-comment-form__error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="br-comment-form__field">
                                <label>Your rating</label>
                                <div class="br-rating-input">
                                    <div class="br-rating-input__stars" id="starRating">
                                        @for($i = 1; $i <= 5; $i++)
                                            <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }} class="br-rating-input__radio">
                                            <label for="star{{ $i }}">{{ $i }}</label>
                                        @endfor
                                    </div>
                                    <span class="br-rating-input__text" id="ratingText">{{ old('rating') ? old('rating') . '/5' : 'Select rating' }}</span>
                                </div>
                                @error('rating')
                                    <span class="br-comment-form__error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="br-comment-form__field">
                            <label for="review_description">Your comment</label>
                            <textarea name="description" id="review_description" rows="4" required placeholder="Describe spreads, execution, withdrawals, support…">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="br-comment-form__error">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="br-btn br-btn--primary">Post comment</button>
                    </form>
                @endif
            </div>

            @if($approved_reviews->isEmpty())
                <div class="br-comments-empty">
                    <p>No comments yet. Be the first to share your experience with {{ $broker->name }}.</p>
                </div>
            @else
                <div class="br-comments-list">
                    @foreach($approved_reviews as $review)
                        <article class="br-comment">
                            <div class="br-comment__header">
                                <div class="br-comment__author">
                                    <div class="br-comment__avatar">{{ strtoupper(substr($review->name, 0, 1)) }}</div>
                                    <div>
                                        <h4 class="br-comment__name">
                                            {{ $review->name }}
                                            @if($review->user && $review->user->is_verified)
                                                <span class="br-comment__verified">Verified</span>
                                            @endif
                                        </h4>
                                        <p class="br-comment__meta">{{ $review->country }} · {{ $review->formatted_date }} · {{ $review->time_ago }}</p>
                                    </div>
                                </div>
                                <div class="br-comment__rating" aria-label="Rating {{ $review->rating }} out of 5">
                                    {{ $review->rating }}/5
                                </div>
                            </div>
                            <div class="br-comment__body">
                                <p>{{ $review->description }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</section>
