<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    <div class="flex flex-col md:flex-row gap-6">
        <!-- Main Content (col-8) -->
        <div class="w-full md:w-8/12">
            <div class="bg-white dark:bg-gray-825 rounded-lg shadow-sm border border-gray-150 dark:border-gray-700">
                <div class="p-6 space-y-8">
                    <!-- Trading Platforms Section -->
                    @php
                    $platformSlugs = [
                        'MetaTrader 4' => 'mt4',
                        'MetaTrader 5' => 'mt5',
                        'cTrader' => 'ctrader',
                        'WebTrader' => 'webtrader',
                    ];
                    @endphp
                    
                    <div>
                        <div class="flex items-center mb-3">
                            <svg class="w-5 h-5 text-gray-700 dark:text-gray-300 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                            </svg>
                            <h4 class="text-lg font-medium text-gray-800 dark:text-gray-100">Trading Platforms</h4>
                        </div>
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                            @foreach ($platformSlugs as $platform => $slug)
                                <a href="{{ route('brokers.by.platform', $slug) }}" 
                                   class="bg-gray-50 dark:bg-gray-750 hover:bg-blue-50 dark:hover:bg-blue-900/30 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 text-sm px-3 py-2 rounded-md transition-all duration-150 border border-gray-200 dark:border-gray-600 hover:border-blue-200 dark:hover:border-blue-700 flex items-center justify-center">
                                    @if($slug === 'mt4')
                                        <svg class="w-4 h-4 mr-1.5 text-blue-500" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2L2 7v10l10 5 10-5V7L12 2zm0 2.8L20 9v6l-8 4-8-4V9l8-4.2z"/>
                                        </svg>
                                    @elseif($slug === 'mt5')
                                        <svg class="w-4 h-4 mr-1.5 text-blue-500" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2L2 7v10l10 5 10-5V7L12 2zm0 2.8L20 9v6l-8 4-8-4V9l8-4.2zM12 12l-5-2.5V15l5 2.5 5-2.5V9.5L12 12z"/>
                                        </svg>
                                    @endif
                                    {{ $platform }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Regulation Section -->
                    @php
                    $regulationSlugs = [
                        'CySEC' => 'cysec',
                        'FCA' => 'fca',
                        'ASIC' => 'asic',
                        'FSCA' => 'fsca',
                        'FSA' => 'fsa',
                        'BaFin' => 'bafin',
                    ];
                    @endphp
                    
                    <div>
                        <div class="flex items-center mb-3">
                            <svg class="w-5 h-5 text-gray-700 dark:text-gray-300 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            <h4 class="text-lg font-medium text-gray-800 dark:text-gray-100">Regulation</h4>
                        </div>
                        
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2">
                            @foreach ($regulationSlugs as $regulator => $slug)
                                <a href="{{ route('brokers.by.regulation', $slug) }}" 
                                   class="bg-gray-50 dark:bg-gray-750 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm px-3 py-2 rounded-md transition-all duration-150 border border-gray-200 dark:border-gray-600 flex items-center justify-center">
                                    {{ $regulator }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- High Leverage Section -->
                    <div>
                        <div class="flex items-center mb-3">
                            <svg class="w-5 h-5 text-gray-700 dark:text-gray-300 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            <h4 class="text-lg font-medium text-gray-800 dark:text-gray-100">Leverage</h4>
                        </div>
                        
                        <div>
                            <a href="{{ route('brokers.high.leverage') }}" 
                               class="inline-flex items-center px-4 py-2 border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-750 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md text-sm font-medium transition-colors duration-150">
                                View High Leverage Brokers (1:500+)
                                <svg class="ml-1.5 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <!-- Sidebar - Relevant Static Content (col-4) -->
        <div class="w-full md:w-4/12 space-y-6">
            <!-- Top 3 Brokers This Month -->
           <div class="bg-white dark:bg-gray-825 rounded-lg shadow-sm border border-gray-150 dark:border-gray-700 p-5">
                <h3 class="flex items-center text-lg font-medium text-gray-800 dark:text-gray-100 mb-4">
                    <svg class="w-5 h-5 text-gray-700 dark:text-gray-300 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                    Top Brokers This Month
                </h3>
                <div class="space-y-4">
                    @foreach($topBrokersThisMonth as $index => $broker)
                    <div class="flex items-start">
                        <span class="@if($index == 0) bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200
                                    @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 @endif
                                    text-xs font-bold px-2 py-1 rounded mr-3 mt-0.5">
                            #{{ $index + 1 }}
                        </span>
                        <div>
                            <h4 class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $broker->name }}</h4>
                            <div class="flex items-center mt-1">
                                <div class="flex text-amber-400">
                                    {{-- Stars rendering (5 stars max) --}}
                                    @php
                                        $fullStars = floor($broker->rating);
                                        $halfStar = ($broker->rating - $fullStars) >= 0.5 ? 1 : 0;
                                        $emptyStars = 5 - $fullStars - $halfStar;
                                    @endphp
                                    @for ($i = 0; $i < $fullStars; $i++)
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endfor
                                    @if($halfStar)
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <defs>
                                                <linearGradient id="halfGradient" x1="0" y1="0" x2="100%" y2="0">
                                                    <stop offset="50%" stop-color="currentColor" />
                                                    <stop offset="50%" stop-color="transparent" />
                                                </linearGradient>
                                            </defs>
                                            <path fill="url(#halfGradient)" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endif
                                    @for ($i = 0; $i < $emptyStars; $i++)
                                        <svg class="w-3 h-3 text-gray-300 dark:text-gray-600" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endfor
                                </div>
                                <span class="text-xs text-gray-500 dark:text-gray-400 ml-1">
                                    {{ number_format($broker->rating, 1) }}  reviews
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 text-center">
        <p class="text-xs text-gray-500 max-w-3xl mx-auto">
            * Top brokers are selected based on reviews, ratings, and reliability. Trading Forex and CFDs involves risk and may not suit everyone. Past results aren’t guarantees. Always research and seek advice before trading.
        </p>
    </div>
</div>