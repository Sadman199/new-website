@extends('front.layout.app')

@php $presenter = \App\Support\PropFirmPresenter::make($firm); @endphp

@section('title', ($firm->meta_title ?: $firm->name . ' Review — Programs & Funding | BrokersCourt'))
@section('meta_description', $firm->meta_description ?: Str::limit(strip_tags($firm->description), 155))
@section('canonical', route('prop_firms.show', ['slug' => $firm->slug]))
@section('og_image', $firm->og_image ?: ($firm->logo ?: ''))

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/prop-firms-design-system.css') }}?v=5">
    <link rel="stylesheet" href="{{ asset('css/prop-firm-detail.css') }}?v=5">
@endpush

@section('main_content')
<div class="pf-root pf-detail">
    <header class="pf-detail__hero">
        <div class="pf-wrap">
            <nav class="pf-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                <a href="{{ route('prop_firms.index') }}">Prop firms</a>
                @if($firm->category)
                    <span>/</span>
                    <a href="{{ route('prop_firms.category', $firm->category->slug) }}">{{ $firm->category->name }}</a>
                @endif
                <span>/</span>
                <span>{{ $firm->name }}</span>
            </nav>

            <div class="pf-detail__hero-grid">
                <div>
                    <div class="pf-detail__brand">
                        <div class="pf-detail__logo">
                            @if($firm->logo)<img src="{{ asset($firm->logo) }}" alt="{{ $firm->name }}">@endif
                        </div>
                        <div>
                            <p class="pf-eyebrow" style="margin-bottom:0.5rem;">Prop firm review</p>
                            <h1 class="pf-detail__title pf-display">{{ $firm->name }}</h1>
                            <p class="pf-detail__subtitle">
                                {{ $firm->headquarters ?? 'Global' }}
                                @if($firm->founded_year) · Est. {{ $firm->founded_year }}@endif
                            </p>
                        </div>
                    </div>

                    <div class="pf-detail__badges">
                        @if($firm->is_featured)<span class="pf-badge pf-badge--gold">★ Featured</span>@endif
                        @if($firm->is_verified)<span class="pf-badge pf-badge--cyan">✓ Verified</span>@endif
                        @if($firm->scaling_available)<span class="pf-badge pf-badge--emerald">Scaling plan</span>@endif
                        @if($firm->trust_score)<span class="pf-score">Trust {{ number_format($firm->trust_score, 1) }}/10 · {{ $presenter->trustLabel() }}</span>@endif
                    </div>

                    @if($firm->description)
                        <p class="pf-detail__desc">{{ $firm->description }}</p>
                    @endif

                    <div class="pf-detail__stats">
                        @foreach($presenter->heroStats() as $stat)
                            <div class="pf-detail__stat">
                                <small>{{ $stat['label'] }}</small>
                                <strong>{{ $stat['value'] }}</strong>
                            </div>
                        @endforeach
                        <div class="pf-detail__stat">
                            <small>Challenge fees</small>
                            <strong>{{ $presenter->fundingRange() }}</strong>
                        </div>
                    </div>
                </div>

                <aside class="pf-card pf-detail__cta-card">
                    <h3>Ratings snapshot</h3>
                    <div class="pf-detail__rating-row"><span>Editor rating</span><strong>{{ $firm->editor_rating ? number_format($firm->editor_rating, 1) : '—' }}</strong></div>
                    <div class="pf-detail__rating-row"><span>User rating</span><strong>{{ $firm->user_rating ? number_format($firm->user_rating, 1) : '—' }}</strong></div>
                    <div class="pf-detail__rating-row"><span>Overall</span><strong>{{ $firm->overall_rating ? number_format($firm->overall_rating, 1) : '—' }}</strong></div>
                    <div class="pf-detail__cta-actions">
                        @if($firm->affiliate_link)
                            <a href="{{ $firm->affiliate_link }}" class="pf-btn pf-btn--gold" target="_blank" rel="noopener sponsored">Get funded →</a>
                        @elseif($firm->website)
                            <a href="{{ $firm->website }}" class="pf-btn pf-btn--gold" target="_blank" rel="noopener">Visit website →</a>
                        @endif
                        <a href="{{ route('prop_firms.index') }}" class="pf-btn pf-btn--ghost">Compare others</a>
                    </div>
                </aside>
            </div>
        </div>
    </header>

    <div class="pf-wrap pf-detail__body">
        <div class="pf-detail__sections">
            @if($settings->get('enable_programs', true) && $firm->programs->isNotEmpty())
            <section class="pf-card pf-detail__section" id="programs">
                <div class="pf-detail__section-head">
                    <div>
                        <h2 class="pf-section-title pf-display">Funding programs</h2>
                        <p class="pf-section-sub">Account sizes, drawdown rules, and trading conditions.</p>
                    </div>
                </div>
                <div class="pf-table-wrap">
                    <table class="pf-table">
                        <thead>
                            <tr>
                                <th>Program</th>
                                <th>Size</th>
                                <th>Fee</th>
                                <th>Target</th>
                                <th>Drawdown</th>
                                <th>Split</th>
                                <th>News</th>
                                <th>Weekend</th>
                                <th>EA</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($firm->programs as $program)
                            <tr>
                                <td><span class="pf-program-name">{{ $program->name }}</span></td>
                                <td>{{ $program->account_size ?? '—' }}</td>
                                <td>{{ $program->entry_fee !== null ? '$'.number_format($program->entry_fee, 0) : '—' }}</td>
                                <td>{{ $program->profit_target ?? '—' }}</td>
                                <td>{{ $program->daily_drawdown ?? '—' }} / {{ $program->max_drawdown ?? '—' }}</td>
                                <td>{{ $program->profit_split ?? '—' }}</td>
                                <td><span class="{{ $program->news_trading ? 'pf-yes' : 'pf-no' }}">{{ $program->news_trading ? 'Yes' : 'No' }}</span></td>
                                <td><span class="{{ $program->weekend_holding ? 'pf-yes' : 'pf-no' }}">{{ $program->weekend_holding ? 'Yes' : 'No' }}</span></td>
                                <td><span class="{{ $program->ea_allowed ? 'pf-yes' : 'pf-no' }}">{{ $program->ea_allowed ? 'Yes' : 'No' }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
            @endif

            @if($firm->attributes->isNotEmpty())
            <section class="pf-card pf-detail__section">
                <h2 class="pf-section-title pf-display">Platforms & features</h2>
                <p class="pf-section-sub" style="margin-bottom:1rem;">Capabilities and tools available at this firm.</p>
                <div class="pf-attr-grid">
                    @foreach($firm->attributes as $attr)
                        <span class="pf-attr-pill">
                            @if($attr->group)<small>{{ $attr->group }}</small>@endif
                            {{ $attr->name }}
                        </span>
                    @endforeach
                </div>
            </section>
            @endif

            @if($settings->get('enable_reviews', true) && $firm->reviews->isNotEmpty())
            <section class="pf-card pf-detail__section">
                <h2 class="pf-section-title pf-display">Trader reviews</h2>
                <p class="pf-section-sub" style="margin-bottom:1rem;">What traders say about {{ $firm->name }}.</p>
                @foreach($firm->reviews as $review)
                    <article class="pf-review">
                        <div class="pf-review__head">
                            <h3 class="pf-review__title">{{ $review->title }}</h3>
                            <span class="pf-score">★ {{ number_format($review->rating, 1) }}</span>
                        </div>
                        <p class="pf-review__meta">{{ $review->author ?? 'Trader' }}</p>
                        <p class="pf-review__body">{{ $review->content }}</p>
                    </article>
                @endforeach
            </section>
            @endif

            @if($settings->get('enable_faqs', true) && $firm->faqs->isNotEmpty())
            <section class="pf-card pf-detail__section">
                <h2 class="pf-section-title pf-display">Frequently asked questions</h2>
                <div class="pf-faqs" style="margin-top:1rem;">
                    @foreach($firm->faqs as $i => $faq)
                        <div class="pf-faq @if($i === 0) is-open @endif">
                            <button type="button" class="pf-faq__q" aria-expanded="{{ $i === 0 ? 'true' : 'false' }}">
                                {{ $faq->question }}
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                            </button>
                            <div class="pf-faq__a">{{ $faq->answer }}</div>
                        </div>
                    @endforeach
                </div>
            </section>
            @endif

            @if($related->isNotEmpty())
            <section class="pf-related">
                <h2 class="pf-section-title pf-display">Similar prop firms</h2>
                <div class="pf-related__grid">
                    @foreach($related as $rel)
                        <a href="{{ route('prop_firms.show', $rel->slug) }}" class="pf-card pf-related__card">
                            <span class="pf-spotlight__logo">@if($rel->logo)<img src="{{ asset($rel->logo) }}" alt="">@endif</span>
                            <span>
                                <strong style="display:block;font-size:0.9375rem;">{{ $rel->name }}</strong>
                                <small style="color:var(--pf-dim);">{{ $rel->max_funding ?? 'Funded accounts' }}</small>
                            </span>
                            @if($rel->trust_score)<span class="pf-score" style="margin-left:auto;font-size:0.75rem;">{{ number_format($rel->trust_score, 1) }}</span>@endif
                        </a>
                    @endforeach
                </div>
            </section>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.pf-faq__q').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var item = btn.closest('.pf-faq');
        var open = item.classList.contains('is-open');
        document.querySelectorAll('.pf-faq').forEach(function (el) {
            el.classList.remove('is-open');
            el.querySelector('.pf-faq__q')?.setAttribute('aria-expanded', 'false');
        });
        if (!open) {
            item.classList.add('is-open');
            btn.setAttribute('aria-expanded', 'true');
        }
    });
});
</script>
@endpush
