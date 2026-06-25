<section class="bg-gradient-to-br from-gray-50 to-white py-10 md:py-12" id="compare">
    <div class="container mx-auto px-4 max-w-7xl">
        <!-- Compact Header Section -->
        <div class="text-center mb-8 md:mb-10 max-w-3xl mx-auto">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3">
                How <span class="text-amber-500">{{ $broker->name }}</span> Compares
            </h2>
            <p class="text-base md:text-lg text-gray-600">
                See how <span class="font-semibold text-gray-800">{{ $broker->name }}</span> measures against other brokers.
            </p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-5">
                @foreach($compare_brokers as $compare_broker)
                    @php
                        $account = $compare_broker->accountOptions->first();
                        $is_regulated = $compare_broker->accountOptions->contains('is_regulated', 1);
                    @endphp
                    
                    <a href="{{ route('compare', [$broker->slug, $compare_broker->slug]) }}" class="block">
                        <article class="bg-white border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors duration-200">
                            <div class="flex items-center justify-between">
                                <!-- Left: Logo and Name -->
                                <div class="flex items-center space-x-4 flex-1">
                                    <!-- Logo -->
                                    <div class="w-16 h-10 flex items-center justify-center bg-white border border-gray-200 rounded-lg p-1.5">
                                        @if($compare_broker->logo)
                                            <img 
                                                class="h-full w-auto object-contain" 
                                                alt="{{ $compare_broker->name }} logo" 
                                                src="{{ asset($compare_broker->logo) }}" 
                                            />
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-amber-100 to-amber-50 rounded flex items-center justify-center text-amber-600 font-bold text-sm border border-amber-200">
                                                {{ substr($compare_broker->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Broker Name and Details -->
                                    <div class="flex flex-col">
                                        <h3 class="font-medium text-gray-900 text-sm tracking-tight">
                                            {{ $compare_broker->name }}
                                        </h3>
                                        <!-- Regulation Status -->
                                        <span class="text-xs text-gray-500 mt-0.5">
                                            {{ $is_regulated ? 'Regulated' : 'Non-Regulated' }}
                                        </span>
                                    </div>
                                </div>
            
                                <!-- Right: Rating -->
                                <div class="flex items-center space-x-2">
                                    <!-- Rating Number -->
                                    <span class="text-sm font-semibold text-gray-900 min-w-[35px] text-right">
                                        {{ number_format($compare_broker->rating, 1) }}
                                    </span>
                                    
                                    <!-- Stars -->
                                    <div class="flex items-center space-x-0.5">
                                        @php
                                            $rating = $compare_broker->rating;
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
                                                        <linearGradient id="half-star-{{ $compare_broker->id }}" x1="0" x2="100%" y1="0" y2="0">
                                                            <stop offset="50%" stop-color="currentColor"/>
                                                            <stop offset="50%" stop-color="#E5E7EB"/>
                                                        </linearGradient>
                                                    </defs>
                                                    <path fill="url(#half-star-{{ $compare_broker->id }})" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
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
                @endforeach
            </div>


        <div class="text-center mt-8">
            <a href="#" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                View All Comparisons
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
            </a>
        </div>
    </div>
</section>