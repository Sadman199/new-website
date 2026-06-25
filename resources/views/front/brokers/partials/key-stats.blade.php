<div class="bg-white rounded-xl shadow-lg border border-gray-100 my-8" id="key-stats">
    <div class="p-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-900">Key Broker Details</h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Key Stats - Compact Design -->
            <div class="lg:col-span-3">
                <div class="bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl p-5 border border-gray-200 h-full flex flex-col">
                    <h3 class="text-base font-bold text-gray-800 mb-4 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Quick Facts
                    </h3>
                    
                    <div class="space-y-4 flex-1">
                        <div class="flex items-center pb-3 border-b border-gray-200">
                            <div class="w-6 h-6 bg-blue-100 rounded flex items-center justify-center mr-2">
                                <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Regulation</p>
                                <p class="text-sm font-semibold text-gray-900">{{ is_array($r = json_decode($broker->regulation, true)) ? implode(', ', $r) : '' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center pb-3 border-b border-gray-200">
                            <div class="w-6 h-6 bg-green-100 rounded flex items-center justify-center mr-2">
                                <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Country</p>
                                <p class="text-sm font-semibold text-gray-900">{{ strip_tags($broker->country) }}</p>
                            </div>
                        </div>

                        <div class="flex items-center pb-3 border-b border-gray-200">
                            <div class="w-6 h-6 bg-purple-100 rounded flex items-center justify-center mr-2">
                                <svg class="w-3 h-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Min. Deposit</p>
                                <p class="text-sm font-semibold text-gray-900">${{ strip_tags($broker->minimum_deposit) }}</p>
                            </div>
                        </div>

                        <div class="flex items-center">
                            <div class="w-6 h-6 bg-orange-100 rounded flex items-center justify-center mr-2">
                                <svg class="w-3 h-3 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Spreads</p>
                                <p class="text-sm font-semibold text-gray-900">{{ strip_tags($broker->spreads ?? '—') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

          <div class="lg:col-span-9">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 h-full">
        
                <!-- ================= PROS CARD ================= -->
                <div class="bg-gradient-to-br from-emerald-50 to-green-50 rounded-xl p-5 border border-emerald-200 h-full flex flex-col">
                
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center mr-2">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M1 21h4V9H1v12zm22-11c0-1.1-.9-2-2-2h-6.31l.95-4.57.03-.32c0-.41-.17-.79-.44-1.06L14.17 1 7.59 7.59C7.22 7.95 7 8.45 7 9v10c0 1.1.9 2 2 2h9c.83 0 1.54-.5 1.84-1.22l3.02-7.05c.09-.23.14-.47.14-.73v-2z"/>
                            </svg>
                        </div>
                
                        <div>
                            <h4 class="text-lg font-bold text-gray-900">Pros</h4>
                            <p class="text-xs text-emerald-600 font-medium">What we like</p>
                        </div>
                    </div>
                
                    @php
                        $prosRaw = preg_replace('/<\/?li>/', "\n", $broker->pros ?? '');
                        $prosClean = strip_tags($prosRaw, '<a><b><strong><i><u><br>');
                        $prosArray = array_filter(array_map('trim', explode("\n", $prosClean)));
                    @endphp
                
                    <ul class="space-y-2 flex-1">
                        @foreach($prosArray as $pro)
                            @if(!empty($pro))
                                <li class="flex items-start p-2 bg-white rounded border border-emerald-100 text-sm">
                
                                    <svg class="w-5 h-5 text-emerald-500 mr-2 mt-0.5 flex-shrink-0"
                                         fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                              clip-rule="evenodd"/>
                                    </svg>
                
                                    <span class="text-gray-700 leading-relaxed">
                                        {!! $pro !!}
                                    </span>
                
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
                
                
                <!-- ================= CONS CARD ================= -->
                <div class="bg-gradient-to-br from-red-50 to-pink-50 rounded-xl p-5 border border-red-200 h-full flex flex-col">
                
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center mr-2">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M15 3H6c-.83 0-1.54.5-1.84 1.22l-3.02 7.05c-.09.23-.14.47-.14.73v2c0 1.1.9 2 2 2h6.31l-.95 4.57-.03.32c0 .41.17.79.44 1.06L9.83 23l6.59-6.59c.36-.36.58-.86.58-1.41V5c0-1.1-.9-2-2-2zm4 0v12h4V3h-4z"/>
                            </svg>
                        </div>
                
                        <div>
                            <h4 class="text-lg font-bold text-gray-900">Cons</h4>
                            <p class="text-xs text-red-600 font-medium">Areas for improvement</p>
                        </div>
                    </div>
                
                    @php
                        $consRaw = preg_replace('/<\/?li>/', "\n", $broker->cons ?? '');
                        $consClean = strip_tags($consRaw, '<a><b><strong><i><u><br>');
                        $consArray = array_filter(array_map('trim', explode("\n", $consClean)));
                    @endphp
                
                    <ul class="space-y-2 flex-1">
                        @foreach($consArray as $con)
                            @if(!empty($con))
                                <li class="flex items-start p-2 bg-white rounded border border-red-100 text-sm">
                
                                    <svg class="w-5 h-5 text-red-500 mr-2 mt-0.5 flex-shrink-0"
                                         fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                              clip-rule="evenodd"/>
                                    </svg>
                
                                    <span class="text-gray-700 leading-relaxed">
                                        {!! $con !!}
                                    </span>
                
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
        
            </div>
        </div>
           
        </div>
    </div>
</div>