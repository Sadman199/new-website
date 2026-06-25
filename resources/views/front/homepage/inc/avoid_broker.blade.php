
<div class="bg-gray-50 py-12">
    <div id="broker-tabs" class="container px-4 max-w-7xl mx-auto w-full">
        <div class="mb-8">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Explore Our Curated Broker Categories</h2>
        </div>

        <div class="grid grid-cols-12 gap-6 min-h-[600px]">
            <div class="col-span-12 md:col-span-3">
                @include('front.brokers.partials.tabs')
            </div>
            <div class="col-span-12 md:col-span-9 h-full">
                @include('front.brokers.partials.tab_top_rated')
                @include('front.brokers.partials.tab_non_regulated')
                @include('front.brokers.partials.tab_top_month')
                @include('front.brokers.partials.tab_demo_available')
                @include('front.brokers.partials.tab_low_deposit')
            </div>
        </div>

        <p class="text-xs text-gray-500 max-w-3xl mx-auto mt-6">
            * We provide categorized broker listings to help you compare and choose wisely. However, the inclusion of a broker does not guarantee profitability or safety. Always verify broker credentials and understand the risks associated with leveraged trading.
        </p>
    </div>
</div>
