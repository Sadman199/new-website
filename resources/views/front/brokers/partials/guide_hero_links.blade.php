@php
    use App\Services\BrokerGuideService;
    $guideService = app(BrokerGuideService::class);
    $guides = $publishedGuides ?? collect();
    $guideCount = $guides->count();
    $guideSubtitle = $guideHubDescription ?? 'Step-by-step guides for accounts, demo setup, and funding.';
@endphp

@if($guideCount > 0)
    <details class="br-hero-guides-acc">
        <summary class="br-hero-guides-acc__trigger">
            <span class="br-hero-guides-acc__icon" aria-hidden="true">
                <i class="fas fa-book-open"></i>
            </span>
            <span class="br-hero-guides-acc__copy">
                <strong class="br-hero-guides-acc__title">Account guides</strong>
                <span class="br-hero-guides-acc__meta">
                    {{ $guideCount }} {{ Str::plural('guide', $guideCount) }} · {{ Str::limit($guideSubtitle, 72) }}
                </span>
            </span>
            <span class="br-hero-guides-acc__badge" aria-hidden="true">{{ $guideCount }}</span>
            <span class="br-hero-guides-acc__chevron" aria-hidden="true">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </span>
        </summary>

        <div class="br-hero-guides-acc__panel">
            <ul class="br-hero-guides-acc__list">
                @foreach($guides as $guide)
                    @php $topic = $guide->topic; @endphp
                    <li>
                        <a href="{{ $guideService->publicUrl($guide) }}" class="br-hero-guides-acc__link">
                            <span class="br-hero-guides-acc__link-icon" aria-hidden="true">
                                @if($topic?->icon)
                                    <i class="{{ $topic->icon }}"></i>
                                @else
                                    <i class="fas fa-file-alt"></i>
                                @endif
                            </span>
                            <span class="br-hero-guides-acc__link-body">
                                <strong>{{ $guide->title }}</strong>
                                @if($guide->summary)
                                    <span>{{ $guide->summary }}</span>
                                @endif
                            </span>
                            <span class="br-hero-guides-acc__link-arrow" aria-hidden="true">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </details>
@endif
