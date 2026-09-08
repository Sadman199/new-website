@extends('front.layout.app')

@section('title', $seo_title)
@section('meta_description', $meta_description)
@section('canonical', route('broker.alternatives.show', ['slug' => $broker->slug]))
@section('og_image', $broker->ogShareImageUrl())

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/broker-alternatives.css') }}?v=4">
@endpush

@push('json_ld')
    <script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}</script>
@endpush

@section('main_content')
<div class="bal-page">
    <header class="bal-hero">
        <div class="container">
            <nav class="bal-crumbs" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <a href="{{ route('broker.alternatives.index') }}">Broker alternatives</a>
                <span aria-hidden="true">/</span>
                <span>{{ $broker->name }}</span>
            </nav>
            <p class="bal-hero__eyebrow">Broker alternatives</p>
            <h1 class="bal-hero__title">Best Alternatives to <span>{{ $broker->name }}</span></h1>
            <p class="bal-hero__lead">{{ $intro }}</p>
            <div class="bal-hero__actions">
                <a href="{{ $review_url }}" class="bc-btn bc-btn--primary">Read {{ $broker->name }} review</a>
                <a href="{{ $compare_url }}" class="bc-btn bc-btn--ghost">Open comparison tool</a>
            </div>
        </div>
    </header>

    <div class="container bal-body">
        <section class="bal-section" aria-labelledby="bal-why-title">
            <h2 id="bal-why-title">Why traders consider alternatives</h2>
            <p class="bal-prose">{{ $why_consider }}</p>
        </section>

        <section class="bal-section" aria-labelledby="bal-rec-title">
            <header class="bal-section__head">
                <h2 id="bal-rec-title">
                    @if($source === 'automatic')
                        Automatically recommended alternatives
                    @else
                        Recommended alternatives
                    @endif
                </h2>
                @if($source === 'automatic')
                    <p>These brokers were scored from the live BrokersCourt catalog — excluding {{ $broker->name }} — using ratings, regulation, and similar trading conditions. Editorial picks, when saved, always replace this list.</p>
                @elseif($source === 'curated')
                    <p>Selected from the BrokersCourt broker database for this page.</p>
                @endif
            </header>

            @if($alternatives->isEmpty())
                <div class="bal-empty">
                    <p>We could not find enough suitable alternatives in the catalog right now. Compare {{ $broker->name }} directly or browse independent reviews.</p>
                    <div class="bal-empty__actions">
                        <a href="{{ $review_url }}" class="bc-btn bc-btn--primary">Read {{ $broker->name }} review</a>
                        <a href="{{ $compare_url }}" class="bc-btn bc-btn--ghost">Compare brokers</a>
                    </div>
                </div>
            @else
                <div class="bal-card-list">
                    @foreach($alternatives as $alternative)
                        @include('front.brokers.alternatives.partials.alt_card', [
                            'broker' => $alternative,
                            'rank' => $loop->iteration,
                        ])
                    @endforeach
                </div>
            @endif
        </section>

        @if($alternatives->isNotEmpty() && $rows !== [])
            <section class="bal-section" aria-labelledby="bal-compare-title">
                <header class="bal-section__head">
                    <h2 id="bal-compare-title">{{ $broker->name }} vs alternatives</h2>
                    <p>Figures come from the live broker database. Open a full review for context, or compare any pair side by side.</p>
                </header>
                <div class="bal-table-scroll">
                    <table class="bal-table">
                        <thead>
                            <tr>
                                <th scope="col">Feature</th>
                                @foreach(collect([$broker])->concat($alternatives) as $columnBroker)
                                    <th scope="col">{{ $columnBroker->name }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rows as $row)
                                <tr>
                                    <th scope="row">{{ $row['label'] }}</th>
                                    @foreach($row['cells'] as $cell)
                                        <td>{{ $cell }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="bal-cta-row">
                    @foreach($alternatives as $alternative)
                        <div class="bal-cta-chip">
                            <strong>{{ $alternative->name }}</strong>
                            <a href="{{ route('broker_detail', ['slug' => \App\Http\Controllers\Front\BrokerController::reviewSlugFor($alternative)]) }}">Full profile</a>
                            <a href="{{ \App\Services\BrokerComparisonService::canonicalPairUrl($broker->slug, $alternative->slug) }}">Compare vs {{ $broker->name }}</a>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        @if($faqs !== [])
            <section class="bal-section" aria-labelledby="bal-faq-title">
                <h2 id="bal-faq-title">Frequently asked questions</h2>
                <div class="bal-faq">
                    @foreach($faqs as $faq)
                        <details class="bal-faq__item">
                            <summary>{{ $faq['question'] }}</summary>
                            <p>{{ $faq['answer'] }}</p>
                        </details>
                    @endforeach
                </div>
            </section>
        @endif

        @if($related->isNotEmpty())
            <section class="bal-section" aria-labelledby="bal-related-title">
                <h2 id="bal-related-title">Related broker alternatives</h2>
                <div class="bal-source-grid">
                    @foreach($related as $relatedPage)
                        @php $relatedBroker = $relatedPage->broker; @endphp
                        <a href="{{ route('broker.alternatives.show', ['slug' => $relatedBroker->slug]) }}" class="bal-source-card">
                            <span class="bal-source-card__logo">
                                @if($relatedBroker->logo)
                                    <img src="{{ asset($relatedBroker->logo) }}" alt="" loading="lazy" decoding="async">
                                @else
                                    <span>{{ strtoupper(substr($relatedBroker->name, 0, 1)) }}</span>
                                @endif
                            </span>
                            <span class="bal-source-card__body">
                                <strong>{{ $relatedBroker->name }}</strong>
                                <span>Best alternatives</span>
                            </span>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</div>
@endsection
