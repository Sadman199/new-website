@php $d = $data ?? []; @endphp
<section class="cms-section cms-timeline">
    <div class="cms-wrap cms-wrap--narrow">
        @if(!empty($d['heading']))
            <h2 class="cms-section__title">{{ $d['heading'] }}</h2>
        @endif
        @if(!empty($d['items']))
            <ol class="cms-timeline__list">
                @foreach($d['items'] as $item)
                    <li class="cms-timeline__item">
                        @if(!empty($item['year']))
                            <span class="cms-timeline__year">{{ $item['year'] }}</span>
                        @endif
                        @if(!empty($item['title']))
                            <h3 class="cms-timeline__title">{{ $item['title'] }}</h3>
                        @endif
                        @if(!empty($item['text']))
                            <p class="cms-timeline__text">{!! nl2br(e($item['text'])) !!}</p>
                        @endif
                    </li>
                @endforeach
            </ol>
        @endif
    </div>
</section>
