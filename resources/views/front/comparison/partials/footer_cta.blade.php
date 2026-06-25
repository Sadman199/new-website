<div class="bg-gray-50 border-t border-gray-200 p-4 sm:p-6">
    <div class="max-w-4xl mx-auto text-center">
        <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-2">Ready to choose your broker?</h3>
        <p class="text-sm sm:text-base text-gray-600 mb-4">Select the platform that best matches your needs</p>
        <div class="flex flex-col sm:flex-row justify-center gap-3">
            <a href="{{ $broker1->open_live }}" class="bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white font-medium py-2 px-4 sm:py-2.5 sm:px-5 rounded-lg transition duration-300 flex items-center justify-center text-sm sm:text-base">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Open {{ $broker1->name }} Account
            </a>
            <a href="{{ $broker2->open_live }}" class="bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white font-medium py-2 px-4 sm:py-2.5 sm:px-5 rounded-lg transition duration-300 flex items-center justify-center text-sm sm:text-base">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Open {{ $broker2->name }} Account
            </a>
        </div>
    </div>
</div>