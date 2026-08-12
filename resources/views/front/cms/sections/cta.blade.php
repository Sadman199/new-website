@php
    $d = $data ?? [];
    $isDark = ($d['style'] ?? 'primary') === 'dark';
@endphp
<section @class(['cms-section', 'cms-cta', 'cms-cta--dark' => $isDark])>
    <div class="cms-wrap cms-cta__inner">
        @if(!empty($d['heading']))
            <h2 class="cms-cta__title">{{ $d['heading'] }}</h2>
        @endif
        @if(!empty($d['text']))
            <p class="cms-cta__text">{!! nl2br(e($d['text'])) !!}</p>
        @endif
        @if(!empty($d['button_label']) && !empty($d['button_url']))
            <a href="{{ $d['button_url'] }}" class="cms-btn cms-btn--primary">{{ $d['button_label'] }}</a>
        @endif
    </div>
</section>
