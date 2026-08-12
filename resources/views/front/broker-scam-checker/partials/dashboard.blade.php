<section class="bsc-dashboard" id="bscDashboard">
    @if($analysis['show_warning'])
        <div class="bsc-warning glass-card">
            <div class="bsc-warning__icon"><i class="fas fa-exclamation-triangle"></i></div>
            <div>
                <h2 class="bsc-warning__title">{{ $analysis['warning_title'] }}</h2>
                <p class="bsc-warning__text">{{ $analysis['warning_message'] }}</p>
                @if($analysis['scam_detail_url'])
                    <a href="{{ $analysis['scam_detail_url'] }}" class="bsc-link">View scam listing details</a>
                @endif
            </div>
        </div>
    @endif

    <div class="bsc-dashboard__head glass-card">
        <div class="bsc-broker-ident">
            <img src="{{ $analysis['broker']['logo_url'] }}" alt="" class="bsc-broker-ident__logo" width="72" height="72" decoding="async">
            <div>
                <p class="bsc-eyebrow">Safety analysis</p>
                <h2 class="bsc-broker-ident__name">{{ $analysis['broker']['name'] }}</h2>
                @if($analysis['broker']['rating'])
                    <p class="bsc-muted mb-0">Editorial rating {{ $analysis['broker']['rating'] }}/10</p>
                @endif
            </div>
        </div>

        <div class="bsc-dashboard__score">
            <div class="bsc-score-ring" data-score="{{ $analysis['overall_score'] }}" style="--bsc-ring-color: {{ $analysis['risk_color'] }}">
                <svg viewBox="0 0 120 120" aria-hidden="true">
                    <circle cx="60" cy="60" r="52" class="bsc-ring-bg"/>
                    <circle cx="60" cy="60" r="52" class="bsc-ring-fill"/>
                </svg>
                <div class="bsc-score-ring__value"><span id="bscScoreValue">0</span><small>/100</small></div>
            </div>
            <div class="bsc-risk-badge bsc-risk-badge--{{ $analysis['risk_level'] }}">
                <span>{{ $analysis['risk_icon'] }}</span>
                {{ $analysis['risk_label'] }}
            </div>
        </div>
    </div>

    <div class="bsc-actions">
        <button type="button" class="bsc-btn bsc-btn-outline" id="bscCompareToggle">
            <i class="fas fa-columns"></i> Compare Safety
        </button>
        <a href="{{ $analysis['review_url'] }}" class="bsc-btn bsc-btn-outline">
            <i class="fas fa-file-alt"></i> Full Review
        </a>
        <button type="button"
                class="bsc-btn bsc-btn-primary"
                data-bsc-open="{{ auth('web')->check() ? '#bscReportModal' : '#bscReportGuestModal' }}">
            <i class="fas fa-flag"></i> Report This Broker
        </button>
    </div>

    <div class="bsc-grid bsc-grid--4 bsc-cards">
        <article class="glass-card bsc-card">
            <div class="bsc-card__icon bsc-card__icon--blue"><i class="fas fa-university"></i></div>
            <h3 class="bsc-card__title">Regulation Check</h3>
            <p class="bsc-card__meta">Tier: {{ $analysis['regulation']['tier'] }}</p>
            <ul class="bsc-checklist">
                @foreach($analysis['regulation']['items'] as $item)
                    <li class="{{ $item['positive'] ? 'is-positive' : 'is-negative' }}">
                        <i class="fas {{ $item['positive'] ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                        {{ $item['label'] }}
                    </li>
                @endforeach
            </ul>
        </article>

        <article class="glass-card bsc-card">
            <div class="bsc-card__icon bsc-card__icon--purple"><i class="fas fa-building"></i></div>
            <h3 class="bsc-card__title">Company Background</h3>
            <dl class="bsc-dl">
                <div><dt>Founded</dt><dd>{{ $analysis['company']['founded'] }}</dd></div>
                <div><dt>Country</dt><dd>{{ $analysis['company']['country'] }}</dd></div>
                <div><dt>Operating</dt><dd>{{ $analysis['company']['years_active'] }}</dd></div>
            </dl>
        </article>

        <article class="glass-card bsc-card">
            <div class="bsc-card__icon bsc-card__icon--green"><i class="fas fa-shield-alt"></i></div>
            <h3 class="bsc-card__title">Trust Score</h3>
            <p class="bsc-trust-label">{{ $analysis['trust']['label'] }}</p>
            @if($analysis['trust']['score'])
                <p class="bsc-trust-score">{{ $analysis['trust']['score'] }}<span>/99</span></p>
            @endif
            @if($analysis['trust']['rating'])
                <p class="bsc-muted">Rating {{ $analysis['trust']['rating'] }}</p>
            @endif
        </article>

        <article class="glass-card bsc-card">
            <div class="bsc-card__icon bsc-card__icon--amber"><i class="fas fa-lock"></i></div>
            <h3 class="bsc-card__title">Trading Safety</h3>
            <ul class="bsc-checklist">
                @foreach($analysis['protection']['items'] as $item)
                    <li class="{{ $item['active'] ? 'is-positive' : 'is-negative' }}">
                        <i class="fas {{ $item['active'] ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                        {{ $item['label'] }}
                    </li>
                @endforeach
            </ul>
        </article>
    </div>

    <div class="bsc-grid bsc-grid--2">
        <article class="glass-card bsc-factor-card">
            <h3 class="bsc-section-title"><i class="fas fa-exclamation-circle"></i> Risk Factors</h3>
            @if(count($analysis['risk_factors']))
                <ul class="bsc-factor-list">
                    @foreach($analysis['risk_factors'] as $factor)
                        <li>{{ $factor['icon'] }} {{ $factor['text'] }}</li>
                    @endforeach
                </ul>
            @else
                <p class="bsc-muted">No major risk flags detected in our database.</p>
            @endif
        </article>
        <article class="glass-card bsc-factor-card">
            <h3 class="bsc-section-title"><i class="fas fa-check-circle"></i> Safety Factors</h3>
            @if(count($analysis['safety_factors']))
                <ul class="bsc-factor-list bsc-factor-list--safe">
                    @foreach($analysis['safety_factors'] as $factor)
                        <li>{{ $factor['icon'] }} {{ $factor['text'] }}</li>
                    @endforeach
                </ul>
            @else
                <p class="bsc-muted">Limited positive safety signals on file.</p>
            @endif
        </article>
    </div>

    <div class="glass-card bsc-breakdown">
        <h3 class="bsc-section-title">Score breakdown</h3>
        <div class="bsc-grid bsc-grid--3">
            @foreach($analysis['components'] as $key => $component)
                <div class="bsc-meter">
                    <div class="bsc-meter__head">
                        <span>{{ $component['label'] }}</span>
                        <strong>{{ $component['score'] }}%</strong>
                    </div>
                    <div class="bsc-meter__track">
                        <div class="bsc-meter__fill" data-meter="{{ $component['score'] }}"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
