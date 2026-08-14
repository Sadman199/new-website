@props(['broker'])

@php
    $rating = $broker->rating;
    $ratingClass = $rating == 5 ? 'text-green-500' : 'text-yellow-500';
@endphp

<div class="item">
    <div class="bg-white rounded-lg shadow-sm p-4 text-center border border-gray-200 hover:shadow-md transition-all duration-200 hover:border-gray-100 group">
        <div class="flex items-center justify-center mb-3">
            <!-- Left wing icon (grayscale) -->
            <img src="{{ asset('public/award_2.png') }}" alt="" class="h-10 mr-2 filter grayscale opacity-80 group-hover:opacity-100 transition-opacity" loading="lazy" decoding="async">

            <!-- Broker logo -->
            <a href="{{ $broker->url }}" class="inline-block hover:no-underline mx-2">
                <img src="{{ asset($broker->logo) }}" alt="{{ $broker->name }}" class="h-8 object-contain group-hover:scale-105 transition-transform" loading="lazy" decoding="async">
            </a>
            
            <!-- Right wing icon (grayscale) -->
            <img src="{{ asset('public/award_1.png') }}" alt="" class="h-10 ml-2 filter grayscale opacity-80 group-hover:opacity-100 transition-opacity" loading="lazy" decoding="async">
        </div>
        

        <div class="flex flex-col items-center">
            <div class="flex items-center mb-1">
                <div class="flex {{ $ratingClass }} mr-1">
                    @for ($i = 1; $i <= 5; $i++)
                        <span class="{{ $i <= $rating ? 'filled' : ($i - 0.5 == $rating ? 'half' : '') }}">
                            {!! $i <= $rating || $i - 0.5 == $rating ? '★' : '☆' !!}
                        </span>
                    @endfor
                </div>
                <span class="text-xs font-medium text-gray-500 ml-1">{{ $rating }}/5</span>
            </div>
            
            <a href="{{ $broker->url }}" target="_blank" rel="noopener noreferrer" class="mt-2 px-3 py-1.5 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 hover:border-yellow-400 transition-all duration-200 text-xs sm:text-sm flex items-center">
                Visit Broker
            </a>

        </div>
    </div>
</div>