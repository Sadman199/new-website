@php
    $isRegulated = $broker->isRegulated();
    $rating = (float) ($broker->rating ?? 0);
    $categoryScores = is_array($broker->category_scores) ? $broker->category_scores : [];
    $scoreLabels = [
        'fees' => 'Fees & Costs',
        'safety' => 'Safety & Regulation',
        'platforms' => 'Trading Platforms',
        'deposit_withdrawal' => 'Deposit & Withdrawal',
        'customer_support' => 'Customer Support',
        'education' => 'Education',
        'research' => 'Research',
        'account_opening' => 'Account Opening',
        'products' => 'Products & Markets',
    ];
    $marketsLabel = $broker->marketList()
        ? implode(', ', array_map('ucfirst', $broker->marketList()))
        : null;
@endphp

<section class="br-hero" id="gettingstarted">
    <div class="br-container">
        <nav class="br-hero__breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span>/</span>
            <a href="{{ route('broker.reviews.index') }}">Broker Reviews</a>
            <span>/</span>
            <span>{{ $broker->name }}</span>
        </nav>

        <div class="br-hero__top">
            <div class="br-hero__identity">
                <div class="br-hero__logo">
                    @if($broker->logo)
                        <img src="{{ asset($broker->logo) }}" alt="{{ $broker->name }} logo" loading="eager">
                    @else
                        <span class="br-hero__logo-fallback">{{ strtoupper(substr($broker->name, 0, 2)) }}</span>
                    @endif
                </div>
                <div>
                    <h1 class="br-hero__title">{{ $broker->title ?: $broker->name . ' Review' }}</h1>
                    @if($broker->short_description)
                        <p class="br-hero__subtitle">{!! Str::limit(strip_tags($broker->short_description), 220) !!}</p>
                    @endif
                    <div class="br-hero__badges">
                        @if($broker->is_scam)
                            <span class="br-badge br-badge--danger">High Risk</span>
                        @elseif($isRegulated)
                            <span class="br-badge br-badge--safe">Regulated</span>
                        @else
                            <span class="br-badge br-badge--warn">Unregulated</span>
                        @endif
                        @if($broker->featured_broker)
                            <span class="br-badge br-badge--featured">Featured</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="br-hero__score-wrap">
                <div class="br-hero__score-ring">
                    <span class="br-hero__score-value">{{ number_format($rating, 1) }}</span>
                    <span class="br-hero__score-label">Score</span>
                </div>
                @if($broker->trust_score && (int) $broker->trust_score <= 99)
                    <p class="br-hero__trust">Trust score {{ (int) $broker->trust_score }}/99</p>
                @endif
            </div>
        </div>

        <div class="br-hero__meta">
            @if($broker->year_founded)
            <div class="br-hero__meta-item">
                <span>Founded</span>
                <strong>{{ $broker->year_founded }}</strong>
            </div>
            @endif
            <div class="br-hero__meta-item">
                <span>Min. Deposit</span>
                <strong>${{ number_format((float) ($broker->minimum_deposit ?? 0), 0) }}</strong>
            </div>
            @if($broker->country)
            <div class="br-hero__meta-item">
                <span>Headquarters</span>
                <strong>{{ strip_tags($broker->country) }}</strong>
            </div>
            @endif
            @if($broker->leverage)
            <div class="br-hero__meta-item">
                <span>Max Leverage</span>
                <strong>{{ strip_tags($broker->leverage) }}</strong>
            </div>
            @endif
            @if($marketsLabel)
            <div class="br-hero__meta-item">
                <span>Markets</span>
                <strong>{{ Str::limit($marketsLabel, 48) }}</strong>
            </div>
            @endif
        </div>

        <div class="br-hero__actions">
            <a href="{{ $broker->open_live ?: $broker->visit_site ?: $broker->url }}" target="_blank" rel="noopener noreferrer" class="br-btn br-btn--primary">
                Open an account
            </a>
            @if($broker->demo_link || $broker->open_demo)
            <a href="{{ $broker->demo_link ?: $broker->open_demo }}" target="_blank" rel="noopener noreferrer" class="br-btn br-btn--secondary">
                Try demo
            </a>
            @endif
            <a href="#compare" class="br-btn br-btn--secondary">Compare brokers</a>
        </div>

        @if(!empty($categoryScores))
        <div class="br-score-grid">
            @foreach($scoreLabels as $key => $label)
                @if(isset($categoryScores[$key]) && $categoryScores[$key] !== '' && $categoryScores[$key] !== null)
                    @php $val = min(10, max(0, (float) $categoryScores[$key])); @endphp
                    <div class="br-score-card">
                        <div class="br-score-card__label">{{ $label }}</div>
                        <div class="br-score-card__value">{{ number_format($val, 1) }}</div>
                        <div class="br-score-card__bar">
                            <div class="br-score-card__bar-fill" style="width: {{ $val * 10 }}%"></div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
        @endif
    </div>
</section>
