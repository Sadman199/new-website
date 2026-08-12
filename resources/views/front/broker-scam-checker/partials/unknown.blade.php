<section class="bsc-unknown glass-card">
    <div class="bsc-unknown__icon"><i class="fas fa-question-circle"></i></div>
    <h2 class="bsc-section-title">We don't have enough information about "{{ $unknownQuery }}"</h2>
    <p class="bsc-muted">This broker is not in our verified database yet. You can request a manual review or submit details for our editorial team.</p>

    <div class="bsc-unknown__actions">
        <button type="button" class="bsc-btn bsc-btn-primary" data-bsc-open="#bscReviewModal">
            Submit Broker For Review
        </button>
        <button type="button" class="bsc-btn bsc-btn-outline" data-bsc-open="#bscReviewModal">
            Request Verification
        </button>
    </div>
</section>

<div class="bsc-modal" id="bscReviewModal" hidden>
    <div class="bsc-modal__backdrop" data-bsc-close></div>
    <div class="bsc-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="bscReviewModalLabel">
        <div class="bsc-modal__content">
            <form action="{{ route('broker.scam_checker.request_review') }}" method="post">
                @csrf
                <div class="bsc-modal__header">
                    <h5 class="bsc-modal__title" id="bscReviewModalLabel">Request broker verification</h5>
                    <button type="button" class="bsc-modal__close" data-bsc-close aria-label="Close">&times;</button>
                </div>
                <div class="bsc-modal__body">
                    <label class="bsc-field">
                        <span>Broker name</span>
                        <input type="text" name="broker_name" class="bsc-input" value="{{ $unknownQuery }}" required>
                    </label>
                    <label class="bsc-field">
                        <span>Your name</span>
                        <input type="text" name="reporter_name" class="bsc-input" required>
                    </label>
                    <label class="bsc-field">
                        <span>Email</span>
                        <input type="email" name="reporter_email" class="bsc-input" required>
                    </label>
                    <label class="bsc-field">
                        <span>Additional details (optional)</span>
                        <textarea name="message" class="bsc-input" rows="4" placeholder="Website URL, regulation claims, etc."></textarea>
                    </label>
                </div>
                <div class="bsc-modal__footer">
                    <button type="submit" class="bsc-btn bsc-btn-primary">Submit request</button>
                </div>
            </form>
        </div>
    </div>
</div>
