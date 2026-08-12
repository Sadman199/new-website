@php $d = $data ?? []; @endphp
<section class="cms-section cms-faq">
    <div class="cms-wrap cms-wrap--narrow">
        @if(!empty($d['heading']))
            <h2 class="cms-section__title">{{ $d['heading'] }}</h2>
        @endif
        @if(!empty($d['items']))
            <div class="cms-faq__list">
                @foreach($d['items'] as $index => $item)
                    <details class="cms-faq__item" @if($index === 0) open @endif>
                        <summary>{{ $item['question'] ?? '' }}</summary>
                        <div class="cms-prose">{!! nl2br(e($item['answer'] ?? '')) !!}</div>
                    </details>
                @endforeach
            </div>
        @endif
    </div>
</section>
