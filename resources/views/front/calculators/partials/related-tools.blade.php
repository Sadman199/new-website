@if(($relatedTools ?? collect())->isNotEmpty())
    <section class="calc-related" aria-labelledby="calc-related-tools-title">
        <div class="calc-related__head">
            <h2 class="calc-related__title" id="calc-related-tools-title">Related calculators</h2>
            <p class="calc-related__lead">Continue with the next tool in your planning flow.</p>
        </div>
        <div class="calc-related__grid">
            @foreach($relatedTools as $item)
                <a href="{{ $item->public_url }}" class="calc-related__card">
                    <i class="{{ $item->icon ?? 'fas fa-calculator' }}" aria-hidden="true"></i>
                    <span>
                        <strong>{{ $item->name }}</strong>
                        @if(trim((string) ($item->short_description ?? '')) !== '')
                            <small>{{ $item->short_description }}</small>
                        @endif
                    </span>
                </a>
            @endforeach
        </div>
    </section>
@endif
