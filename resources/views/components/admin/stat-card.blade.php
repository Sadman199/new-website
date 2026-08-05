@props(['icon', 'iconClass', 'value', 'label', 'change' => null])

<div class="stat-card">
    <div class="stat-icon {{ $iconClass }}">
        <i class="{{ $icon }}"></i>
    </div>
    <div class="stat-value">{{ $value }}</div>
    <div class="stat-label">{{ $label }}</div>
    @if($change)
        <div class="stat-change">{!! $change !!}</div>
    @endif
</div>
