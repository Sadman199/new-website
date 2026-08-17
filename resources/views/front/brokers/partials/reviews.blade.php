<section class="br-reviews" id="voices" data-br-authenticated="{{ auth('web')->check() ? '1' : '0' }}">
    <div class="br-section br-section--reviews">
        <div class="br-section__head">
            <h2 class="br-section__title">Community Reviews</h2>
            <p class="br-section__desc">Trader ratings and comments about {{ $broker->name }}</p>
        </div>
        <div class="br-section__body">
            @include('front.brokers.partials.reviews.overall-rating')
            @include('front.brokers.partials.reviews.rate-and-review')
            @include('front.brokers.partials.reviews.community-comments')
        </div>
    </div>

    @include('front.brokers.partials.reviews.login-modal')
</section>
