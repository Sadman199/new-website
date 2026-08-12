@php $d = $data ?? []; @endphp
<section class="cms-section cms-stats">
    <div class="cms-wrap">
        @if(!empty($d['heading']))
            <h2 class="cms-section__title cms-section__title--center">{{ $d['heading'] }}</h2>
        @endif
        @if(!empty($d['items']))
            @include('front.partials.hero_metrics', [
                'stats' => collect($d['items'])->map(fn ($item, $i) => [
                    'label' => $item['label'] ?? '',
                    'value' => $item['value'] ?? '',
                    'tone' => ($item['tone'] ?? '') === 'highlight' ? 'highlight' : null,
                ])->all(),
                'class' => 'cms-stats__metrics',
            ])
        @endif
    </div>
</section>
