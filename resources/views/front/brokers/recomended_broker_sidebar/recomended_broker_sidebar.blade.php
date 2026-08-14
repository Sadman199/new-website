<!-- Professional Forex Broker List with Star Ratings -->
<div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">
    <div class="p-5 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
            </svg>
           Expert-Recommended Brokers
        </h3>
    </div>
    
    <div class="p-5">
        <ul class="space-y-3">
            @foreach ($recommended_brokers as $index => $broker)
                <a href="{{ route('broker_detail', ['slug' => $broker->slug]) }}" class="block">
                    <li class="group flex items-center justify-between p-4 hover:bg-yellow-50 rounded-lg transition-colors duration-150 border border-gray-200 hover:border-gray-200">
                        <div class="flex items-center space-x-4">
                            <!-- Ranking indicator -->
                            <span class="flex items-center justify-center w-7 h-7 rounded text-xs font-medium
                                {{ $index < 3 ? 'bg-yellow-100 text-gray-800' : 'bg-gray-100 text-gray-600' }}">
                                {{ $index + 1 }}
                            </span>
                            
                            
                            <div class="relative">
                                @if ($broker->logo)
                                    <img src="{{ asset($broker->logo) }}" alt="{{ $broker->name }} Logo" class="w-12 h-12 object-contain" width="48" height="48" loading="lazy" decoding="async">
                                @else
                                    <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center border border-gray-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="flex-1">
                                <span class="font-medium text-gray-800 group-hover:text-yellow-500">{{ $broker->name }}</span>
                                <!-- Star Rating -->
                                <div class="flex items-center mt-1">
                                    @php
                                        $rating = $broker->rating;
                                        $fullStars = floor($rating);
                                        $hasHalfStar = $rating - $fullStars >= 0.5;
                                    @endphp

                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $fullStars)
                                            <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @elseif($i == $fullStars + 1 && $hasHalfStar)
                                            <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                <defs>
                                                    <linearGradient id="half-star-{{ $broker->id }}" x1="0" x2="100%" y1="0" y2="0">
                                                        <stop offset="50%" stop-color="currentColor"/>
                                                        <stop offset="50%" stop-color="#D1D5DB"/>
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
                                    <span class="ml-1 text-xs text-gray-600">{{ number_format($rating, 1) }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 group-hover:text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </li>
                </a>
            @endforeach
        </ul>
        
        <!-- Professional footer -->
        <div class="mt-5 pt-4 border-t border-gray-200 text-center">
           <p class="text-xs text-gray-500 font-normal">Regulated brokers • Expert-rated • Secure trading</p>
        </div>
    </div>
</div>