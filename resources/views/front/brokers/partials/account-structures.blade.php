<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-12">
    <!-- Header -->
    <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
        <h2 class="text-2xl font-bold text-gray-800">{{ $broker->name }} Account Structure</h2>
        <p class="text-gray-600 mt-1">Compare account types and features</p>
    </div>

    <!-- Account Options -->
    <div class="divide-y divide-gray-100">
        @foreach($account_options as $accountOption)
        <div class="p-6">
            <!-- Row 1: Account Type + Badges -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
                <div class="flex items-center gap-2">
                    <!-- Account Type -->
                    <span class="border border-yellow-500 text-yellow-600 px-4 py-1.5 rounded-lg font-semibold text-sm bg-yellow-50">
                        {{ $accountOption->account_type }}
                    </span>
                
                    <!-- Currency -->
                    <span class="border border-gray-300 text-gray-700 px-3 py-1 rounded-full text-xs font-medium bg-gray-50">
                        {{ $accountOption->account_currency }}
                    </span>
                
                </div>
                
                <div class="flex flex-wrap gap-2">
                    @if($accountOption->is_demo_available)
                    <span class="text-xs bg-green-100 text-green-700 px-2.5 py-1 rounded-full">Demo</span>
                    @endif
                    @if($accountOption->swap_free)
                    <span class="text-xs bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-full">Swap Free</span>
                    @endif
                    @if($accountOption->bonus_eligibility)
                    <span class="text-xs bg-amber-100 text-amber-700 px-2.5 py-1 rounded-full">Bonus</span>
                    @endif
                </div>
            </div>

            <!-- Row 2: Key Metrics -->
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gray-50 rounded-lg p-3">
                    <div class="text-xs text-gray-500 mb-0.5">Commission</div>
                    <div class="text-base font-semibold text-gray-900">{{ $accountOption->commission ?? 'None' }}</div>
                </div>
                
                <div class="bg-gray-50 rounded-lg p-3">
                    <div class="text-xs text-gray-500 mb-0.5">Trade Size</div>
                    <div class="text-base font-semibold text-gray-900">{{ $accountOption->min_trade_size }} – {{ $accountOption->max_trade_size }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>