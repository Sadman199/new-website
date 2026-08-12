@php
    $d = $data ?? [];
    $cols = max(2, min(4, (int) ($d['columns'] ?? 3)));
@endphp
<section class="cms-section cms-cards">
    <div class="cms-wrap">
        @if(!empty($d['heading']))
            <h2 class="cms-section__title">{{ $d['heading'] }}</h2>
        @endif
        @if(!empty($d['subheading']))
            <p class="cms-section__lead">{{ $d['subheading'] }}</p>
        @endif
        @if(!empty($d['items']))
            <div class="cms-cards__grid cms-cards__grid--cols-{{ $cols }}">
                @foreach($d['items'] as $item)
                    <article class="cms-card">
                        @if(!empty($item['icon']))
                            <div class="cms-card__icon" aria-hidden="true">{{ $item['icon'] }}</div>
                        @endif
                        @if(!empty($item['title']))
                            <h3 class="cms-card__title">{{ $item['title'] }}</h3>
                        @endif
                        @if(!empty($item['text']))
                            <p class="cms-card__text">{!! nl2br(e($item['text'])) !!}</p>
                        @endif
                        @if(!empty($item['url']))
                            <a href="{{ $item['url'] }}" class="cms-card__link">Learn more</a>
                        @endif
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</section>
