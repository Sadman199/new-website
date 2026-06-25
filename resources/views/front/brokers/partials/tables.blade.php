<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-5 border-b border-gray-200">
        <h2 class="text-2xl font-bold text-gray-800">{{ $broker->name }} Overview</h2>
        <p class="text-gray-600 mt-1">Comprehensive broker information and trading capabilities</p>
    </div>

    <!-- Main Content Grid -->
    <div class="p-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- Left Column - Basic Information -->
            <div class="space-y-6">
                <!-- Languages & Region -->
                <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-lg">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-1">Languages & Region</h3>
                           <p class="text-gray-600 text-sm">{{ strip_tags($broker->languages ?? '—') }}</p>
                           <p class="text-gray-600 text-sm mt-1">{{ strip_tags($broker->country ?? '—') }}</p>

                    </div>
                </div>

                <!-- Pricing & Fees -->
                <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-lg">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-1">Pricing & Fees</h3>
                        <p class="text-gray-600 text-sm">{{ strip_tags($broker->pricing ?? '—') }}</p>
                    </div>
                </div>

                <!-- Payment Methods -->
                <div class="space-y-4">
                    <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-1">Deposit Methods</h3>
                             <p class="text-gray-600 text-sm">{{ strip_tags($broker->deposit_methods ?? '—') }}</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-1">Withdrawal Methods</h3>
                            <p class="text-gray-600 text-sm">{{ strip_tags($broker->withdrawal_method ?? '—') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Trading & Services -->
            <div class="space-y-6">
                <!-- Trading Platforms -->
                <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-lg">
                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-1">Trading Platforms</h3>
                        <p class="text-gray-600 text-sm">
                            @php
                                $platforms = is_array($p = json_decode($broker->platforms, true)) ? implode(', ', $p) : strip_tags($broker->platforms);
                            @endphp
                            {{ $platforms }}
                        </p>
                    </div>
                </div>

                <!-- Leverage -->
                <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-lg">
                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-1">Leverage</h3>
                        <p class="text-gray-600 text-sm">{{ strip_tags($broker->leverage) ?? '—' }}</p>
                    </div>
                </div>

                <!-- Account & Services -->
                <div class="grid grid-cols-2 gap-4">
                    <!-- Account Types -->
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <h3 class="font-semibold text-gray-800 mb-2 text-sm">Account Types</h3>
                        <p class="text-gray-600 text-sm">
                            {{ 
                                is_array($broker->account_types) 
                                    ? implode(', ', $broker->account_types) 
                                    : (is_string($broker->account_types) 
                                        ? implode(', ', json_decode($broker->account_types, true)) 
                                        : '—') 
                            }}
                        </p>
                    </div>

                    <!-- Customer Support -->
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <h3 class="font-semibold text-gray-800 mb-2 text-sm">Customer Support</h3>
                        <p class="text-gray-600 text-sm">{{ strip_tags($broker->customer_support) ?? '—' }}</p>
                    </div>

                    <!-- Security Features -->
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <h3 class="font-semibold text-gray-800 mb-2 text-sm">Fund Security</h3>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $broker->segregation_of_funds ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $broker->segregation_of_funds ? 'Segregated Funds' : 'No Segregation' }}
                            </span>
                        </div>
                    </div>

                    <!-- VPS Hosting -->
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <h3 class="font-semibold text-gray-800 mb-2 text-sm">VPS Hosting</h3>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $broker->vps_hosting ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $broker->vps_hosting ? 'Available' : 'Not Available' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>