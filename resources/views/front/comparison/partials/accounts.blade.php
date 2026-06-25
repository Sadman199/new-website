
<div id="accounts" class="p-6 border-b border-gray-200">
    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
        <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
        </svg>
        Account Types
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Broker 1 -->
        <div class="bg-gray-50 rounded-lg p-4">
            <h4 class="text-sm font-medium text-gray-500 mb-3">{{ $broker1->name }}</h4>
            <div class="flex flex-wrap gap-2">
                @php
                    $accounts1 = is_array($broker1->account_types)
                        ? $broker1->account_types
                        : json_decode($broker1->account_types, true);
                @endphp
    
                @if(!empty($accounts1))
                    @foreach($accounts1 as $account)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $account }}
                        </span>
                    @endforeach
                @else
                    <p class="text-sm text-gray-500">No account types specified</p>
                @endif
            </div>
        </div>
    
        <!-- Broker 2 -->
        <div class="bg-gray-50 rounded-lg p-4">
            <h4 class="text-sm font-medium text-gray-500 mb-3">{{ $broker2->name }}</h4>
            <div class="flex flex-wrap gap-2">
                @php
                    $accounts2 = is_array($broker2->account_types)
                        ? $broker2->account_types
                        : json_decode($broker2->account_types, true);
                @endphp
    
                @if(!empty($accounts2))
                    @foreach($accounts2 as $account)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $account }}
                        </span>
                    @endforeach
                @else
                    <p class="text-sm text-gray-500">No account types specified</p>
                @endif
            </div>
        </div>
    </div>

</div>
