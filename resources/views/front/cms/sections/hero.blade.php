@php
    $d = $data ?? [];
    $isDark = ($d['background_style'] ?? 'dark') === 'dark';
    $pageTitle = $page->title ?? 'Page';
@endphp
<section @class(['cms-hero', 'cms-hero--dark' => $isDark, 'cms-hero--light' => ! $isDark]) aria-label="Page header">
    <div class="cms-hero__bg" aria-hidden="true"></div>
    <div class="cms-wrap">
        <nav class="cms-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span aria-hidden="true">/</span>
            <span>{{ $pageTitle }}</span>
        </nav>

        @if(!empty($d['eyebrow']))
            <p class="cms-hero__eyebrow">{{ $d['eyebrow'] }}</p>
        @endif
        @if(!empty($d['headline']))
            <h1 class="cms-hero__title">{!! nl2br(e($d['headline'])) !!}</h1>
        @endif
        @if(!empty($d['subheadline']))
            <p class="cms-hero__subtitle">{!! nl2br(e($d['subheadline'])) !!}</p>
        @endif

        @if(!empty($d['metrics']))
            @include('front.partials.hero_metrics', [
                'stats' => collect($d['metrics'])->map(fn ($m) => [
                    'label' => $m['label'] ?? '',
                    'value' => $m['value'] ?? '',
                    'tone' => ($m['tone'] ?? '') === 'highlight' ? 'highlight' : null,
                ])->all(),
            ])
        @endif

        @if(!empty($d['cta_label']) || !empty($d['secondary_cta_label']))
            <div class="cms-hero__actions">
                @if(!empty($d['cta_label']) && !empty($d['cta_url']))
                    <a href="{{ $d['cta_url'] }}" class="cms-btn cms-btn--primary">{{ $d['cta_label'] }}</a>
                @endif
                @if(!empty($d['secondary_cta_label']) && !empty($d['secondary_cta_url']))
                    <a href="{{ $d['secondary_cta_url'] }}" class="cms-btn cms-btn--ghost">{{ $d['secondary_cta_label'] }}</a>
                @endif
            </div>
        @endif
    </div>
</section>
