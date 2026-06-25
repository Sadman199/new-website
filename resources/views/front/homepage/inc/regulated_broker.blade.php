


<!-- Regulated Broker -->
<section class="py-12 px-4 sm:px-6 lg:px-8">
    <div class="container px-4 max-w-7xl mx-auto w-full">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div class="mb-4 md:mb-0">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Trade With A Regulated Broker</h2>
            </div>
            <a href="{{ route('regulated_brokers') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg text-center transition-colors duration-300 flex items-center justify-center text-sm">
                See All <span class="ml-1">→</span>
            </a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($regulatedBrokers as $broker)
                @include("front.brokers.regulated_card.regulated_card") 
            @endforeach
        </div>
    </div>
    <p class="text-xs text-gray-500 max-w-3xl mx-auto mt-6">* All regulated brokers listed have been carefully selected based on their compliance with financial regulations, transparency, and reputation. Trading forex and CFDs involves significant risk and may not be suitable for every investor. Please ensure you understand the risks before trading.</p>
</section>


    


    