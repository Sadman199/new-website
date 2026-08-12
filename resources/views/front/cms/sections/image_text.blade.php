@php
    $d = $data ?? [];
    $imageLeft = ($d['image_position'] ?? 'right') === 'left';
@endphp
<section class="cms-section cms-image-text">
    <div class="cms-wrap cms-image-text__grid @if($imageLeft) cms-image-text__grid--reverse @endif">
        <div class="cms-image-text__content">
            @if(!empty($d['heading']))
                <h2 class="cms-section__title">{{ $d['heading'] }}</h2>
            @endif
            @if(!empty($d['body']))
                <div class="cms-prose">{!! nl2br(e($d['body'])) !!}</div>
            @endif
        </div>
        @if(!empty($d['image']))
            <div class="cms-image-text__media">
                <img src="{{ $d['image'] }}" alt="{{ $d['image_alt'] ?? '' }}" loading="lazy">
            </div>
        @endif
    </div>
</section>
