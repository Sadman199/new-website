<section class="bsc-unknown glass-card">
    <div class="bsc-unknown__icon"><i class="bi bi-question-circle"></i></div>
    <h2 class="bsc-section-title">We don't have enough information about "{{ $unknownQuery }}"</h2>
    <p class="bsc-muted">This broker is not in our verified database yet. You can request a manual review or submit details for our editorial team.</p>

    <div class="bsc-unknown__actions">
        <button type="button" class="btn bsc-btn-primary" data-bs-toggle="modal" data-bs-target="#bscReviewModal">
            Submit Broker For Review
        </button>
        <button type="button" class="btn bsc-btn-outline" data-bs-toggle="modal" data-bs-target="#bscReviewModal">
            Request Verification
        </button>
    </div>
</section>

<div class="modal fade bsc-modal" id="bscReviewModal" tabindex="-1" aria-labelledby="bscReviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('broker.scam_checker.request_review') }}" method="post">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="bscReviewModalLabel">Request broker verification</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Broker name</label>
                        <input type="text" name="broker_name" class="form-control bsc-input" value="{{ $unknownQuery }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Your name</label>
                        <input type="text" name="reporter_name" class="form-control bsc-input" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="reporter_email" class="form-control bsc-input" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Additional details (optional)</label>
                        <textarea name="message" class="form-control bsc-input" rows="4" placeholder="Website URL, regulation claims, etc."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn bsc-btn-primary">Submit request</button>
                </div>
            </form>
        </div>
    </div>
</div>
