@foreach($page->sections as $section)
    @php
        $view = 'front.cms.sections.' . $section->section_type;
    @endphp
    @if(view()->exists($view))
        @include($view, [
            'data' => $section->section_data ?? [],
            'section' => $section,
            'page' => $page,
        ])
    @endif
@endforeach
