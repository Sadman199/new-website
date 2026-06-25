<div class="my-12" id="broker-features">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Row 1 -->
        <div class="lg:col-span-12 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 shadow-sm border border-blue-100">
            <div class="flex items-start">
                <div class="bg-blue-100 p-2 rounded-lg mr-4 flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Broker Summary</h3>
                    <p class="text-gray-700 leading-relaxed">{!! $broker->short_description !!}</p>
                </div>
            </div>
        </div>

        <!-- Row 2 -->
        <div class="lg:col-span-12 bg-white rounded-xl p-5 shadow-sm border border-gray-200">
            <div class="flex items-center mb-4">
                <div class="bg-green-100 p-2 rounded-lg mr-3 flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Safety & Regulation</h3>
            </div>

            <div class="grid grid-cols-2 gap-3">
                @php
                    $capitalization = strip_tags($broker->capitalization ?? '—', '<a><b><strong><i><u><br>');
                    $insurance = strip_tags($broker->insurance ?? '—', '<a><b><strong><i><u><br>');
                    $licenses = strip_tags($broker->regulatory_licenses ?? '—', '<a><b><strong><i><u><br><ul><li>');
                    $jurisdictions = strip_tags($broker->regulated_jurisdictions ?? '—', '<a><b><strong><i><u><br><ul><li>');
                @endphp
            
                <div class="bg-gray-50 p-3 rounded-lg">
                    <p class="text-xs font-medium text-gray-500 mb-1">Capitalization</p>
                    <p class="text-sm font-semibold text-gray-800">
                        {!! $capitalization !!}
                    </p>
                </div>
            
                <div class="bg-gray-50 p-3 rounded-lg">
                    <p class="text-xs font-medium text-gray-500 mb-1">Insurance</p>
                    <p class="text-sm font-semibold text-gray-800">
                        {!! $insurance !!}
                    </p>
                </div>
            
                <div class="bg-gray-50 p-3 rounded-lg">
                    <p class="text-xs font-medium text-gray-500 mb-1">Licenses</p>
                    <p class="text-sm font-semibold text-gray-800">
                        {!! $licenses !!}
                    </p>
                </div>
            
                <div class="bg-gray-50 p-3 rounded-lg">
                    <p class="text-xs font-medium text-gray-500 mb-1">Jurisdictions</p>
                    <p class="text-sm font-semibold text-gray-800">
                        {!! $jurisdictions !!}
                    </p>
                </div>
            
            </div>
        </div>

        <div class="lg:col-span-6 bg-white rounded-xl p-5 shadow-sm border border-gray-200">
            <div class="flex items-center mb-4">
                <div class="bg-yellow-100 p-2 rounded-lg mr-3 flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Trading Tools</h3>
            </div>

            <div class="grid grid-cols-2 gap-3 mb-4">
                <div class="text-center p-2 bg-gray-50 rounded-lg">
                    <div class="mx-auto w-8 h-8 rounded-full flex items-center justify-center mb-1 text-sm {{ $broker->social_trading ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                        {{ $broker->social_trading ? '✓' : '✗' }}
                    </div>
                    <p class="text-xs font-medium text-gray-500">Social Trading</p>
                </div>
                
                <div class="text-center p-2 bg-gray-50 rounded-lg">
                    <div class="mx-auto w-8 h-8 rounded-full flex items-center justify-center mb-1 text-sm {{ $broker->economic_calendar ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                        {{ $broker->economic_calendar ? '✓' : '✗' }}
                    </div>
                    <p class="text-xs font-medium text-gray-500">Econ Calendar</p>
                </div>
                
                <div class="text-center p-2 bg-gray-50 rounded-lg">
                    <div class="mx-auto w-8 h-8 rounded-full flex items-center justify-center mb-1 text-sm {{ $broker->account_managers ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                        {{ $broker->account_managers ? '✓' : '✗' }}
                    </div>
                    <p class="text-xs font-medium text-gray-500">Account Managers</p>
                </div>
                
                <div class="text-center p-2 bg-gray-50 rounded-lg">
                    <div class="mx-auto w-8 h-8 rounded-full flex items-center justify-center mb-1 bg-blue-100 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-xs font-medium text-gray-500">Payments</p>
                </div>
            </div>

            <div class="pt-3 border-t border-gray-200">
                <p class="text-center text-sm font-medium text-gray-700">{!! strip_tags($broker->payment_methods ?? '—') !!}</p>
            </div>
        </div>
        <div class="lg:col-span-6 bg-white rounded-xl p-5 shadow-sm border border-gray-200">
            <div class="flex items-center mb-4">
                <div class="bg-purple-100 p-2 rounded-lg mr-3 flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Trading Platforms</h3>
            </div>

            <div class="space-y-3">
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-xs font-medium text-gray-500">Mobile Trading</p>
                        <p class="text-sm font-semibold text-gray-800">{!! $broker->mobile_trading ?? '—' !!}</p>
                    </div>
                    <div class="text-green-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>

                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-xs font-medium text-gray-500">Web Trader</p>
                        <p class="text-sm font-semibold text-gray-800">{!! $broker->web_trader ?? '—' !!}</p>
                    </div>
                    <div class="text-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
