
<div>
    @foreach($account_options as $accountOption)
        <!-- Exclusive Offers -->
        <div id="accounttypes" class="bg-white dark:bg-gray-800 shadow-md rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6">
                <span class="text-amber-600">{{ $broker->name }}</span> Account Types
            </h3>
        
            <!-- IMPORTANT: Keep this wrapper class -->
            <div class="exclusive-offers overflow-x-auto rounded-xl border border-gray-100 dark:border-gray-700">
                {!! html_entity_decode($accountOption->exclusive_offers) !!}
            </div>
        </div>


        <!-- Trading Features Section -->
        <div class="py-8" id="featuresconditions">
            <div class="text-center mb-10">
                <h3 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-4">Trading Features & Conditions</h3>
                <p class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">Explore {{ $broker->name }}'s comprehensive trading ecosystem</p>
            </div>
        
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Trading Instruments -->
                <div class="group bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h4 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Max Leverage</h4>
                    </div>
                    <div class="prose dark:prose-invert max-w-none">
                        @if(strip_tags($accountOption->trading_instruments))
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ strip_tags($accountOption->max_leverage) }}</p>
                        @else
                            <div class="text-center py-4">
                                <div class="text-gray-400 dark:text-gray-500 text-3xl mb-2">📊</div>
                                <p class="text-gray-500 dark:text-gray-400">Information coming soon</p>
                            </div>
                        @endif
                    </div>
                </div>
        
                <!-- Risk Management Tools -->
                <div class="group bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-xl bg-red-100 dark:bg-red-900/30 flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <h4 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Risk Management</h4>
                    </div>
                    <div class="prose dark:prose-invert max-w-none">
                        @if(strip_tags($accountOption->risk_management_tools))
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ strip_tags($accountOption->risk_management_tools) }}</p>
                        @else
                            <div class="text-center py-4">
                                <div class="text-gray-400 dark:text-gray-500 text-3xl mb-2">🛡️</div>
                                <p class="text-gray-500 dark:text-gray-400">Information coming soon</p>
                            </div>
                        @endif
                    </div>
                </div>
        
                <!-- Special Conditions -->
                <div class="group bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <h4 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Special Conditions</h4>
                    </div>
                    <div class="prose dark:prose-invert max-w-none">
                        @if(strip_tags($accountOption->special_conditions))
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ strip_tags($accountOption->special_conditions) }}</p>
                        @else
                            <div class="text-center py-4">
                                <div class="text-gray-400 dark:text-gray-500 text-3xl mb-2">⭐</div>
                                <p class="text-gray-500 dark:text-gray-400">Information coming soon</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
