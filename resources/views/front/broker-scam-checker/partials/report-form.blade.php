{{-- Guest: login required --}}
<div class="bsc-modal" id="bscReportGuestModal" hidden>
    <div class="bsc-modal__backdrop" data-bsc-close></div>
    <div class="bsc-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="bscReportGuestModalLabel">
        <div class="bsc-modal__content">
            <div class="bsc-modal__header">
                <h5 class="bsc-modal__title" id="bscReportGuestModalLabel">Report {{ $analysis['broker']['name'] }}</h5>
                <button type="button" class="bsc-modal__close" data-bsc-close aria-label="Close">&times;</button>
            </div>
            <div class="bsc-modal__body bsc-modal__body--center">
                <div class="bsc-guest-icon"><i class="fas fa-user-lock"></i></div>
                <h6>Sign in to submit a report</h6>
                <p class="bsc-muted">Community reports help our editorial team investigate withdrawal issues, fake regulation claims, and suspicious activity. Only registered users can submit reports.</p>
                <div class="bsc-unknown__actions">
                    <a href="{{ route('user.login') }}" class="bsc-btn bsc-btn-primary">Log in</a>
                    <a href="{{ route('user.register') }}" class="bsc-btn bsc-btn-outline">Create account</a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Authenticated: report form --}}
@auth('web')
<div class="bsc-modal" id="bscReportModal" hidden>
    <div class="bsc-modal__backdrop" data-bsc-close></div>
    <div class="bsc-modal__dialog bsc-modal__dialog--lg" role="dialog" aria-modal="true" aria-labelledby="bscReportModalLabel">
        <div class="bsc-modal__content">
            <form id="bscReportForm" action="{{ route('broker.scam_checker.report') }}" method="post" novalidate>
                @csrf
                <input type="hidden" name="broker_id" value="{{ $analysis['broker']['id'] }}">
                <div class="bsc-modal__header">
                    <h5 class="bsc-modal__title" id="bscReportModalLabel">Report {{ $analysis['broker']['name'] }}</h5>
                    <button type="button" class="bsc-modal__close" data-bsc-close aria-label="Close">&times;</button>
                </div>
                <div class="bsc-modal__body">
                    <div id="bscReportAlert" class="bsc-form-alert bsc-hidden" role="alert"></div>

                    <p class="bsc-muted">Community reports help our editorial team investigate withdrawal issues, fake regulation claims, and suspicious activity.</p>

                    <div class="bsc-reporter-badge glass-card">
                        <div class="bsc-reporter-badge__avatar">
                            <img src="{{ auth('web')->user()->avatar_url }}" alt="" width="40" height="40" loading="lazy" decoding="async">
                        </div>
                        <div>
                            <p class="bsc-reporter-badge__name">{{ auth('web')->user()->name }}</p>
                            <p class="bsc-reporter-badge__email">{{ auth('web')->user()->email }}</p>
                        </div>
                    </div>

                    <label class="bsc-field">
                        <span>Issue type <span class="bsc-required">*</span></span>
                        <select name="issue_type" id="bscIssueType" class="bsc-input" required>
                            <option value="" disabled {{ old('issue_type') ? '' : 'selected' }}>Select an issue type…</option>
                            @foreach($issueTypes as $value => $label)
                                <option value="{{ $value }}" {{ old('issue_type') === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <div class="bsc-field-error bsc-hidden" data-error-for="issue_type"></div>
                    </label>
                    <label class="bsc-field">
                        <span>Message <span class="bsc-required">*</span></span>
                        <textarea name="message"
                                  id="bscReportMessage"
                                  class="bsc-input"
                                  rows="5"
                                  required
                                  minlength="20"
                                  maxlength="5000"
                                  placeholder="Describe the issue with as much detail as possible (minimum 20 characters)…">{{ old('message') }}</textarea>
                        <div class="bsc-field-foot">
                            <div class="bsc-field-error bsc-hidden" data-error-for="message"></div>
                            <small class="bsc-muted"><span id="bscMessageCount">0</span> / 5000</small>
                        </div>
                    </label>
                </div>
                <div class="bsc-modal__footer">
                    <button type="button" class="bsc-btn bsc-btn-outline" data-bsc-close>Cancel</button>
                    <button type="submit" class="bsc-btn bsc-btn-primary" id="bscReportSubmit">
                        <span class="bsc-submit-label">Submit report</span>
                        <span class="bsc-submit-spinner bsc-hidden">Submitting…</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endauth
