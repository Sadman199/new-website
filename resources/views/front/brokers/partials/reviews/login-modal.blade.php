@php
    $returnUrl = url()->current() . '#voices';
@endphp

<div class="br-modal" id="brReviewLoginModal" hidden>
    <div class="br-modal__backdrop" data-br-modal-close></div>
    <div class="br-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="brReviewLoginModalLabel">
        <div class="br-modal__content">
            <div class="br-modal__header">
                <h5 class="br-modal__title" id="brReviewLoginModalLabel">Sign in to continue</h5>
                <button type="button" class="br-modal__close" data-br-modal-close aria-label="Close">&times;</button>
            </div>
            <div class="br-modal__body">
                <p>Log in or create an account to rate brokers, leave reviews, and reply to comments.</p>
                <div class="br-modal__actions">
                    <a href="{{ route('user.login', ['redirect' => $returnUrl]) }}" class="br-btn br-btn--primary">Log in</a>
                    <a href="{{ route('user.register', ['redirect' => $returnUrl]) }}" class="br-btn br-btn--secondary">Create account</a>
                </div>
            </div>
        </div>
    </div>
</div>
