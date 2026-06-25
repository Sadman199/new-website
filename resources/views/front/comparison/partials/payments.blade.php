
<div id="payments" class="p-6 border-b border-gray-200">
    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
        <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        Payments
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-gray-50 rounded-lg p-4">
            <h4 class="text-sm font-medium text-gray-500 mb-3">{{ $broker1->name }}</h4>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-gray-400">Deposit Methods</p>
                    <p class="text-sm text-gray-800 mt-1">{{ strip_tags($broker1->deposit_methods) }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Withdrawal Methods</p>
                    <p class="text-sm text-gray-800 mt-1">{{ strip_tags($broker1->withdrawal_method) }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Withdrawal Fee</p>
                    <p class="text-sm text-gray-800 mt-1">{{ $broker1->withdrawal_fee ?? 'None' }}</p>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 rounded-lg p-4">
            <h4 class="text-sm font-medium text-gray-500 mb-3">{{ $broker2->name }}</h4>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-gray-400">Deposit Methods</p>
                    <p class="text-sm text-gray-800 mt-1">{{ strip_tags($broker2->deposit_methods) }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Withdrawal Methods</p>
                    <p class="text-sm text-gray-800 mt-1">{{ strip_tags($broker2->withdrawal_method) }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Withdrawal Fee</p>
                    <p class="text-sm text-gray-800 mt-1">{{ $broker2->withdrawal_fee ?? 'None' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
