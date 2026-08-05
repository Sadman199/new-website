@props(['title' => null, 'optional' => null])

<div {{ $attributes->merge(['class' => 'bc-card']) }}>
    @if($title)
        <div class="bc-card-header">
            <h3>{{ $title }}</h3>
            @if($optional)
                <span class="text-muted small">{{ $optional }}</span>
            @endif
        </div>
    @endif
    <div class="bc-card-body">
        {{ $slot }}
    </div>
</div>
