<a href="{{ route('broker_detail', ['slug' => \App\Http\Controllers\Front\BrokerController::reviewSlugFor($broker)]) }}" class="{{ $class ?? 'bc-review-link' }}">
    <span class="bc-review-link__logo" aria-hidden="true">
        @if($broker->logo)
            <img src="{{ asset($broker->logo) }}" alt="" class="bc-review-link__logo-img" loading="lazy" decoding="async" width="22" height="22">
        @endif
    </span>
    <span class="bc-review-link__name">{{ $broker->name }} Review</span>
</a>
