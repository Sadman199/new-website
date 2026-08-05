@props(['broker'])

<div class="bg-gray-800 border border-yellow-500 rounded-lg p-4 hover:shadow-yellow-500/10 transition">
    <div class="flex flex-col md:flex-row items-center space-x-0 md:space-x-4 h-full">
        
        <!-- Broker Logo -->
        @if ($broker->logo)
            <div class="flex-shrink-0 h-14 w-14 md:h-16 md:w-16 mb-4 md:mb-0">
                <img src="{{ asset($broker->logo) }}" alt="{{ $broker->name }} Logo" class="h-full w-full object-contain rounded-lg bg-white p-1" />
            </div>
        @else
            <div class="flex-shrink-0 h-14 w-14 md:h-16 md:w-16 bg-gray-700 rounded-lg flex items-center justify-center mb-4 md:mb-0">
                <i class="fas fa-building text-gray-400 text-lg"></i>
            </div>
        @endif

        <!-- Content -->
        <div class="flex-1 text-left">
            <!-- Broker Name & Rating -->
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-white font-bold text-sm md:text-base truncate">{{ $broker->name }}</h3>
                <div class="flex items-center text-yellow-400 text-xs font-medium">
                    ⭐ {{ $broker->rating }}
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center space-x-2">
                <a href="{{ route('broker_detail', ['slug' => $broker->slug]) }}" 
                class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 text-xs font-medium rounded transition flex items-center justify-center px-3 py-1"
                title="Details">
                    Details
                </a>
                <a href="{{ $broker->url }}" target="_blank" rel="noopener noreferrer" 
                class="bg-gray-700 hover:bg-gray-600 text-white text-xs font-medium rounded transition flex items-center justify-center px-3 py-1"
                title="Website">
                 Trade
                </a>
            </div>
        </div>
    </div>
</div>
