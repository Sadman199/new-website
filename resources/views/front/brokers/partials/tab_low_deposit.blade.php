@php
    use Carbon\Carbon;
    use Illuminate\Support\Str;
@endphp
<!-- Low Deposit Brokers -->
<div class="tab-content hidden bg-white rounded-lg shadow-xs border border-gray-200 p-5 h-full" id="low_deposit">
    <div class="flex flex-col h-full">
        <div class="flex justify-between items-center mb-8 border-b pb-4">
            <div class="flex items-center space-x-4">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                <span class="text-lg font-medium text-gray-700">Low Deposit Brokers</span>
            </div>
            <span class="text-xs text-gray-500 bg-gray-50 px-2 py-1 rounded">≤ $50 minimum</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 flex-grow">
            @forelse($lowDepositBrokers as $broker)
                <div class="bg-gray-50 p-5 rounded-xl border border-gray-100 hover:border-yellow-200 transition-all hover:shadow-sm group">
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
                            <div class="flex justify-between items-start">
                                <h3 class="font-medium text-gray-800 text-sm leading-tight">{{ $broker->name }}</h3>
                                <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full flex items-center whitespace-nowrap ml-2">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3 .921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    {{ $broker->rating }}/5
                                </span>
                            </div>
                            <p class="text-gray-600 text-xs mt-1">{{ Str::limit($broker->title ?: 'No description provided', 40) }}</p>
                        </div>
                    </div>

                    <!-- Footer with Deposit Info and Details Link -->
                    <div class="flex justify-between items-center text-xs text-gray-500 mt-3 pt-3 border-t border-gray-200">
                        <span class="bg-white px-2 py-1 rounded border border-gray-200">
                            Min: {{ $broker->min_deposit ?? 'N/A' }}
                        </span>
                        <a href="{{ route('broker_detail', ['slug' => $broker->slug]) }}" 
                           class="text-yellow-500 hover:text-yellow-700 font-medium flex items-center group-hover:translate-x-0.5 transition-transform">
                            Details 
                            <svg class="w-3 h-3 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full flex items-center justify-center h-full">
                    <div class="text-center py-8">
                        <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-700">No brokers available</h3>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>