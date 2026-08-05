@php
    $assessmentBrokers = $all_brokers->take(6);
    $metrics = [
        ['key' => 'speed', 'label' => 'Trading Speed', 'sub' => 'Avg. speed', 'suffix' => ' ms'],
        ['key' => 'stability', 'label' => 'Stability', 'sub' => 'Avg. daily disconnection', 'suffix' => ' times'],
        ['key' => 'liquidity', 'label' => 'Liquidity', 'sub' => 'Avg. quoting freq.', 'suffix' => ' times/min'],
    ];
@endphp
<div class="bv-section bv-section--assessment">
    <div class="bv-section__head">
        <span class="bv-section__num">3</span>
        <span class="bv-section__title">Live Trading Assessment</span>
        <a href="{{ route('broker.reviews.index') }}" class="bv-section__link">View all ›</a>
    </div>
    <div class="bv-assessment-grid">
        @foreach($metrics as $metric)
            <div class="bv-assessment-col">
                <div class="bv-assessment-col__head">
                    <strong>{{ $metric['label'] }}</strong>
                    <span>{{ $metric['sub'] }}</span>
                </div>
                <ul class="bv-assessment-list">
                    @foreach($assessmentBrokers as $i => $broker)
                        @php
                            $demoValues = [
                                'speed' => [124, 133, 146, 217, 284, 320],
                                'stability' => [0.2, 0.5, 0.7, 0.8, 0.9, 1.1],
                                'liquidity' => [64, 74, 69, 64, 57, 55],
                            ];
                            $val = $demoValues[$metric['key']][$i] ?? '—';
                        @endphp
                        <li>
                            <a href="{{ route('broker_detail', $broker->slug) }}" class="bv-assessment-item">
                                <span class="bv-assessment-item__name">{{ $broker->name }}</span>
                                <span class="bv-assessment-item__val">{{ $val }}{{ $metric['suffix'] }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>
</div>
