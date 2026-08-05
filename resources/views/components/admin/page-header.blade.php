@props(['title', 'description' => null])

<div class="page-header">
    <div>
        <h2>{{ $title }}</h2>
        @if($description)
            <p>{!! $description !!}</p>
        @endif
    </div>
    @isset($actions)
        <div class="page-actions">
            {{ $actions }}
        </div>
    @endisset
</div>
