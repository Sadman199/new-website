@php
    use App\Http\Controllers\Front\BrokerController;
    use App\Support\BrokerRating;
    use App\Support\RichText;
    use Illuminate\Support\Str;

    $rank = (int) ($rank ?? 1);
    $rating = BrokerRating::outOfFive($broker->rating ?? null);
    $ratingPercent = BrokerRating::percent($broker->rating ?? null);
    $regs = method_exists($broker, 'regulationList') ? $broker->regulationList() : [];
    $regulationSummary = $regs !== []
        ? 'Regulated by ' . implode(', ', array_slice($regs, 0, 3)) . (count($regs) > 3 ? ' +' . (count($regs) - 3) : '')
        : null;
    $summary = RichText::toPlainText($broker->top_feature ?? null)
        ?: RichText::toPlainText($broker->short_description ?? null)
        ?: 'Strong trading conditions and catalog coverage on BrokersCourt.';
    $summary = Str::limit($summary, 120, '…');
    $feeLevel = ucfirst((string) ($broker->fee_level ?: 'medium'));
    $reviewCount = (int) ($broker->approved_review_count ?? 0);
    $mobileRaw = trim(strip_tags((string) ($broker->mobile_trading ?: '')));
    if ($mobileRaw === '' && ! empty($broker->web_trader)) {
        $mobileRaw = trim(strip_tags((string) $broker->web_trader));
    }
    if ($mobileRaw === '') {
        $platforms = method_exists($broker, 'platformList') ? $broker->platformList() : [];
        $joined = strtolower(implode(' ', $platforms));
        $mobile = (str_contains($joined, 'mt4') || str_contains($joined, 'mt5') || str_contains($joined, 'mobile') || str_contains($joined, 'ios') || str_contains($joined, 'android'))
            ? 'Yes'
            : '—';
    } else {
        $lower = strtolower($mobileRaw);
        $mobile = (str_contains($lower, 'no ') || $lower === 'no' || $lower === 'n/a' || $lower === 'none')
            ? 'No'
            : (strlen($mobileRaw) > 18 ? 'Yes' : $mobileRaw);
    }
    $reviewUrl = route('broker_detail', ['slug' => BrokerController::reviewSlugFor($broker)]);
    $visitUrl = $broker->open_live ?: $broker->visit_site ?: $broker->url;
    $isRegulated = method_exists($broker, 'isRegulated') ? $broker->isRegulated() : ! empty($regs);
    $trustLabel = $isRegulated ? 'Top trusted broker' : 'Editorially reviewed';
    $rankLabel = $rank === 1 ? 'Top pick' : ($broker->featured_broker ? 'Award winner' : 'Recommended');
@endphp

<article class="bal-alt-card {{ $rank === 1 ? 'is-top-pick' : '' }}">
    <div class="bal-alt-card__topline">
        <span class="bal-alt-card__rank">
            <strong>#{{ $rank }}</strong>
            {{ $rankLabel }}
        </span>
        <span class="bal-alt-card__trust-label">{{ $trustLabel }}</span>
    </div>

    <div class="bal-alt-card__identity">
        <a href="{{ $reviewUrl }}" class="bal-alt-card__logo" aria-hidden="true" tabindex="-1">
            @if($broker->logo)
                <img src="{{ asset($broker->logo) }}" alt="" loading="lazy" decoding="async">
            @else
                <span>{{ strtoupper(substr($broker->name, 0, 1)) }}</span>
            @endif
        </a>

        <div class="bal-alt-card__name-wrap">
            <a href="{{ $reviewUrl }}" class="bal-alt-card__name">{{ $broker->name }}</a>
            @if($isRegulated)
                <span class="bal-alt-card__badge">Regulated</span>
            @else
                <span class="bal-alt-card__badge bal-alt-card__badge--muted">Reviewed</span>
            @endif
        </div>

        @if($rating !== null)
            <div class="bal-alt-card__rating"
                 style="--bal-rating: {{ $ratingPercent }}%"
                 aria-label="Rating {{ number_format($rating, 1) }} out of 5">
                <strong>{{ number_format($rating, 1) }}</strong>
                <span>/5</span>
            </div>
        @else
            <div class="bal-alt-card__rating bal-alt-card__rating--empty" aria-hidden="true"></div>
        @endif
    </div>

    <p class="bal-alt-card__summary">{{ $summary }}</p>

    <dl class="bal-alt-card__facts">
        <div>
            <dt>Fee level</dt>
            <dd>{{ $feeLevel }}</dd>
        </div>
        <div>
            <dt>Client reviews</dt>
            <dd>{{ number_format($reviewCount) }}</dd>
        </div>
        <div>
            <dt>Mobile access</dt>
            <dd>{{ $mobile }}</dd>
        </div>
    </dl>

    @if($regulationSummary)
        <p class="bal-alt-card__regulation">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M12 3l7.5 3v5.25c0 4.75-3.2 8.25-7.5 9.75-4.3-1.5-7.5-5-7.5-9.75V6L12 3Z"/>
            </svg>
            {{ $regulationSummary }}
        </p>
    @endif

    <div class="bal-alt-card__actions">
        <a href="{{ $reviewUrl }}" class="bal-alt-card__btn bal-alt-card__btn--review">Read full review</a>
        @if($visitUrl)
            <a href="{{ $visitUrl }}" class="bal-alt-card__btn bal-alt-card__btn--visit" target="_blank" rel="noopener noreferrer nofollow">
                Visit broker
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                </svg>
            </a>
        @endif
    </div>

    <p class="bal-alt-card__risk">Your capital is at risk.</p>
</article>
