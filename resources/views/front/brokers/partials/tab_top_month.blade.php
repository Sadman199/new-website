@php
    use Carbon\Carbon;
    use Illuminate\Support\Str;
@endphp
<!-- Top Brokers This Month -->
<div class="tab-content hidden bg-white rounded-lg shadow-xs border border-gray-200 p-5 h-full" id="top_month">
    <div class="flex justify-between items-center mb-8 border-b pb-4">
        <div class="flex items-center space-x-4">
            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            <span class="text-lg font-medium text-gray-700">Top Brokers for {{ Carbon::now()->format('F') }}</span>
        </div>
        <span class="text-sm text-gray-500">Updated weekly</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($topBrokersThisMonth as $index => $broker)
            <div class="bg-gray-50 p-5 rounded-xl border border-gray-100 hover:border-yellow-200 transition-all hover:shadow-sm group relative">
                <!-- Top Rank Badge -->
                @if($index < 3)
                    <div class="absolute -top-2 -left-2 w-8 h-8 rounded-full bg-gradient-to-r from-yellow-500 to-yellow-600 flex items-center justify-center text-white font-bold text-sm shadow-lg">
                        #{{ $index + 1 }}
                    </div>
                @endif

                <!-- Header with Logo and Broker Info -->
                <div class="flex items-start space-x-3 mb-3">
                    <!-- Broker Logo -->
                    @if($broker->logo)
                        <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-white border border-gray-200 p-1 flex items-center justify-center shadow-sm">
                            <img src="{{ asset($broker->logo) }}" alt="{{ $broker->name }}" 
                                 class="w-full h-full object-contain">
                        </div>
                    @else
                        <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-yellow-100 to-yellow-50 rounded-lg flex items-center justify-center text-yellow-600 font-bold text-lg border border-yellow-200">
                            {{ substr($broker->name, 0, 1) }}
                        </div>
                    @endif
                    
                    <!-- Broker Name and Rating -->
                    <div class="flex-grow">
                        <div class="flex justify-between items-start mb-1">
                            <h3 class="font-semibold text-gray-800 text-base group-hover:text-purple-600">{{ $broker->name }}</h3>
                            <span class="bg-yellow-100 text-yellow-800 text-xs px-2.5 py-1 rounded-full flex items-center whitespace-nowrap ml-2">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3 .921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                {{ $broker->rating }}/5
                            </span>
                        </div>
                    </div>
                </div>

               
                <!-- Footer with Details Link -->
                <div class="flex justify-between items-center text-xs text-gray-500">
                    <span class="text-gray-600">Top performer this month</span>
                    <a href="{{ route('broker_detail', ['slug' => $broker->slug]) }}" 
                       class="text-yellow-500 hover:text-yellow-700 font-medium flex items-center group-hover:translate-x-0.5 transition-transform">
                        View Details
                        <svg class="w-3 h-3 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-10">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No monthly top brokers</h3>
                <p class="mt-1 text-sm text-gray-500">Monthly rankings will be available soon.</p>
            </div>
        @endforelse
    </div>
</div>