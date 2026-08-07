<aside class="bc-result-sidebar" aria-label="Comparison navigation">
    <div class="bc-result-sidebar__block">
        <p class="bc-result-sidebar__title">Jump to section</p>
        <nav class="bc-result-sidebar__nav">
            @foreach($comparison['toc'] as $item)
                <a href="#bc-result-{{ $item['id'] }}" class="bc-result-sidebar__link" data-result-toc="{{ $item['id'] }}">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>
    </div>

    @if(!empty($comparison['summary']))
        <div class="bc-result-sidebar__block">
            <p class="bc-result-sidebar__title">Quick wins</p>
            <ul class="bc-result-sidebar__summary">
                @foreach($comparison['summary'] as $item)
                    <li>
                        <span class="bc-result-sidebar__summary-label">{{ $item['label'] }}</span>
                        <strong>{{ $item['broker'] }}</strong>
                        <span class="bc-result-sidebar__summary-value">{{ $item['value'] }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bc-result-sidebar__block bc-result-sidebar__block--cta">
        <p class="bc-result-sidebar__title">Safety check</p>
        <p class="bc-result-sidebar__copy">Run an independent scam and safety analysis for either broker.</p>
        <div class="bc-result-sidebar__safety-links">
            <a href="{{ $comparison['broker1']['scam_checker_url'] }}" class="bc-result-sidebar__safety-link">
                {{ $comparison['broker1']['name'] }}
            </a>
            <a href="{{ $comparison['broker2']['scam_checker_url'] }}" class="bc-result-sidebar__safety-link">
                {{ $comparison['broker2']['name'] }}
            </a>
        </div>
    </div>
</aside>
