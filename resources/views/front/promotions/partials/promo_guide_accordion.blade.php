@if(!empty($guide['type_guides']))
    <section class="bpr-accordion-section" aria-labelledby="bprGuideTitle">
        <div class="bpr-accordion-section__head">
            <p class="bpr-section__eyebrow">Quick guide</p>
            <h2 class="bpr-accordion-section__title" id="bprGuideTitle">Understanding broker promotions</h2>
            <p class="bpr-accordion-section__lead">Short explanations for each offer type — expand a category before you claim.</p>
        </div>

        <div class="bpr-accordion">
            @foreach($guide['type_guides'] as $item)
                <details class="bpr-accordion__item">
                    <summary class="bpr-accordion__summary">
                        <span class="bpr-accordion__title">{{ $item['name'] }}</span>
                        <span class="bpr-accordion__meta">{{ $item['count'] }} live</span>
                    </summary>
                    <div class="bpr-accordion__body">
                        <p>{{ $item['description'] }}</p>
                        @if(($item['count'] ?? 0) > 0)
                            <a href="{{ $item['url'] }}" class="bpr-accordion__link">
                                Browse {{ strtolower($item['name']) }}
                            </a>
                        @endif
                    </div>
                </details>
            @endforeach

            @if(!empty($guide['evaluate_steps']))
                <details class="bpr-accordion__item">
                    <summary class="bpr-accordion__summary">
                        <span class="bpr-accordion__title">How to evaluate any promotion</span>
                    </summary>
                    <div class="bpr-accordion__body">
                        <ol class="bpr-accordion__steps">
                            @foreach($guide['evaluate_steps'] as $step)
                                <li>{{ $step }}</li>
                            @endforeach
                        </ol>
                    </div>
                </details>
            @endif
        </div>
    </section>
@endif
