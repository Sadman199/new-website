
<div id="support" class="p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
        <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
        </svg>
        Support
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-gray-50 rounded-lg p-4">
            <h4 class="text-sm font-medium text-gray-500 mb-3">{{ $broker1->name }}</h4>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-gray-400">Support Languages</p>
                    <p class="text-sm text-gray-800 mt-1">{{ strip_tags($broker1->languages) }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Support Channels</p>
                    <p class="text-sm text-gray-800 mt-1">{{ strip_tags($broker1->customer_support) }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Account Manager</p>
                    <p class="text-sm font-medium mt-1">
                        @if($broker1->account_managers)
                            <span class="text-green-600">Available</span>
                        @else
                            <span class="text-red-600">Not Available</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 rounded-lg p-4">
            <h4 class="text-sm font-medium text-gray-500 mb-3">{{ $broker2->name }}</h4>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-gray-400">Support Languages</p>
                    <p class="text-sm text-gray-800 mt-1">{{ strip_tags($broker2->languages) }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Support Channels</p>
                    <p class="text-sm text-gray-800 mt-1">{{ strip_tags($broker2->customer_support) }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Account Manager</p>
                    <p class="text-sm font-medium mt-1">
                        @if($broker2->account_managers)
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
