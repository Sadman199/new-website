<a href="{{ route('broker_detail', ['slug' => $broker->slug]) }}" class="block">
    <article class="bg-white border-b border-gray-200 py-4 px-4 hover:bg-gray-50 transition-colors duration-200">
        <div class="flex items-center justify-between">
            <!-- Left: Logo and Name -->
            <div class="flex items-center space-x-4 flex-1">
                <!-- Logo -->
                <div class="w-20 h-12 flex items-center justify-center bg-white border border-gray-200 rounded-lg p-2">
                    <img 
                        class="h-8 w-auto object-contain" 
                        alt="{{ $broker->name }} logo" 
                        src="{{ asset($broker->logo) }}" 
                    />
                </div>
                
                <!-- Broker Name -->
                <div class="flex flex-col">
                    <h3 class="font-medium text-gray-900 text-sm tracking-tight">
                        {{ $broker->name }}
                    </h3>
                    <!-- Regulation Status -->
                    <span class="text-xs mt-0.5 {{ $broker->isRegulated() ? 'bc-regulated-tag px-2 py-1 rounded-full' : 'text-gray-500' }}">
                        {{ $broker->isRegulated() ? 'Regulated' : 'Non-Regulated' }}
                    </span>
                </div>
            </div>

            <!-- Right: Rating -->
            <div class="flex items-center space-x-2">
                <!-- Rating Number -->
                <span class="text-sm font-semibold text-gray-900 min-w-[35px] text-right">
                    {{ number_format($broker->rating, 1) }}
                </span>
                
                <!-- Stars -->
                <div class="flex items-center space-x-0.5">
                    @php
                        $rating = $broker->rating;
                        $fullStars = floor($rating);
                        $hasHalfStar = $rating - $fullStars >= 0.5;
                    @endphp

                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $fullStars)
                            <svg class="w-3 h-3 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @elseif($i == $fullStars + 1 && $hasHalfStar)
                            <svg class="w-3 h-3 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <defs>
                                    <linearGradient id="half-star-{{ $broker->id }}" x1="0" x2="100%" y1="0" y2="0">
                                        <stop offset="50%" stop-color="currentColor"/>
                                        <stop offset="50%" stop-color="#E5E7EB"/>
                                    </linearGradient>
                                </defs>
                                <path fill="url(#half-star-{{ $broker->id }})" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @else
                            <svg class="w-3 h-3 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endif
                    @endfor
                </div>
            </div>
        </div>
    </article>
</a>