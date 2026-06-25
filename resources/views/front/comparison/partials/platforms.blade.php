<div id="platforms" class="p-6 border-b border-gray-200 bg-gray-50">
    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
        <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
        </svg>
        Platforms & Tools
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-4">
            <div>
                <h4 class="text-sm font-medium text-gray-500 mb-2">{{ $broker1->name }}</h4>
                <p class="text-sm text-gray-800">
                    {{ implode(', ', is_array($p = json_decode(strip_tags($broker1->platforms), true)) ? $p : [strip_tags($broker1->platforms ?? 'Not specified')]) }}
                </p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white p-3 rounded-lg border border-gray-200">
                    <p class="text-xs text-gray-500 mb-1">Mobile Trading</p>
                    <p class="text-sm font-medium">
                        @if($broker1->mobile_trading)
                            <span class="text-green-600">Available</span>
                        @else
                            <span class="text-red-600">Not Available</span>
                        @endif
                    </p>
                </div>
                <div class="bg-white p-3 rounded-lg border border-gray-200">
                    <p class="text-xs text-gray-500 mb-1">Web Trading</p>
                    <p class="text-sm font-medium">
                        @if($broker1->web_trader)
                            <span class="text-green-600">Available</span>
                        @else
                            <span class="text-red-600">Not Available</span>
                        @endif
                    </p>
                </div>
                <div class="bg-white p-3 rounded-lg border border-gray-200">
                    <p class="text-xs text-gray-500 mb-1">VPS Hosting</p>
                    <p class="text-sm font-medium">
                        @if($broker1->vps_hosting)
                            <span class="text-green-600">Available</span>
                        @else
                            <span class="text-red-600">Not Available</span>
                        @endif
                    </p>
                </div>
                <div class="bg-white p-3 rounded-lg border border-gray-200">
                    <p class="text-xs text-gray-500 mb-1">Social Trading</p>
                    <p class="text-sm font-medium">
                        @if($broker1->social_trading)
                            <span class="text-green-600">Available</span>
                        @else
                            <span class="text-red-600">Not Available</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
        <div class="space-y-4">
            <div>
                <h4 class="text-sm font-medium text-gray-500 mb-2">{{ $broker2->name }}</h4>
                <p class="text-sm text-gray-800">
                    {{ implode(', ', is_array($p = json_decode(strip_tags($broker2->platforms), true)) ? $p : [strip_tags($broker2->platforms ?? 'Not specified')]) }}
                </p>

            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white p-3 rounded-lg border border-gray-200">
                    <p class="text-xs text-gray-500 mb-1">Mobile Trading</p>
                    <p class="text-sm font-medium">
                        @if($broker2->mobile_trading)
                            <span class="text-green-600">Available</span>
                        @else
                            <span class="text-red-600">Not Available</span>
                        @endif
                    </p>
                </div>
                <div class="bg-white p-3 rounded-lg border border-gray-200">
                    <p class="text-xs text-gray-500 mb-1">Web Trading</p>
                    <p class="text-sm font-medium">
                        @if($broker2->web_trader)
                            <span class="text-green-600">Available</span>
                        @else
                            <span class="text-red-600">Not Available</span>
                        @endif
                    </p>
                </div>
                <div class="bg-white p-3 rounded-lg border border-gray-200">
                    <p class="text-xs text-gray-500 mb-1">VPS Hosting</p>
                    <p class="text-sm font-medium">
                        @if($broker2->vps_hosting)
                            <span class="text-green-600">Available</span>
                        @else
                            <span class="text-red-600">Not Available</span>
                        @endif
                    </p>
                </div>
                <div class="bg-white p-3 rounded-lg border border-gray-200">
                    <p class="text-xs text-gray-500 mb-1">Social Trading</p>
                    <p class="text-sm font-medium">
                        @if($broker2->social_trading)
                            <span class="text-green-600">Available</span>
                        @else
                            <span class="text-red-600">Not Available</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
