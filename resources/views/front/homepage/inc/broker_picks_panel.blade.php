@php
    $brokers = ($brokers ?? collect())->take(8)->values();
    $emptyMessage = $empty ?? null;
@endphp

@if($brokers->isEmpty())
    <p class="bc-picks__empty">{{ $emptyMessage ?? 'No brokers available for this category yet.' }}</p>
@else
    <div class="bc-picks__layout">
        @foreach($brokers as $i => $broker)
            @include('front.homepage.inc.broker_tile', [
                'broker' => $broker,
                'rank' => $i + 1,
                'featured' => $i === 0,
            ])
        @endforeach
    </div>
@endif
