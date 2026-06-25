 <div class="bg-white rounded-lg shadow-sm hover:shadow-sm transition-shadow duration-300 overflow-hidden border border-gray-200 mb-4">
    <!-- Mobile Header - Only shown on small screens -->
    <div class="md:hidden p-3 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
        <div class="flex items-center space-x-2">
            @if ($broker->logo)
            <div class="w-10 h-8 flex-shrink-0 bg-white p-1 rounded border border-gray-200">
                <img src="{{ asset($broker->logo) }}" alt="Broker Logo" class="w-full h-full object-contain">
            </div>
            @endif
            <h4 class="font-semibold text-gray-700 text-sm">{{ Str::limit($broker->title, 20) }}</h4>
        </div>
        <span class="text-xs px-2 py-1 rounded-full {{ $broker->accountOptions->first() && $broker->accountOptions->first()->is_regulated ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
            {{ $broker->accountOptions->first() && $broker->accountOptions->first()->is_regulated ? 'Regulated' : 'Non-Regulated' }}
        </span>
    </div>
    
    <div class="grid grid-cols-12 gap-4 p-4 items-center">
        <!-- Broker Logo and Name - Hidden on mobile, shown on md and up -->
        <div class="hidden md:flex col-span-4 md:col-span-4 items-center space-x-3">
            @if ($broker->logo)
            <div class="w-16 h-12 flex-shrink-0 bg-white p-1 rounded border border-gray-200">
                <img src="{{ asset($broker->logo) }}" alt="Broker Logo" class="w-full h-full object-contain">
            </div>
            @endif
            <div>
                <h4 class="font-semibold text-gray-700 text-sm">{{ Str::limit($broker->title, 25) }}</h4>
                <span class="text-xs px-2 py-1 rounded-full {{ $broker->accountOptions->first() && $broker->accountOptions->first()->is_regulated ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $broker->accountOptions->first() && $broker->accountOptions->first()->is_regulated ? 'Regulated' : 'Non-Regulated' }}
                </span>
            </div>
        </div>
        
        <!-- Rating - Hidden on mobile, shown on md and up -->
        <div class="hidden md:flex md:col-span-2">
            <div class="w-full">
                <div class="flex items-center mb-1">
                    <div class="flex">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= floor($broker->rating))
                                <i class="fas fa-star text-yellow-400 text-xs"></i>
                            @elseif ($i - 0.5 <= $broker->rating)
                                <i class="fas fa-star-half-alt text-yellow-400 text-xs"></i>
                            @else
                                <i class="far fa-star text-gray-300 text-xs"></i>
                            @endif
                        @endfor
                    </div>
                    <span class="ml-1 text-xs font-medium text-gray-700">{{ number_format($broker->rating, 1) }}/5</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-1">
                    <div class="bg-yellow-400 h-1 rounded-full" style="width: {{ ($broker->rating / 5) * 100 }}%"></div>
                </div>
            </div>
        </div>
        
        <!-- Mobile Rating - Only shown on small screens -->
        <div class="md:hidden col-span-6 flex items-center">
            <div class="flex mr-1">
                @for ($i = 1; $i <= 5; $i++)
                    @if ($i <= floor($broker->rating))
                        <i class="fas fa-star text-yellow-400 text-xs"></i>
                    @elseif ($i - 0.5 <= $broker->rating)
                        <i class="fas fa-star-half-alt text-yellow-400 text-xs"></i>
                    @else
                        <i class="far fa-star text-gray-300 text-xs"></i>
                    @endif
                @endfor
            </div>
            <span class="text-xs font-medium text-gray-700">{{ number_format($broker->rating, 1) }}</span>
        </div>
        
        <!-- Minimum Deposit -->
        <div class="col-span-6 md:col-span-2">
            <div class="text-xs md:text-sm text-gray-500">Min. Deposit</div>
            <div class="font-bold text-blue-800 text-sm md:text-base">${{ $broker->minimum_deposit }}</div>
        </div>
        
        <!-- Action Buttons -->
        <div class="col-span-6 md:col-span-4 flex justify-end space-x-2">
            <a href="{{ route('broker_detail', ['slug' => $broker->slug]) }}" 
            class="px-2 py-1 md:px-3 md:py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium rounded-lg text-center transition-colors duration-300 flex items-center justify-center text-xs md:text-sm">
                <i class="fas fa-eye mr-1 text-xs"></i> Review
            </a>
            <a href="{!! $broker->url !!}" target="_blank" rel="noopener noreferrer" class="bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white font-medium py-1 md:py-2 px-2 md:px-4 rounded-lg text-center transition-all duration-300 shadow-md hover:shadow-lg flex items-center justify-center text-xs md:text-sm">
                <i class="fas fa-external-link-alt mr-1 md:mr-2 text-xs"></i>
                Trade Now
            </a>
        </div>
    </div>
    
    <!-- Additional Info (Tabs) -->
    <div class="border-t border-gray-200 px-4 py-3 bg-gray-50">
        <div class="flex overflow-x-auto border-b border-gray-200">
            <button class="tab-button active whitespace-nowrap px-3 py-2 text-xs md:text-sm font-medium text-gray-700 border-b-2 border-transparent" data-tab="summary-{{ $broker->id }}">
                Summary
            </button>
            <button class="tab-button whitespace-nowrap px-3 py-2 text-xs md:text-sm font-medium text-gray-500 hover:text-gray-700" data-tab="review-{{ $broker->id }}">
                Review
            </button>
            <button class="tab-button whitespace-nowrap px-3 py-2 text-xs md:text-sm font-medium text-gray-500 hover:text-gray-600" data-tab="regulation-{{ $broker->id }}">
                Regulation
            </button>
        </div>
        
        <div class="mt-2">
            <div id="summary-{{ $broker->id }}" class="tab-content active">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 text-xs md:text-sm">
                    <div>
                        <div class="text-gray-500">Leverage</div>
                        <div class="font-medium">{{ $broker->leverage }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500">Platforms</div>
                        <div class="font-medium">MT4, MT5</div>
                    </div>
                    <div>
                        <div class="text-gray-500">Assets</div>
                        <div class="font-medium">100+</div>
                    </div>
                    <div>
                        <div class="text-gray-500">Execution</div>
                        <div class="font-medium">Market</div>
                    </div>
                </div>
            </div>
            
            <div id="review-{{ $broker->id }}" class="tab-content hidden">
                <p class="text-xs md:text-sm text-gray-700">{{ Str::limit(strip_tags($broker->short_description), 200) }}</p>
                <a href="{{ route('broker_detail', ['slug' => $broker->slug]) }}" class="text-blue-600 hover:text-blue-800 text-xs md:text-sm font-medium inline-block mt-2">
                    Read Full Review →
                </a>
            </div>
            
            <div id="regulation-{{ $broker->id }}" class="tab-content hidden">
            <div class="text-xs md:text-sm text-gray-700">
                {{ is_array($regs = json_decode($broker->regulation, true)) ? implode(', ', $regs) : $broker->regulation }}
                </div>
            </div>
        </div>
    </div>
 </div>