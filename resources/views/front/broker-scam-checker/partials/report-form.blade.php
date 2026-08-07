{{-- Guest: login required --}}
<div class="modal fade bsc-modal" id="bscReportGuestModal" tabindex="-1" aria-labelledby="bscReportGuestModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bscReportGuestModalLabel">Report {{ $analysis['broker']['name'] }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="bsc-guest-icon mb-3"><i class="bi bi-person-lock"></i></div>
                <h6 class="mb-2">Sign in to submit a report</h6>
                <p class="bsc-muted mb-4">Community reports help our editorial team investigate withdrawal issues, fake regulation claims, and suspicious activity. Only registered users can submit reports.</p>
                <div class="d-flex flex-wrap gap-2 justify-content-center">
                    <a href="{{ route('user.login') }}" class="btn bsc-btn-primary">Log in</a>
                    <a href="{{ route('user.register') }}" class="btn bsc-btn-outline">Create account</a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Authenticated: report form --}}
@auth('web')
<div class="modal fade bsc-modal" id="bscReportModal" tabindex="-1" aria-labelledby="bscReportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form id="bscReportForm" action="{{ route('broker.scam_checker.report') }}" method="post" novalidate>
                @csrf
                <input type="hidden" name="broker_id" value="{{ $analysis['broker']['id'] }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="bscReportModalLabel">Report {{ $analysis['broker']['name'] }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="bscReportAlert" class="bsc-form-alert d-none" role="alert"></div>

                    <p class="bsc-muted">Community reports help our editorial team investigate withdrawal issues, fake regulation claims, and suspicious activity.</p>

                    <div class="bsc-reporter-badge glass-card mb-3">
                        <div class="bsc-reporter-badge__avatar">
                            <img src="{{ auth('web')->user()->avatar_url }}" alt="" width="40" height="40">
                        </div>
                        <div>
                            <p class="bsc-reporter-badge__name mb-0">{{ auth('web')->user()->name }}</p>
                            <p class="bsc-reporter-badge__email mb-0">{{ auth('web')->user()->email }}</p>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label" for="bscIssueType">Issue type <span class="text-danger">*</span></label>
                            <select name="issue_type" id="bscIssueType" class="form-select bsc-input" required>
                                <option value="" disabled {{ old('issue_type') ? '' : 'selected' }}>Select an issue type…</option>
                                @foreach($issueTypes as $value => $label)
                                    <option value="{{ $value }}" {{ old('issue_type') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            <div class="bsc-field-error d-none" data-error-for="issue_type"></div>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="bscReportMessage">Message <span class="text-danger">*</span></label>
                            <textarea name="message"
                                      id="bscReportMessage"
                                      class="form-control bsc-input"
                                      rows="5"
                                      required
                                      minlength="20"
                                      maxlength="5000"
                                      placeholder="Describe the issue with as much detail as possible (minimum 20 characters)…">{{ old('message') }}</textarea>
                            <div class="d-flex justify-content-between mt-1">
                                <div class="bsc-field-error d-none" data-error-for="message"></div>
                                <small class="bsc-muted ms-auto"><span id="bscMessageCount">0</span> / 5000</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bsc-btn-outline" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn bsc-btn-primary" id="bscReportSubmit">
                        <span class="bsc-submit-label">Submit report</span>
                        <span class="bsc-submit-spinner d-none"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Submitting…</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endauth
