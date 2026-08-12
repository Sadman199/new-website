@extends('front.layout.app')

@section('title', 'Is '.$detail['hero']['name'].' a Scam? '.date('Y').' Warning | BrokersCourt')
@section('meta_description', Str::limit($detail['flag']['reason'], 155))
@section('canonical', route('scam_broker_detail', ['slug' => $detail['broker']->scam_slug]))
@section('og_image', $detail['broker']->logo ?: '')

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/scam-broker-detail.css') }}?v=2">
@endpush

@section('main_content')
@php
    $hero = $detail['hero'];
    $broker = $detail['broker'];
@endphp
<div class="sbd-page">
    <header class="sbd-hero">
        <div class="sbd-hero__bg" aria-hidden="true"></div>
        <div class="sbd-wrap">
            <nav class="sbd-breadcrumb" aria-label="Breadcrumb">
                @foreach($detail['breadcrumb'] as $crumb)
                    @if($crumb['url'])
                        <a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a>
                        <span aria-hidden="true">/</span>
                    @else
                        <span>{{ $crumb['label'] }}</span>
                    @endif
                @endforeach
            </nav>

            <div class="sbd-hero__top">
                <div class="sbd-hero__brand">
                    <div class="sbd-hero__logo" aria-hidden="true">
                        @if($hero['logo'])
                            <img src="{{ $hero['logo'] }}" alt="">
                        @else
                            <span>{{ strtoupper(substr($hero['name'], 0, 1)) }}</span>
                        @endif
                    </div>
                    <div class="sbd-hero__identity">
                        <p class="sbd-hero__eyebrow">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                            </svg>
                            Scam / high-risk warning
                        </p>
                        <h1 class="sbd-hero__title">Is {{ $hero['name'] }} a <span class="sbd-hero__accent">scam</span>?</h1>
                        @if($hero['country'])
                            <p class="sbd-hero__meta-line">{{ $hero['country'] }}</p>
                        @endif
                    </div>
                </div>

                <div class="sbd-hero__verdict" aria-label="BrokersCourt suspected inactive status">
                    <div class="sbd-gavel" aria-hidden="true">
                        <div class="sbd-gavel__hammer">
                            <svg viewBox="0 0 64 64" fill="none">
                                <rect x="8" y="34" width="34" height="10" rx="2" fill="#94a3b8"/>
                                <rect x="36" y="28" width="18" height="16" rx="3" fill="#f87171"/>
                                <rect x="14" y="12" width="6" height="28" rx="2" fill="#cbd5e1" transform="rotate(-18 17 26)"/>
                            </svg>
                        </div>
                        <div class="sbd-gavel__block"></div>
                    </div>
                    <p class="sbd-hero__verdict-label">Suspected inactive</p>
                    <p class="sbd-hero__verdict-sub">BrokersCourt watchlist</p>
                    <span class="sbd-hero__verdict-risk sbd-hero__verdict-risk--{{ $hero['risk_level'] }}">{{ ucfirst($hero['risk_level']) }} risk</span>
                </div>
            </div>

            @include('front.partials.hero_metrics', [
                'stats' => array_values(array_filter([
                    [
                        'label' => 'Scam reported',
                        'value_html' => $hero['reported_date']
                            ? '<time datetime="'.$hero['reported_iso'].'">'.$hero['reported_date'].'</time>'
                            : 'Under review',
                        'tone' => 'primary',
                    ],
                    $hero['days_since_report'] !== null ? [
                        'label' => 'On watchlist',
                        'value' => $hero['days_since_report'].' days',
                    ] : null,
                    [
                        'label' => 'Flagged brokers',
                        'value' => number_format($scamCount),
                    ],
                    $detail['reports']['count'] > 0 ? [
                        'label' => 'User reports',
                        'value' => number_format($detail['reports']['count']),
                    ] : null,
                ])),
            ])

            @if(!empty($hero['warning_tags']))
                <div class="sbd-hero__tags">
                    @foreach($hero['warning_tags'] as $tag)
                        <span class="sbd-tag">{{ $tag['label'] }}</span>
                    @endforeach
                </div>
            @endif
        </div>
    </header>

    <div class="sbd-body">
        <div class="sbd-wrap">
            <div class="sbd-layout">
                <main class="sbd-main">
                    <section class="sbd-dossier" aria-labelledby="sbdFlagTitle">
                        <header class="sbd-dossier__head">
                            <span class="sbd-dossier__icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15a2.25 2.25 0 0 1 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z"/>
                                </svg>
                            </span>
                            <div>
                                <h2 class="sbd-dossier__title" id="sbdFlagTitle">{{ $detail['flag']['title'] }}</h2>
                                @if($hero['reported_date'])
                                    <p class="sbd-dossier__date">
                                        Reported <time datetime="{{ $hero['reported_iso'] }}">{{ $hero['reported_date'] }}</time>
                                        @if($hero['reported_relative'])
                                            · {{ $hero['reported_relative'] }}
                                        @endif
                                    </p>
                                @endif
                            </div>
                        </header>
                        <div class="sbd-dossier__body">
                            @foreach(preg_split('/\r\n|\r|\n/', $detail['flag']['reason']) as $paragraph)
                                @if(trim($paragraph) !== '')
                                    <p>{{ trim($paragraph) }}</p>
                                @endif
                            @endforeach
                        </div>
                    </section>

                    @if($broker->short_description || $broker->description)
                        <section class="sbd-panel" aria-labelledby="sbdContextTitle">
                            <h2 class="sbd-panel__title" id="sbdContextTitle">Background</h2>
                            <p class="sbd-panel__text">
                                {{ Str::limit(strip_tags($broker->short_description ?: $broker->description), 480) }}
                            </p>
                        </section>
                    @endif

                    <section class="sbd-panel" aria-labelledby="sbdSnapshotTitle">
                        <h2 class="sbd-panel__title" id="sbdSnapshotTitle">Broker snapshot</h2>
                        <p class="sbd-panel__lead">Publicly claimed details — unverified claims are a red flag on their own.</p>
                        <div class="sbd-facts">
                            @foreach($detail['snapshot'] as $fact)
                                <div class="sbd-fact sbd-fact--{{ $fact['status'] }}">
                                    <span class="sbd-fact__label">{{ $fact['label'] }}</span>
                                    <span class="sbd-fact__value">{{ $fact['value'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    <section class="sbd-panel sbd-panel--warn" aria-labelledby="sbdSignsTitle">
                        <h2 class="sbd-panel__title" id="sbdSignsTitle">Warning signs linked to this flag</h2>
                        <ul class="sbd-signs">
                            @foreach($detail['warning_signs'] as $sign)
                                <li class="sbd-signs__item">
                                    <span class="sbd-signs__icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                                        </svg>
                                    </span>
                                    <div>
                                        <strong>{{ $sign['title'] }}</strong>
                                        <p>{{ $sign['description'] }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </section>

                    <section class="sbd-panel" aria-labelledby="sbdStepsTitle">
                        <h2 class="sbd-panel__title" id="sbdStepsTitle">If you deposited with {{ $hero['name'] }}</h2>
                        <ol class="sbd-steps">
                            @foreach($detail['action_steps'] as $index => $step)
                                <li class="sbd-steps__item">
                                    <span class="sbd-steps__num">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                                    <div>
                                        <strong>{{ $step['title'] }}</strong>
                                        <p>{{ $step['body'] }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                    </section>
                </main>

                <aside class="sbd-sidebar">
                    <div class="sbd-callout sbd-callout--report">
                        <h3 class="sbd-callout__title">Affected by {{ $hero['name'] }}?</h3>
                        <p class="sbd-callout__text">Share your experience so we can warn other traders and keep this listing accurate.</p>
                        <a href="{{ $detail['links']['report'] }}" class="sbd-btn sbd-btn--danger">Report this broker</a>
                    </div>

                    <div class="sbd-callout sbd-callout--safe">
                        <h3 class="sbd-callout__title">Trade with a regulated broker</h3>
                        <p class="sbd-callout__text">Compare licensed platforms with verified oversight and transparent terms.</p>
                        <a href="{{ $detail['links']['regulated'] }}" class="sbd-btn sbd-btn--primary">See regulated brokers</a>
                    </div>

                    <div class="sbd-callout sbd-callout--ghost">
                        <h3 class="sbd-callout__title">Check another broker</h3>
                        <p class="sbd-callout__text">Run a name through our scam checker before you deposit.</p>
                        <a href="{{ $detail['links']['scam_checker'] }}" class="sbd-btn sbd-btn--ghost">Scam checker tool</a>
                    </div>

                    @if($related !== [])
                        <section class="sbd-related" aria-labelledby="sbdRelatedTitle">
                            <div class="sbd-related__head">
                                <h3 class="sbd-related__title" id="sbdRelatedTitle">Other flagged brokers</h3>
                                <a href="{{ $detail['links']['scam_index'] }}" class="sbd-related__all">View all</a>
                            </div>
                            <ul class="sbd-related__list">
                                @foreach($related as $item)
                                    @include('front.scam-brokers.partials.related_scam_card', [
                                        'broker' => $item,
                                        'warningFilters' => $warningFilters,
                                    ])
                                @endforeach
                            </ul>
                        </section>
                    @endif
                </aside>
            </div>

            <footer class="sbd-footer-nav">
                <a href="{{ $detail['links']['scam_index'] }}" class="sbd-back">
                    <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd"/></svg>
                    Back to scam broker list
                </a>
            </footer>
        </div>
    </div>
</div>
@endsection
