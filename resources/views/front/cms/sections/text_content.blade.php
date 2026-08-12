@php $d = $data ?? []; @endphp
<section class="cms-section cms-text" @if(($d['align'] ?? 'left') === 'center') style="text-align:center" @endif>
    <div class="cms-wrap">
        @if(!empty($d['heading']))
            <h2 class="cms-section__title">{{ $d['heading'] }}</h2>
        @endif
        @if(!empty($d['body']))
            <div class="cms-prose">{!! $d['body'] !!}</div>
        @endif
    </div>
</section>
