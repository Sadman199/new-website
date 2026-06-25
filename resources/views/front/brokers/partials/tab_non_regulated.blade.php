 @php
    use Carbon\Carbon;
    use Illuminate\Support\Str;
@endphp
 <!-- Non-Regulated Brokers -->
<div class="tab-content hidden bg-white rounded-lg shadow-xs border border-gray-200 p-5 h-full" id="non_regulated">
    <div class="flex flex-col h-full">
        <div class="flex justify-between items-center mb-8 border-b pb-4">
            <div class="flex items-center space-x-4">
                <i class="fas fa-skull text-red-600 text-xl"></i>
                <span class="text-lg font-semibold text-red-700">⚠️ Warning: High-Risk Broker</span>
            </div>
            <div class="bg-red-50 px-3 py-1 rounded-full">
                <span class="text-sm font-medium text-red-600">{{ \Carbon\Carbon::now()->format('M d, Y') }}</span>
            </div>
        </div>


        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 flex-grow">
            @forelse($non_regulatedBrokers as $broker)
                <div class="bg-gray-50 p-5 rounded-xl border border-gray-100 hover:border-yellow-200 transition-all hover:shadow-sm group">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-medium text-gray-800">{{ $broker->name }}</h3>
                        <span class="bg-yellow-100 text-yellow-800 text-xs px-2.5 py-1 rounded-full flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3 .921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            {{ $broker->rating }}/5
                        </span>
                    </div>
                    <p class="text-gray-600 text-xs mb-3 flex-grow">{{ Str::limit($broker->title ?? 'No description provided', 40) }}</p>
                    <div class="flex justify-between items-center text-xs text-gray-500 mt-auto">
                        <span>Since {{ $broker->year_founded ?? 'N/A' }}</span>
                        <a href="{{ route('broker_detail', ['slug' => $broker->slug]) }}" class="text-yellow-500 hover:text-yellow-700 font-medium">Details →</a>
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