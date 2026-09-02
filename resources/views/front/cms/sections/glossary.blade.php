@php $d = $data ?? []; @endphp
<section class="cms-section cms-glossary">
    <div class="cms-wrap cms-wrap--narrow">
        @if(!empty($d['heading']))
            <h2 class="cms-section__title">{{ $d['heading'] }}</h2>
        @endif
        @if(!empty($d['intro']))
            <div class="cms-section__lead cms-prose">{!! \App\Support\RichText::forDisplay($d['intro'] ?? null) !!}</div>
        @endif
        @if(!empty($d['items']))
            <dl class="cms-glossary__list">
                @foreach($d['items'] as $item)
                    @if(!empty($item['term']))
                        <div class="cms-glossary__item" id="{{ \Illuminate\Support\Str::slug($item['term']) }}">
                            <dt>{{ $item['term'] }}</dt>
                            <dd class="cms-prose">{!! \App\Support\RichText::forDisplay($item['definition'] ?? null) !!}</dd>
                        </div>
                    @endif
                @endforeach
            </dl>
        @endif
    </div>
</section>
