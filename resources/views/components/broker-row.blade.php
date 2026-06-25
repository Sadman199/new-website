@props(['broker'])

@php
    $leverageNum = (int) filter_var($broker->leverage, FILTER_SANITIZE_NUMBER_INT);
    $leverageLevel = min(5, ceil($leverageNum / 200));

    // Normalize country value
    $countryRaw = strtolower(trim($broker->country ?? ''));

    // Flexible country keyword matching
    $countryMap = [
        'australia' => 'au',
        'united kingdom' => 'gb',
        'uk' => 'gb',
        'england' => 'gb',
        'united states' => 'us',
        'usa' => 'us',
        'america' => 'us',
        'cyprus' => 'cy',
        'belize' => 'bz',
        'seychelles' => 'sc',
        'israel' => 'il',
        'mauritius' => 'mu',
        'switzerland' => 'ch',
        'vanuatu' => 'vu',
        'south africa' => 'za',
        'canada' => 'ca',
        'germany' => 'de',
        'singapore' => 'sg',
        'hong kong' => 'hk',
        'japan' => 'jp',
        'new zealand' => 'nz',
        'uae' => 'ae',
        'united arab emirates' => 'ae',
        'st. vincent' => 'vc',
        'st vincent' => 'vc',
        'saint vincent' => 'vc',
        'bahamas' => 'bs',
        'marshall islands' => 'mh',
        'indonesia' => 'id',
        'malaysia' => 'my',
        'bangladesh' => 'bd',
    ];

    // Smart partial match for country names
    $countryCode = 'us'; // default fallback
    foreach ($countryMap as $name => $code) {
        if (Str::contains($countryRaw, $name)) {
            $countryCode = $code;
            break;
        }
    }

    // If broker->country_code exists, it overrides detection
    if (!empty($broker->country_code)) {
        $countryCode = strtolower($broker->country_code);
    }
@endphp

<div class="block transition-all duration-500">
    <div class="flex w-full flex-col rounded-3xl bg-white shadow-sm border border-gray-200 h-full max-sm:flex-row max-sm:items-center max-sm:gap-3 max-sm:rounded-2xl max-sm:p-3">

        <!-- Image -->
           <div class="w-full flex items-center justify-center h-20 px-3 
                    max-sm:w-28 max-sm:h-8 max-sm:rounded-lg">
           <a href="{{ route('broker_detail', ['slug' => $broker->slug]) }}" 
               class="flex items-center justify-center w-full h-full">
                @if ($broker->logo)
                    <img src="{{ asset($broker->logo) }}" 
                         alt="{{ $broker->name }}"
                         class="max-h-8 max-w-24 object-contain">
                @else
                    <div class="flex items-center justify-center w-full h-full bg-gray-100 rounded-lg">
                        <i class="fas fa-landmark text-blue-500 text-sm"></i>
                    </div>
                @endif
            </a>
        </div>


        <!-- Content -->
        <div class="flex flex-col items-center gap-1 px-4 pb-3 pt-2 flex-grow">

            <a href="{{ route('broker_detail', ['slug' => $broker->slug]) }}" class="text-center text-base font-bold max-sm:text-sm">
                {{ $broker->name }}
            </a>

            <div class="flex items-center gap-2 mb-2">
                <div class="flex items-center">
                    <div class="flex text-yellow-400 text-xs mr-1">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= floor($broker->rating))
                                <i class="fas fa-star"></i>
                            @elseif ($i - 0.5 <= $broker->rating)
                                <i class="fas fa-star-half-alt"></i>
                            @else
                                <i class="far fa-star text-gray-300"></i>
                            @endif
                        @endfor
                    </div>
                    <span class="text-xs font-bold text-gray-700">{{ number_format($broker->rating, 1) }}</span>
                </div>

                @if($broker->country)
                    <img src="https://flagcdn.com/16x12/{{ strtolower($countryCode) }}.png" class="w-4 h-3 rounded shadow-sm">
                @endif
            </div>

            <a href="{!! $broker->url !!}" target="_blank" rel="noopener nofollow"
               class="flex h-10 w-full items-center justify-center gap-1 rounded-lg border border-gray-200 hover:bg-gray-50 transition">
                <span class="text-sm font-medium max-sm:text-xs">Register</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
            </a>

            <p class="text-xs text-gray-500 mt-1">Your capital is at risk.</p>

            <button class="save-btn mt-1 flex items-center gap-1 text-gray-500 hover:text-blue-600 text-xs transition"
                    data-broker-id="{{ $broker->id }}">
                <i class="far fa-bookmark"></i>
                <span>Save</span>
            </button>
        </div>
    </div>
</div>

<script>
$(function() {
    let saved = JSON.parse(localStorage.getItem('savedBrokers')) || [];
    $('.save-btn').each(function() {
        const btn = $(this), id = btn.data('broker-id');
        if (saved.includes(id.toString())) update(btn, true);
        btn.on('click', function() {
            const isSaved = saved.includes(id.toString());
            saved = isSaved ? saved.filter(i => i !== id.toString()) : [...saved, id.toString()];
            update(btn, !isSaved);
            localStorage.setItem('savedBrokers', JSON.stringify(saved));
        });
    });
    function update(btn, s) {
        btn.toggleClass('text-blue-600', s);
        btn.find('i').toggleClass('far fas');
        btn.find('span').text(s ? 'Saved' : 'Save');
    }
});
</script>
