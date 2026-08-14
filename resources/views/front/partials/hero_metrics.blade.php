@php
    $stats = $stats ?? [];
    $cols = $cols ?? min(4, max(2, count($stats)));
    $class = trim('bc-hm bc-hm--cols-' . $cols . ' ' . ($class ?? ''));
@endphp

@once
    <link rel="stylesheet" href="{{ asset('css/hero-metrics.css') }}?v=3">
@endonce

@if(count($stats))
    <dl class="{{ $class }}" aria-label="{{ $ariaLabel ?? 'Page statistics' }}">
        @foreach($stats as $stat)
            @php
                $tone = $stat['tone'] ?? null;
                $itemClass = 'bc-hm__item' . ($tone ? ' bc-hm__item--' . $tone : '');
            @endphp
            <div @class([$itemClass])>
                <dt>{{ $stat['label'] }}</dt>
                @if(!empty($stat['value_html']))
                    <dd @if(!empty($stat['id'])) id="{{ $stat['id'] }}" @endif>{!! $stat['value_html'] !!}</dd>
                @else
                    <dd @if(!empty($stat['id'])) id="{{ $stat['id'] }}" @endif>{{ $stat['value'] ?? '' }}</dd>
                @endif
            </div>
        @endforeach
    </dl>
@endif
