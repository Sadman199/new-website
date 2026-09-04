@php
    $content = $pageContent ?? [];
    $sections = [
        ['key' => 'how_to_use', 'title' => $howToTitle ?? 'How to use this calculator', 'id' => 'how-to-use'],
        ['key' => 'formula', 'title' => 'Formula', 'id' => 'formula'],
        ['key' => 'example', 'title' => 'Example calculation', 'id' => 'example'],
        ['key' => 'additional', 'title' => 'More detail', 'id' => 'more-detail'],
    ];
@endphp

@if(trim((string) ($content['how_to_use'] ?? '')) !== '' || trim((string) ($content['formula'] ?? '')) !== '' || trim((string) ($content['example'] ?? '')) !== '' || trim((string) ($content['additional'] ?? '')) !== '')
    <div class="calc-guide">
        @foreach($sections as $section)
            @php $text = trim((string) ($content[$section['key']] ?? '')); @endphp
            @if($text !== '')
                <section class="calc-guide__block" aria-labelledby="calc-{{ $section['id'] }}">
                    <h2 class="calc-guide__title" id="calc-{{ $section['id'] }}">{{ $section['title'] }}</h2>
                    <div class="calc-guide__prose">{!! nl2br(e($text)) !!}</div>
                </section>
            @endif
        @endforeach
    </div>
@endif
