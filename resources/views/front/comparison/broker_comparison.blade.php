@extends('front.layout.app')
@section('title', 'Broker Comparison | Compare Forex Brokers and Find the Best Option')
@section('main_content')

    <div class="bg-white py-8 border-b border-gray-200">
            <div class="container px-4 max-w-7xl mx-auto w-full pt-20">
                <!-- Flex container for left-right layout -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                <!-- Left-aligned content -->
                <div class="mb-6 md:mb-0">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">
                    Compare Top Forex Brokers
                    </h1>
                    <!-- Compact data points -->
                    <div class="flex flex-wrap gap-4 mt-4">
                    <div class="flex items-center">
                        <span class="text-xl font-bold text-green-600 mr-2">50+</span>
                        <span class="text-gray-600 text-sm">Brokers</span>
                    </div>
                    <div class="flex items-center">
                        <span class="text-xl font-bold text-yellow-500 mr-2">20+</span>
                        <span class="text-gray-600 text-sm">Platforms</span>
                    </div>
                    <div class="flex items-center">
                        <span class="text-xl font-bold text-blue-600 mr-2">1000+</span>
                        <span class="text-gray-600 text-sm">Reviews</span>
                    </div>
                    </div>
                </div>
                
                <!-- Right-aligned breadcrumb -->
                <nav class="bg-gray-100 rounded-full px-4 py-2">
                    <ol class="inline-flex items-center space-x-2 text-sm">
                    <li class="flex items-center">
                        <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-home mr-1"></i>
                        Home
                        </a>
                    </li>
                    <li>
                        <i class="fas fa-chevron-right text-xs text-gray-400 mx-1"></i>
                    </li>
                    <li class="text-gray-700 font-medium">
                        Broker Comparison
                    </li>
                    </ol>
                </nav>
                </div>
            </div>
        </div>
            <!-- Main Content -->
            <div class="container px-4 max-w-7xl mx-auto w-full pt-12">
                <!-- Main Comparison Section -->
                <div class="w-full">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-8 mb-8 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center mb-6">
                            <i class="fas fa-exchange-alt text-yellow-500 text-2xl mr-3"></i>
                            <h2 class="text-2xl font-bold text-gray-700 dark:text-white">Compare Forex Brokers</h2>
                        </div>
                        
                        <p class="text-gray-600 dark:text-gray-300 mb-8">
                            Analyze key metrics across multiple brokers to find the perfect match for your trading strategy. 
                            Our comparison tool provides side-by-side evaluation of spreads, commissions, platforms, and more.
                            Whether you're a beginner or an experienced trader, we help you make informed decisions by comparing the most important aspects of each broker.
                        </p>
            
                        <!-- Comparison Form -->
                        <div class="container mx-auto px-4 py-10">
                <form action="{{ route('brokers.getComparison') }}" method="POST" id="compareForm" class="mb-10">
                    @csrf
                    <input type="hidden" name="broker1_id" id="compare_broker1">
                    <input type="hidden" name="broker2_id" id="compare_broker2">
            
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- Broker 1 Selector -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-chart-line mr-1 text-yellow-500"></i>
                                Select First Broker
                            </label>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg min-h-[150px] border-2 border-dashed border-gray-300 dark:border-gray-600" id="broker1-selection">
                                <div class="flex items-center justify-center h-full" id="broker1-placeholder">
                                    <span class="text-gray-500 dark:text-gray-400">Click to select first broker</span>
                                </div>
                            </div>
                            <p class="mt-1 text-sm text-red-500 hidden" id="compare_broker1_error">Please select a broker.</p>
                        </div>
            
                        <!-- Broker 2 Selector -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-chart-line mr-1 text-teal-500"></i>
                                Select Second Broker
                            </label>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg min-h-[150px] border-2 border-dashed border-gray-300 dark:border-gray-600" id="broker2-selection">
                                <div class="flex items-center justify-center h-full" id="broker2-placeholder">
                                    <span class="text-gray-500 dark:text-gray-400">Click to select second broker</span>
                                </div>
                            </div>
                            <p class="mt-1 text-sm text-red-500 hidden" id="compare_broker2_error">Please select a broker.</p>
                        </div>
                    </div>
            
                    <!-- Brokers Slider -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-4">Available Brokers</h3>
                        <div class="relative">
                            <div class="owl-carousel owl-theme" id="brokers-slider">
                                @foreach($brokers as $broker)
                                <div class="item flex-shrink-0 w-40 cursor-pointer broker-card" 
                                    data-broker-id="{{ $broker->slug }}" 
                                    data-broker-name="{{ $broker->name }}">
                                    <div class="bg-white dark:bg-gray-700 rounded-lg shadow-sm p-4 h-full border border-gray-200 dark:border-gray-600 hover:border-yellow-500 dark:hover:border-yellow-500 transition-colors">
                                        <div class="flex justify-center mb-3">
                                            <img src="{{ asset($broker->logo) }}" alt="{{ $broker->name }}" class="h-12 w-auto object-contain">
                                        </div>
                                        <h4 class="text-sm font-medium text-center text-gray-800 dark:text-white truncate">{{ $broker->name }}</h4>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
            
                    <div class="text-center">
                        <button type="submit" class="px-6 py-3 bg-yellow-500 hover:bg-yellow-600 rounded-md text-sm font-medium text-white transition-colors flex items-center justify-center mx-auto">
                            <i class="fas fa-balance-scale-left mr-2"></i>
                            Compare Brokers
                        </button>
                    </div>
                </form>
            </div>

            <!-- How to Compare Section -->
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
                <h4 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white">
                    <i class="fas fa-question-circle text-gray-700 mr-2"></i>
                    How to Compare Brokers Effectively
                </h4>
                
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            <div class="w-5 h-5 bg-yellow-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h5 class="text-sm font-medium text-gray-800 dark:text-white">Define Your Trading Needs</h5>
                            <p class="text-sm text-gray-600 dark:text-gray-300">Consider your trading style (scalping, day trading, swing trading) and preferred instruments like forex, stocks, or commodities. This will help you prioritize the features that matter the most.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            <div class="w-5 h-5 bg-yellow-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h5 class="text-sm font-medium text-gray-800 dark:text-white">Prioritize Key Features</h5>
                            <p class="text-sm text-gray-600 dark:text-gray-300">Focus on the metrics that matter most to you - regulation, costs, or platform features. Assess which aspects are most critical for your trading success.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            <div class="w-5 h-5 bg-yellow-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h5 class="text-sm font-medium text-gray-800 dark:text-white">Test With Demo Accounts</h5>
                            <p class="text-sm text-gray-600 dark:text-gray-300">Many brokers offer demo accounts to test their platforms before committing. This allows you to get hands-on experience with the tools and features they offer without any risk.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- FAQ Section -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-8 mb-8 border border-gray-200 dark:border-gray-700">
        <div class="flex items-center mb-6">
            <i class="fas fa-question-circle text-yellow-500 text-2xl mr-3"></i>
            <h2 class="text-2xl font-bold text-gray-700 dark:text-white">Frequently Asked Questions</h2>
        </div>

        <div class="space-y-4">
            <!-- FAQ Item -->
            <div class="faq-item border-b border-gray-200 dark:border-gray-700 pb-4">
                <button class="faq-question flex justify-between items-center w-full text-left font-medium text-gray-800 dark:text-white hover:text-yellow-500 dark:hover:text-yellow-400 transition-colors">
                    <span>How often is the broker comparison data updated?</span>
                    <i class="fas fa-chevron-down text-sm ml-2 transition-transform rotate-icon"></i>
                </button>
                <div class="faq-answer mt-2 text-gray-600 dark:text-gray-300 hidden">
                    <p>We update our broker data every quarter or whenever there are significant changes such as new regulations, fee adjustments, or platform enhancements. For metrics like spreads or execution speed, the last update date is displayed beside the metric.</p>
                </div>
            </div>

            <div class="faq-item border-b border-gray-200 dark:border-gray-700 pb-4">
                <button class="faq-question flex justify-between items-center w-full text-left font-medium text-gray-800 dark:text-white hover:text-yellow-500 dark:hover:text-yellow-400 transition-colors">
                    <span>Can I compare more than two brokers at once?</span>
                    <i class="fas fa-chevron-down text-sm ml-2 transition-transform rotate-icon"></i>
                </button>
                <div class="faq-answer mt-2 text-gray-600 dark:text-gray-300 hidden">
                    <p>Currently, our comparison tool allows side-by-side comparison of two brokers for better readability. However, you can perform multiple comparisons and bookmark or save your preferred results for later review.</p>
                </div>
            </div>

            <div class="faq-item border-b border-gray-200 dark:border-gray-700 pb-4">
                <button class="faq-question flex justify-between items-center w-full text-left font-medium text-gray-800 dark:text-white hover:text-yellow-500 dark:hover:text-yellow-400 transition-colors">
                    <span>Do you include all possible fees in your cost analysis?</span>
                    <i class="fas fa-chevron-down text-sm ml-2 transition-transform rotate-icon"></i>
                </button>
                <div class="faq-answer mt-2 text-gray-600 dark:text-gray-300 hidden">
                    <p>Yes, we include all known and disclosed fees such as spreads, commissions, overnight swap rates, and funding/withdrawal charges. Any hidden or special fees, such as inactivity or premium services, are also mentioned in the broker’s detailed review.</p>
                </div>
            </div>

            <div class="faq-item border-b border-gray-200 dark:border-gray-700 pb-4">
                <button class="faq-question flex justify-between items-center w-full text-left font-medium text-gray-800 dark:text-white hover:text-yellow-500 dark:hover:text-yellow-400 transition-colors">
                    <span>How do you ensure unbiased broker reviews?</span>
                    <i class="fas fa-chevron-down text-sm ml-2 transition-transform rotate-icon"></i>
                </button>
                <div class="faq-answer mt-2 text-gray-600 dark:text-gray-300 hidden">
                    <p>We follow strict editorial independence and transparency policies. While we may receive affiliate compensation, it does not affect our ratings or rankings. All reviews are data-driven and based on transparent, verifiable metrics, as well as community feedback from real traders.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        let currentSelection = 1; // 1 for first broker, 2 for second broker

        const $brokerCards = $('.broker-card');
        const $broker1Selection = $('#broker1-selection');
        const $broker2Selection = $('#broker2-selection');
        const $broker1Placeholder = $('#broker1-placeholder');
        const $broker2Placeholder = $('#broker2-placeholder');
        const $broker1Input = $('#compare_broker1');
        const $broker2Input = $('#compare_broker2');

        // Click handlers for selection areas
        $broker1Selection.on('click', function() {
            currentSelection = 1;
            highlightSelectedBroker();
        });

        $broker2Selection.on('click', function() {
            currentSelection = 2;
            highlightSelectedBroker();
        });

        // Click handlers for broker cards
        $brokerCards.on('click', function() {
            const brokerId = $(this).data('broker-id');
            const brokerName = $(this).data('broker-name');
            const brokerLogo = $(this).find('img').attr('src');

            const brokerHtml = `
                <div class="flex flex-col items-center">
                    <img src="${brokerLogo}" alt="${brokerName}" class="h-12 w-auto object-contain mb-2">
                    <span class="font-medium text-gray-800 dark:text-white">${brokerName}</span>
                </div>
            `;

            if (currentSelection === 1) {
                $broker1Placeholder.html(brokerHtml);
                $broker1Input.val(brokerId);
                $('#compare_broker1_error').addClass('hidden');
            } else {
                $broker2Placeholder.html(brokerHtml);
                $broker2Input.val(brokerId);
                $('#compare_broker2_error').addClass('hidden');
            }

            // Reset highlight
            $brokerCards.removeClass('ring-2 ring-yellow-500');
        });

        function highlightSelectedBroker() {
            $brokerCards.removeClass('ring-2 ring-yellow-500');

            if (currentSelection === 1) {
                $broker1Selection.addClass('border-yellow-500 border-2').removeClass('border-dashed');
                $broker2Selection.removeClass('border-yellow-500 border-2').addClass('border-dashed');
            } else {
                $broker2Selection.addClass('border-yellow-500 border-2').removeClass('border-dashed');
                $broker1Selection.removeClass('border-yellow-500 border-2').addClass('border-dashed');
            }
        }

        // Form validation
        $('#compareForm').on('submit', function(e) {
            let valid = true;

            if (!$broker1Input.val()) {
                $('#compare_broker1_error').removeClass('hidden');
                valid = false;
            }

            if (!$broker2Input.val()) {
                $('#compare_broker2_error').removeClass('hidden');
                valid = false;
            }

            if ($broker1Input.val() && $broker2Input.val() && $broker1Input.val() === $broker2Input.val()) {
                alert('Please select two different brokers to compare');
                valid = false;
            }

            if (!valid) {
                e.preventDefault();
            }
        });
    });
    
    
        $(document).ready(function(){
        $('#brokers-slider').owlCarousel({
            loop: true,
            margin: 20,
            nav: true,
            dots: true,
            autoplay: true,
            autoplayTimeout: 4000,
            autoplayHoverPause: true,
            smartSpeed: 600,
            responsive:{
                0:{ items: 2 },
                640:{ items: 3 },
                1024:{ items: 6 },
            },
            navText: [
                '<button class="bg-yellow-500 hover:bg-yellow-600 text-white rounded-full w-10 h-10 flex items-center justify-center shadow-md transition"><i class="fas fa-chevron-left"></i></button>',
                '<button class="bg-yellow-500 hover:bg-yellow-600 text-white rounded-full w-10 h-10 flex items-center justify-center shadow-md transition"><i class="fas fa-chevron-right"></i></button>'
            ],
            dotsEach: true,
            dotsData: false
        });
    
        // Customize dots with icons
        $('.owl-dots button').each(function(){
            $(this).html('<i class="fas fa-circle"></i>');
        });
    });
</script>

@endsection