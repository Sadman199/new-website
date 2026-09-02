@extends('admin.layout.app')
@include('admin.brokers._assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')

@section('heading', 'Broker overview')

@php
    $logo = $broker->mediaUrl();
    $regulators = $broker->regulationList();
    $platforms = $broker->platformList();
    $markets = $broker->marketList();
    $categories = $broker->brokerCategoryList();
@endphp

@section('main_content')
<div class="ab-page">
    <div class="ab-wrap">
        <header class="ab-header">
            <div class="ab-identity">
                <span class="ab-logo ab-logo--lg">
                    @if($logo)
                        <img src="{{ $logo }}" alt="">
                    @else
                        <span>{{ strtoupper(substr($broker->name, 0, 1)) }}</span>
                    @endif
                </span>
                <div>
                    <p class="ab-header__eyebrow">Overview</p>
                    <h1 class="ab-header__title">{{ $broker->name }}</h1>
                    <p class="ab-header__sub">
                        {{ $broker->slug }}
                        @if($broker->country) · {{ $broker->country }} @endif
                        @if($broker->year_founded) · Est. {{ $broker->year_founded }} @endif
                    </p>
                    <div class="ab-pills">
                        @if($broker->featured_broker)<span class="ab-pill ab-pill--warn">Featured</span>@endif
                        @if($broker->is_scam)<span class="ab-pill ab-pill--danger">Scam flagged</span>@else<span class="ab-pill ab-pill--ok">Live</span>@endif
                        @if($broker->rating)<span class="ab-pill">{{ number_format((float) $broker->rating, 1) }}/5</span>@endif
                    </div>
                </div>
            </div>
            <div class="ab-header__actions">
                @if($broker->slug)
                    <a href="{{ route('broker_detail', $broker->slug) }}" class="ab-btn ab-btn--ghost" target="_blank" rel="noopener">View on site</a>
                @endif
                <a href="{{ route('admin_broker_edit', $broker->id) }}" class="ab-btn ab-btn--primary">Edit profile</a>
            </div>
        </header>

        @include('admin.brokers._tabs', ['broker' => $broker, 'activeTab' => 'view'])

        <div class="ab-kpis">
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-layer-group"></i></span>
                <div>
                    <p class="ab-kpi__label">Accounts</p>
                    <p class="ab-kpi__value">{{ $broker->account_options_count }}</p>
                </div>
            </div>
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-star"></i></span>
                <div>
                    <p class="ab-kpi__label">Reviews</p>
                    <p class="ab-kpi__value">{{ $broker->reviews_count }}</p>
                </div>
            </div>
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-book-open"></i></span>
                <div>
                    <p class="ab-kpi__label">Guides</p>
                    <p class="ab-kpi__value">{{ $broker->guides_count }}</p>
                </div>
            </div>
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-gift"></i></span>
                <div>
                    <p class="ab-kpi__label">Promos</p>
                    <p class="ab-kpi__value">{{ $broker->forex_bonuses_count }}</p>
                </div>
            </div>
        </div>

        <section class="ab-section">
            <div class="ab-section__head">
                <h2>Profile</h2>
                <p>Core listing data shown across reviews and comparison pages.</p>
            </div>
            <div class="ab-section__body">
                <dl class="ab-dl">
                    <div><dt>Website</dt><dd>{{ $broker->url ?: '—' }}</dd></div>
                    <div><dt>Visit URL</dt><dd>{{ $broker->visit_site ?: '—' }}</dd></div>
                    <div><dt>Languages</dt><dd>{{ $broker->languages ?: '—' }}</dd></div>
                    <div><dt>Min. deposit</dt><dd>{{ $broker->minimum_deposit !== null ? '$'.number_format((float) $broker->minimum_deposit, 0) : '—' }}</dd></div>
                    <div><dt>Spreads</dt><dd>{{ $broker->spreads ?: '—' }}</dd></div>
                    <div><dt>Leverage</dt><dd>{{ $broker->leverage ?: '—' }}</dd></div>
                    <div><dt>Trust score</dt><dd>{{ $broker->trust_score ? $broker->trust_score.'/99' : '—' }}</dd></div>
                    <div><dt>Regulatory tier</dt><dd>{{ $broker->regulatory_tier ?: '—' }}</dd></div>
                    <div><dt>Fee level</dt><dd>{{ $broker->fee_level ? ucfirst($broker->fee_level) : '—' }}</dd></div>
                </dl>
            </div>
        </section>

        <section class="ab-section">
            <div class="ab-section__head">
                <h2>Coverage</h2>
            </div>
            <div class="ab-section__body">
                <dl class="ab-dl">
                    <div>
                        <dt>Regulators</dt>
                        <dd>{{ $regulators ? implode(', ', $regulators) : '—' }}</dd>
                    </div>
                    <div>
                        <dt>Platforms</dt>
                        <dd>{{ $platforms ? implode(', ', $platforms) : '—' }}</dd>
                    </div>
                    <div>
                        <dt>Markets</dt>
                        <dd>{{ $markets ? implode(', ', $markets) : '—' }}</dd>
                    </div>
                    <div>
                        <dt>Categories</dt>
                        <dd>{{ $categories ? implode(', ', $categories) : '—' }}</dd>
                    </div>
                    <div>
                        <dt>Regions</dt>
                        <dd>{{ $broker->regionList() ? implode(', ', $broker->regionList()) : '—' }}</dd>
                    </div>
                    <div>
                        <dt>Demo account</dt>
                        <dd>{{ $broker->demo_account_available ? 'Available' : 'Not listed' }}</dd>
                    </div>
                </dl>
            </div>
        </section>

        @if($broker->verdict || $broker->short_description)
            <section class="ab-section">
                <div class="ab-section__head">
                    <h2>Editorial</h2>
                </div>
                <div class="ab-section__body">
                    @if($broker->verdict)
                        <h6>Verdict</h6>
                        <p class="ab-prose">{{ strip_tags($broker->verdict) }}</p>
                    @endif
                    @if($broker->short_description)
                        <h6>Short description</h6>
                        <p class="ab-prose">{{ \Illuminate\Support\Str::limit(strip_tags($broker->short_description), 600) }}</p>
                    @endif
                </div>
            </section>
        @endif

        @if($broker->is_scam)
            <section class="ab-section">
                <div class="ab-section__head">
                    <h2>Scam flag</h2>
                </div>
                <div class="ab-section__body">
                    <dl class="ab-dl">
                        <div><dt>Reported</dt><dd>{{ optional($broker->scam_reported_date)->format('M j, Y') ?: '—' }}</dd></div>
                        <div><dt>Reason</dt><dd>{{ $broker->scam_reason ?: '—' }}</dd></div>
                    </dl>
                </div>
            </section>
        @endif
    </div>
</div>
@endsection
