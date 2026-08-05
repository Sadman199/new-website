 <div class="bg-white rounded-lg shadow-xs border border-gray-200 p-3 h-full">
                <nav class="flex flex-col space-y-1 h-full">
                    <button 
                        class="tab-button px-3 py-2.5 text-left rounded-md bg-gray-100 text-gray-700 font-medium border-l-3 border-yellow-500 transition-colors group flex items-center active-tab"
                        data-tab="top_rated"
                    >
                        <svg class="w-4 h-4 mr-3 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                        <span class="text-sm text-yellow-600">Top Regulated Rated</span>
                    </button>

                     <button 
                        class="tab-button px-3 py-2.5 text-left rounded-md hover:bg-gray-50 text-gray-700 font-medium border-l-3 border-transparent hover:border-blue-500 transition-colors group flex items-center"
                        data-tab="non_regulated"
                    >
                        <!-- Font Awesome Skeleton Icon -->
                        <i class="fas fa-skull text-gray-400 mr-3 text-sm group-hover:text-blue-500 animate-pulse"></i>

                        <span class="text-sm group-hover:text-blue-600">No Regulation</span>
                    </button>



                    <button 
                        class="tab-button px-3 py-2.5 text-left rounded-md hover:bg-gray-50 text-gray-700 font-medium border-l-3 border-transparent hover:border-purple-500 transition-colors group flex items-center"
                        data-tab="top_month"
                    >
                        <svg class="w-4 h-4 mr-3 text-gray-500 group-hover:text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-sm group-hover:text-purple-600">Monthly Top</span>
                    </button>
                    <button 
                        class="tab-button px-3 py-2.5 text-left rounded-md hover:bg-gray-50 text-gray-700 font-medium border-l-3 border-transparent hover:border-green-500 transition-colors group flex items-center"
                        data-tab="demo_available"
                    >
                        <svg class="w-4 h-4 mr-3 text-gray-500 group-hover:text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="text-sm group-hover:text-green-600">Demo Accounts</span>
                    </button>
                    <button 
                        class="tab-button px-3 py-2.5 text-left rounded-md hover:bg-gray-50 text-gray-700 font-medium border-l-3 border-transparent hover:border-cyan-500 transition-colors group flex items-center"
                        data-tab="low_deposit"
                    >
                        <svg class="w-4 h-4 mr-3 text-gray-500 group-hover:text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm group-hover:text-cyan-600">Low Deposit</span>
                    </button>
                </nav>
            </div>