<section class="py-8 lg:py-12 bg-gray-50">
    <div class="container px-4 mx-auto max-w-7xl">
        <div class="mb-8 lg:mb-12">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Trading Promotions & Offers</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            <!-- Deposit Boost -->
            <div class="bg-white rounded-xl border border-gray-200 p-5 hover:border-blue-300 transition-all hover:shadow-md">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M6 14h6m-6 4h12M5 4h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Deposit Bonus</h3>
                        <p class="text-xs text-gray-500">Up to 50% extra</p>
                    </div>
                </div>
                <div class="flex justify-between items-center border-t border-gray-100 pt-3 mt-1">
                    <span class="text-xs font-medium text-blue-700 bg-blue-50 px-2 py-1 rounded">Capital boost</span>
                    
                    <a href="{{ route('bonuses.type','deposit-bonuses') }}"
                       class="text-sm font-medium text-gray-600 hover:text-blue-600 transition">
                       Details →
                    </a>
                </div>
            </div>

            <!-- Risk-Free Start -->
            <div class="bg-white rounded-xl border border-gray-200 p-5 hover:border-green-300 transition-all hover:shadow-md">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v4a2 2 0 002 2h10a2 2 0 002-2v-4"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">No Deposit</h3>
                        <p class="text-xs text-gray-500">Free trading credit</p>
                    </div>
                </div>
                <div class="flex justify-between items-center border-t border-gray-100 pt-3 mt-1">
                    <span class="text-xs font-medium text-green-700 bg-green-50 px-2 py-1 rounded">Zero deposit</span>
                    <a href="{{ route('bonuses.type','no-deposit-bonuses') }}" class="text-sm font-medium text-gray-600 hover:text-green-600 transition">Details →</a>
                </div>
            </div>

            <!-- Live Competition -->
            <div class="bg-white rounded-xl border border-gray-200 p-5 hover:border-red-300 transition-all hover:shadow-md">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Live Contest</h3>
                        <p class="text-xs text-gray-500">Prize pools</p>
                    </div>
                </div>
                <div class="flex justify-between items-center border-t border-gray-100 pt-3 mt-1">
                    <span class="text-xs font-medium text-red-700 bg-red-50 px-2 py-1 rounded">Real competition</span>
                    <a href="{{ route('bonuses.type','live-contests') }}" class="text-sm font-medium text-gray-600 hover:text-red-600 transition">Details →</a>
                </div>
            </div>

            <!-- Demo Contest -->
            <div class="bg-white rounded-xl border border-gray-200 p-5 hover:border-amber-300 transition-all hover:shadow-md">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Demo Contest</h3>
                        <p class="text-xs text-gray-500">Practice with rewards</p>
                    </div>
                </div>
                <div class="flex justify-between items-center border-t border-gray-100 pt-3 mt-1">
                    <span class="text-xs font-medium text-amber-700 bg-amber-50 px-2 py-1 rounded">Virtual funds</span>
                    <a href="{{ route('bonuses.type','demo-contests') }}" class="text-sm font-medium text-gray-600 hover:text-amber-600 transition">Details →</a>
                </div>
            </div>

            <!-- Cashback -->
            <div class="bg-white rounded-xl border border-gray-200 p-5 hover:border-purple-300 transition-all hover:shadow-md">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M6 14h6m-6 4h12M5 4h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Cashback Rebate</h3>
                        <p class="text-xs text-gray-500">Up to 15% weekly</p>
                    </div>
                </div>
                <div class="flex justify-between items-center border-t border-gray-100 pt-3 mt-1">
                    <span class="text-xs font-medium text-purple-700 bg-purple-50 px-2 py-1 rounded">Loss recovery</span>
                    <a href="{{ route('bonuses.type','cashback-rebates') }}" class="text-sm font-medium text-gray-600 hover:text-purple-600 transition">Details →</a>
                </div>
            </div>

            <!-- Crypto Bonus -->
            <div class="bg-white rounded-xl border border-gray-200 p-5 hover:border-orange-300 transition-all hover:shadow-md">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Crypto Bonus</h3>
                        <p class="text-xs text-gray-500">+10% on crypto deposits</p>
                    </div>
                </div>
                <div class="flex justify-between items-center border-t border-gray-100 pt-3 mt-1">
                    <span class="text-xs font-medium text-orange-700 bg-orange-50 px-2 py-1 rounded">Digital assets</span>
                    <a href="{{ route('bonuses.type','crypto-bonuses') }}" class="text-sm font-medium text-gray-600 hover:text-orange-600 transition">Details →</a>
                </div>
            </div>
        </div>
    </div>
</section>