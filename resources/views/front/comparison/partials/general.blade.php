
<div id="general" class="p-6 border-b border-gray-200">
    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
        <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
        </svg>
        General Information
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-gray-50 rounded-lg p-4">
            <h4 class="text-sm font-medium text-gray-500 mb-3">{{ $broker1->name }}</h4>
            <div class="space-y-4">
                <div>
                    <p class="text-xs text-gray-400">Regulation</p>
                   <p class="text-sm text-gray-800 mt-1">
                        {{ ($regs = $broker1->regulationList()) ? implode(', ', $regs) : 'Not specified' }}
                    </p>

                </div>
                <div>
                    <p class="text-xs text-gray-400">Founded</p>
                    <p class="text-sm text-gray-800 mt-1">{{ $broker1->year_founded ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Headquarters</p>
                    <p class="text-sm text-gray-800 mt-1">{{ $broker1->country }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Overall Rating</p>
                    <div class="flex items-center mt-1">
                        <div class="flex">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $broker1->rating)
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @endif
                            @endfor
                        </div>
                        <span class="ml-2 text-sm text-gray-700">{{ $broker1->rating }}/5</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-gray-50 rounded-lg p-4">
            <h4 class="text-sm font-medium text-gray-500 mb-3">{{ $broker2->name }}</h4>
            <div class="space-y-4">
                <div>
                    <p class="text-xs text-gray-400">Regulation</p>
                    <p class="text-sm text-gray-800 mt-1">
                        {{ ($regs = $broker2->regulationList()) ? implode(', ', $regs) : 'Not specified' }}
                    </p>
                 </div>
                <div>
                    <p class="text-xs text-gray-400">Founded</p>
                    <p class="text-sm text-gray-800 mt-1">{{ $broker2->year_founded ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Headquarters</p>
                    <p class="text-sm text-gray-800 mt-1">{{ $broker2->country }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Overall Rating</p>
                    <div class="flex items-center mt-1">
                        <div class="flex">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $broker2->rating)
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @endif
                            @endfor
                        </div>
                        <span class="ml-2 text-sm text-gray-700">{{ $broker2->rating }}/5</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
