@php
    $fact = $entry['facts'][$field['key']] ?? ['known' => false];
    $known = ! empty($fact['known']);
    $items = [];

    if ($known && (! empty($field['chips']) || ! empty($field['count']))) {
        if (! empty($fact['items'])) {
            $items = $fact['items'];
        } else {
            $items = preg_split('/\s*,\s*/', (string) $fact['value'], -1, PREG_SPLIT_NO_EMPTY) ?: [];
        }
    }

    $rating = $field['key'] === 'rating' && $known ? (float) ($fact['numeric'] ?? 0) : null;
@endphp

<div @class([
    'bgx-review__spec',
    'bgx-review__spec--count' => ! empty($field['count']),
    'is-unknown' => ! $known,
])>
    <dt>{{ $field['label'] }}</dt>
    <dd>
        @if(! $known)
            <span class="bgx-table__unknown">Not disclosed</span>
        @elseif($rating !== null)
            <span class="bgx-stars" aria-label="Rated {{ $fact['value'] }}">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= floor($rating))
                        <i class="fas fa-star" aria-hidden="true"></i>
                    @elseif($i - 0.5 <= $rating)
                        <i class="fas fa-star-half-alt" aria-hidden="true"></i>
                    @else
                        <i class="far fa-star" aria-hidden="true"></i>
                    @endif
                @endfor
                <span class="bgx-stars__value">{{ $fact['value'] }}</span>
            </span>
        @elseif(! empty($field['count']))
            {{ max(count($items), (int) ($fact['numeric'] ?? 0)) }}
        @elseif($field['key'] === 'swap_free')
            <span @class(['bgx-status', 'bgx-status--yes' => ($fact['numeric'] ?? 0) > 0, 'bgx-status--no' => ($fact['numeric'] ?? 0) <= 0])>
                <i class="fas {{ ($fact['numeric'] ?? 0) > 0 ? 'fa-check' : 'fa-minus' }}" aria-hidden="true"></i>
                {{ $fact['value'] }}
            </span>
            @if(($fact['numeric'] ?? 0) > 0)
                <span class="bgx-review__note">Islamic account</span>
            @endif
        @elseif($items !== [])
            <ul class="bgx-chips">
                @foreach($items as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>
        @else
            {{ $fact['value'] }}
        @endif
    </dd>
</div>
