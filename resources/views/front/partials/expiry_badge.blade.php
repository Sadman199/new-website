@php
    $badge = $badge ?? null;
    $class = $class ?? 'bc-expiry-badge';
    $pill = $pill ?? true;
@endphp
@if(!empty($badge))
    <span @class([
        $class,
        $class . '--' . ($badge['tone'] ?? 'normal'),
        $pill ? $class . '--pill' : null,
    ])>{{ $badge['short'] ?? $badge['label'] }}</span>
@endif
