@php
    $reviewFilters = $reviewFilters ?? ['score' => 'all', 'length' => 'all', 'account_type' => 'all'];
    $reviewFilterOptions = $reviewFilterOptions ?? ['scores' => [], 'lengths' => [], 'account_types' => []];
@endphp

<div class="br-community-comments">
    <div class="br-community-comments__head">
        <div>
            <h3 class="br-community-comments__title">Community Comments</h3>
            <p class="br-community-comments__desc">Published reviews from traders. Replies stay one level deep.</p>
        </div>

        <form method="GET" action="{{ url()->current() }}" class="br-review-filters" id="brReviewFilters">
            <label class="sr-only" for="br_filter_score">Filter by score</label>
            <select name="score" id="br_filter_score" data-br-filter>
                @foreach(($reviewFilterOptions['scores'] ?? []) as $value => $label)
                    <option value="{{ $value }}" @selected(($reviewFilters['score'] ?? 'all') === $value)>{{ $label }}</option>
                @endforeach
            </select>

            <label class="sr-only" for="br_filter_length">Filter by length of use</label>
            <select name="length" id="br_filter_length" data-br-filter>
                @foreach(($reviewFilterOptions['lengths'] ?? []) as $value => $label)
                    <option value="{{ $value }}" @selected(($reviewFilters['length'] ?? 'all') === $value)>{{ $label }}</option>
                @endforeach
            </select>

            <label class="sr-only" for="br_filter_account">Filter by account type</label>
            <select name="account_type" id="br_filter_account" data-br-filter>
                <option value="all" @selected(($reviewFilters['account_type'] ?? 'all') === 'all')>All Account Types</option>
                @foreach(($reviewFilterOptions['account_types'] ?? []) as $type)
                    <option value="{{ $type }}" @selected(($reviewFilters['account_type'] ?? '') === $type)>{{ $type }}</option>
                @endforeach
            </select>

            <noscript>
                <button type="submit" class="br-btn br-btn--secondary br-btn--sm">Apply</button>
            </noscript>
        </form>
    </div>

    @if(session('success'))
        <div class="br-comment-alert br-comment-alert--success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="br-comment-alert br-comment-alert--error">{{ session('error') }}</div>
    @endif

    @if($approved_reviews->isEmpty())
        <div class="br-comments-empty">
            <p>No comments match these filters yet. Be the first to share your experience with {{ $broker->name }}.</p>
        </div>
    @else
        <div class="br-comments-list">
            @foreach($approved_reviews as $review)
                <article class="br-comment" id="review-{{ $review->id }}">
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
                                <p class="br-comment__meta">
                                    {{ $review->country }}
                                    · {{ $review->formatted_date }}
                                    @if($review->account_type)
                                        · {{ $review->account_type }}
                                    @endif
                                    @if($review->lengthOfUseLabel())
                                        · {{ $review->lengthOfUseLabel() }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="br-comment__rating" aria-label="Rating {{ $review->score10() }} out of 10">
                            {{ number_format($review->score10(), 0) }}/10
                        </div>
                    </div>

                    @if($review->rating_cost || $review->rating_platforms || $review->rating_customer_support)
                        <ul class="br-comment__dims">
                            @if($review->rating_cost)
                                <li>Cost <strong>{{ $review->rating_cost }}/5</strong></li>
                            @endif
                            @if($review->rating_platforms)
                                <li>Platforms <strong>{{ $review->rating_platforms }}/5</strong></li>
                            @endif
                            @if($review->rating_customer_support)
                                <li>Support <strong>{{ $review->rating_customer_support }}/5</strong></li>
                            @endif
                        </ul>
                    @endif

                    <div class="br-comment__body">
                        <p>{{ $review->description }}</p>
                    </div>

                    <div class="br-comment__actions">
                        <button
                            type="button"
                            class="br-comment__reply-btn"
                            data-br-reply-toggle="{{ $review->id }}"
                            data-br-require-auth
                        >
                            Reply
                        </button>
                    </div>

                    <div class="br-comment__reply-form" id="br-reply-form-{{ $review->id }}" hidden>
                        @auth('web')
                            <form action="{{ route('reviews.replies.store', [$broker, $review]) }}" method="POST" class="br-reply-form">
                                @csrf
                                <label class="sr-only" for="reply_body_{{ $review->id }}">Your reply</label>
                                <textarea
                                    id="reply_body_{{ $review->id }}"
                                    name="description"
                                    rows="3"
                                    required
                                    minlength="2"
                                    maxlength="2000"
                                    placeholder="Write a reply…"
                                ></textarea>
                                <div class="br-comment-form__actions">
                                    <button type="submit" class="br-btn br-btn--primary br-btn--sm">Post reply</button>
                                    <button type="button" class="br-btn br-btn--secondary br-btn--sm" data-br-reply-cancel="{{ $review->id }}">Cancel</button>
                                </div>
                                <p class="br-reply-form__note">Replies are moderated before they appear.</p>
                            </form>
                        @else
                            <div class="br-rate-review__guest">
                                <p>Log in to reply to this comment.</p>
                                <button type="button" class="br-btn br-btn--primary br-btn--sm" data-br-open-login>Log in to reply</button>
                            </div>
                        @endauth
                    </div>

                    @if($review->approvedReplies->isNotEmpty())
                        <div class="br-comment__replies">
                            @foreach($review->approvedReplies as $reply)
                                <article class="br-comment br-comment--reply" id="review-{{ $reply->id }}">
                                    <div class="br-comment__header">
                                        <div class="br-comment__author">
                                            <div class="br-comment__avatar br-comment__avatar--sm">{{ strtoupper(substr($reply->name, 0, 1)) }}</div>
                                            <div>
                                                <h5 class="br-comment__name">
                                                    {{ $reply->name }}
                                                    @if($reply->user && $reply->user->is_verified)
                                                        <span class="br-comment__verified">Verified</span>
                                                    @endif
                                                </h5>
                                                <p class="br-comment__meta">{{ $reply->formatted_date }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="br-comment__body">
                                        <p>{{ $reply->description }}</p>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @endif
                </article>
            @endforeach
        </div>
    @endif
</div>
