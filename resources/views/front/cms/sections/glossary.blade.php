@php $d = $data ?? []; @endphp
<section class="cms-section cms-glossary">
    <div class="cms-wrap cms-wrap--narrow">
        @if(!empty($d['heading']))
            <h2 class="cms-section__title">{{ $d['heading'] }}</h2>
        @endif
        @if(!empty($d['intro']))
            <p class="cms-section__lead">{{ $d['intro'] }}</p>
        @endif
        @if(!empty($d['items']))
            <dl class="cms-glossary__list">
                @foreach($d['items'] as $item)
                    @if(!empty($item['term']))
                        <div class="cms-glossary__item" id="{{ \Illuminate\Support\Str::slug($item['term']) }}">
                            <dt>{{ $item['term'] }}</dt>
                            <dd>{!! nl2br(e($item['definition'] ?? '')) !!}</dd>
                        </div>
                    @endif
                @endforeach
            </dl>
        @endif
    </div>
</section>
