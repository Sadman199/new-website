 @php
    use Carbon\Carbon;
    use Illuminate\Support\Str;
@endphp
 <!-- Top Rated Brokers -->
<div class="tab-content bg-white rounded-lg shadow-xs border border-gray-200 p-5 h-full" id="top_rated">
    <div class="flex justify-between items-center mb-6 border-b pb-3">
        <div class="flex items-center space-x-3">
            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            <span class="text-base font-medium text-gray-700">Top Regulated Brokers</span>
        </div>
        <div class="bg-yellow-50 px-2 py-1 rounded-full">
            <span class="text-xs font-medium text-yellow-600">{{ Carbon::now()->format('M j') }}</span>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($topRatedRegulatedBrokers as $broker)
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-100 hover:border-yellow-200 transition-all group">
                <!-- Header with Logo and Basic Info -->
                <div class="flex items-center space-x-3 mb-3">
                    @if($broker->logo)
                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-white border border-gray-200 p-1 flex items-center justify-center">
                            <img src="{{ asset($broker->logo) }}" alt="{{ $broker->name }}" 
                                 class="w-full h-full object-contain">
                        </div>
                    @else
                        <div class="flex-shrink-0 w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center text-yellow-600 font-bold text-sm border border-yellow-200">
                            {{ substr($broker->name, 0, 1) }}
                        </div>
                    @endif
                    
                    <div class="flex-grow min-w-0">
                        <div class="flex justify-between items-center mb-1">
                            <h3 class="font-semibold text-gray-800 text-sm truncate group-hover:text-yellow-600">{{ $broker->name }}</h3>
                            <span class="text-yellow-600 text-xs font-medium bg-yellow-50 px-1.5 py-0.5 rounded ml-2">
                                {{ $broker->rating }}/5
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Regulation Indicator -->
                <div class="flex items-center justify-between bg-green-50 border border-green-100 rounded-md p-2 mb-3">
                    <div class="flex items-center space-x-1.5">
                        <svg class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span class="text-xs font-medium text-green-700">Regulated</span>
                    </div>
                    <span class="text-xs text-green-600 bg-green-100 px-1.5 py-0.5 rounded">
                        Verified
                    </span>
                </div>

                <!-- Footer -->
                <div class="flex justify-between items-center text-xs text-gray-500">
                    <span class="text-gray-600 text-xs">Trusted broker</span>
                    <a href="{{ route('broker_detail', ['slug' => $broker->slug]) }}" 
                       class="text-yellow-500 hover:text-yellow-700 font-medium text-xs">
                        Details →
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-8">
                <svg class="mx-auto h-10 w-10 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-sm font-medium text-gray-900">No top rated brokers</h3>
                <p class="mt-1 text-xs text-gray-500">No top rated brokers available currently.</p>
            </div>
        @endforelse
    </div>
</div>