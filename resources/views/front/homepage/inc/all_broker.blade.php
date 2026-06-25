<section class="py-16 bg-gray-50">
    <div class="container px-4 max-w-7xl mx-auto w-full">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-8">
            <div class="mb-4 md:mb-0">
                <div class="flex items-center gap-2">
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">
                        Check Out the Latest Broker Listings
                    </h2>
                </div>
            </div>
            <a href="{{ route('all_brokers') }}"
            class="inline-flex items-center px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-800 dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-white text-sm font-medium rounded-lg transition-colors duration-300">
                View All Brokers
            </a>
        </div>
            @if ($all_brokers->count() > 0)
                <div class="space-y-4">
                    <x-broker-table-header />
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                        @foreach($all_brokers as $broker)
                            <x-broker-row :broker="$broker" />
                        @endforeach
                    </div>
                </div>
            @else
                <x-no-brokers-found />
            @endif
        <div class="mt-6 text-center">
            <p class="text-xs text-gray-500 max-w-3xl mx-auto">
                * All brokers listed are featured based on a combination of verified offerings, bonus structures, and platform trustworthiness. Trading forex and CFDs carries a high level of risk and may not be suitable for all investors. Please ensure you fully understand the risks involved before investing.
            </p>
        </div>
    </div>
</section>