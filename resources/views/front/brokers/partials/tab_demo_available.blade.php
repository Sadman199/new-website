@php
    use Carbon\Carbon;
    use Illuminate\Support\Str;
@endphp
<!-- Demo Available Brokers -->
<div class="tab-content hidden bg-white rounded-lg shadow-xs border border-gray-200 p-5 h-full" id="demo_available">
    <div class="flex justify-between items-center mb-8 border-b pb-4">
        <div class="flex items-center space-x-4">
            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            <span class="text-lg font-medium text-gray-700">Brokers with Demo Accounts</span>
        </div>
        <div class="bg-yellow-50 px-3 py-1 rounded-full">
            <span class="text-sm font-medium text-yellow-600">{{ Carbon::now()->format('M d, Y') }}</span>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($demoAvailableBrokers as $broker)
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
                        <div class="flex justify-between items-start mb-1">
                            <h3 class="font-semibold text-gray-800 text-base group-hover:text-green-600">{{ $broker->name }}</h3>
                            <span class="bg-yellow-100 text-yellow-800 text-xs px-2.5 py-1 rounded-full flex items-center whitespace-nowrap ml-2">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3 .921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                {{ $broker->rating }}/5
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Demo Account Info -->
                <div class="border border-yellow-100 rounded-lg p-3 mb-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            <span class="text-xs font-medium text-gray-500">Demo Account Available</span>
                        </div>
                        <span class="text-xs text-gray-600 bg-yellow-50 px-2 py-1 rounded">
                            {{ $broker->demo_duration ?? 'Unlimited' }}
                        </span>
                    </div>
                </div>

                <!-- Footer with Details Link -->
                <div class="flex justify-between items-center text-xs text-gray-500">
                    <span class="text-gray-600">Practice trading risk-free</span>
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
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No demo account brokers</h3>
                <p class="mt-1 text-sm text-gray-500">There are currently no brokers with demo accounts available.</p>
            </div>
        @endforelse
    </div>
</div>