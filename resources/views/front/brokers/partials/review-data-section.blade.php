@props(['section'])

@if(!empty($section['preview']) || !empty($section['more']))
<section class="br-section" id="{{ $section['id'] }}">
    <div class="br-section__head">
        <div class="br-section__head-row">
            <div>
                <h2 class="br-section__title">{{ $section['title'] }}</h2>
                @if(!empty($section['description']))
                    <p class="br-section__desc">{{ $section['description'] }}</p>
                @endif
            </div>
            @if(!empty($section['score']))
                <div class="br-section__score">
                    <span class="br-section__score-value">{{ $section['score'] }}</span>
                    <span class="br-section__score-label">Score /10</span>
                </div>
            @endif
        </div>
    </div>
    <div class="br-section__body">
        @if(!empty($section['preview']))
            <dl class="br-dl">
                @foreach($section['preview'] as $row)
                    <div class="br-dl__row">
                        <dt>{{ $row['label'] }}</dt>
                        <dd>
                            @if(!empty($row['html']))
                                {!! $row['value'] !!}
                            @else
                                {{ $row['value'] }}
                            @endif
                        </dd>
                    </div>
                @endforeach
            </dl>
        @endif

        @if(!empty($section['more']))
            <div class="br-section__more" id="more-{{ $section['id'] }}" hidden>
                <dl class="br-dl br-dl--more">
                    @foreach($section['more'] as $row)
                        <div class="br-dl__row">
                            <dt>{{ $row['label'] }}</dt>
                            <dd>
                                @if(!empty($row['html']))
                                    {!! $row['value'] !!}
                                @else
                                    {{ $row['value'] }}
                                @endif
                            </dd>
                        </div>
                    @endforeach
                </dl>
            </div>
            <button type="button"
                    class="br-read-more"
                    data-br-target="more-{{ $section['id'] }}"
                    aria-expanded="false">
                <span class="br-read-more__show">Read more about {{ $section['title'] }}</span>
                <span class="br-read-more__hide" hidden>Show less</span>
            </button>
        @endif
    </div>
</section>
@endif
