
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('meta_description', 'BrokersCourt helps you compare and find top forex brokers, read expert reviews, and grab exclusive deals on trading accounts.')">
    <meta name="keywords" content="@yield('meta_keywords', 'Forex Brokers, Forex Broker Comparison, Broker Reviews, Forex Deals, Top Forex Brokers')">
    <meta name="author" content="BrokersCourt">
    <meta name="robots" content="index, follow">

    <title>@yield('title', 'BrokersCourt - Compare and Find Top Forex Brokers, Reviews, and Deals')</title>

    <!-- Google Tag Manager -->
    <script async src="https://www.googletagmanager.com/gtm.js?id=GTM-W3MTNWPW"></script>
    <!-- End Google Tag Manager -->

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'BrokersCourt - Compare and Find Top Forex Brokers, Reviews, and Deals')">
    <meta property="og:description" content="@yield('meta_description', 'BrokersCourt helps you compare and find top forex brokers, read expert reviews, and grab exclusive deals on trading accounts.')">
    <meta property="og:image" content="{{ asset('uploads/'.$global_setting_data->favicon) }}">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:creator" content="@BrokersCourt">
    <meta name="twitter:title" content="@yield('title', 'BrokersCourt - Compare and Find Top Forex Brokers, Reviews, and Deals')">
    <meta name="twitter:description" content="@yield('meta_description', 'BrokersCourt helps you compare and find top forex brokers, read expert reviews, and grab exclusive deals on trading accounts.')">
    <meta name="twitter:image" content="{{ asset('uploads/'.$global_setting_data->favicon) }}">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('uploads/'.$global_setting_data->favicon) }}">

    <!-- Optimized CSS -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <!-- Asynchronous JS (Optional for non-blocking) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" async></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.x.x/dist/alpine.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/smooth-scrollbar@8.7.4/dist/smooth-scrollbar.js"></script>
    <link rel="dns-prefetch" href="https://brokerscourt.com/">
    <link rel="preconnect" href="https://brokerscourt.com/">


    <!-- Additional Includes -->
    @include('front.layout.styles')
    @include('front.layout.scripts')
    @include('front.layout.responsive')
</head>


<body>
    
     <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-BTKXSHQ638"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
    
      gtag('config', 'G-BTKXSHQ638');
    </script>


    @include('front.layout.nav')

    @yield('main_content')

    @include('front.layout.scripts_footer')
    
    
    

   <!-- Scroll to Top Button -->
    <button id="scrollToTopBtn" class="fixed bottom-6 right-6 bg-yellow-500 text-white rounded-full shadow-lg hover:bg-yellow-600 focus:outline-none transition-all duration-300 w-16 h-16 flex items-center justify-center" style="opacity: 0; pointer-events: none;">
        <span id="scrollPercentage" class="absolute text-sm font-medium">0%</span>
        <svg class="absolute w-full h-full top-0 left-0 -rotate-90" viewBox="0 0 100 100">
            <circle cx="50" cy="50" r="45" stroke="" stroke-width="8" fill="none" />
            <circle id="scrollProgress" cx="50" cy="50" r="45" stroke="currentColor" stroke-width="8" fill="none" stroke-dasharray="283" stroke-dashoffset="283" />
        </svg>
    </button>

    <footer class="bg-gray-900 text-white py-8 sm:py-12 lg:py-16">
        <div class="container px-4 max-w-7xl mx-auto w-full">
            <!-- Main Footer Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
                <!-- About Section -->
                <div>
                    <h3 class="text-base sm:text-lg font-semibold mb-3 sm:mb-4">About Brokers Court</h3>
                    <p class="text-gray-400 text-xs sm:text-sm mb-3 sm:mb-4">
                        Brokers Court is your premier destination for forex trading insights. We provide comprehensive broker reviews, exclusive promotions, and educational resources to empower traders worldwide.
                    </p>
                    <a href="{{ route('about') }}" class="text-yellow-500 text-xs sm:text-sm hover:text-yellow-600 transition">Learn More About Us</a>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-base sm:text-lg font-semibold mb-3 sm:mb-4">Quick Links</h3>
                    <ul class="space-y-2 text-xs sm:text-sm">
                        <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-white transition">Home</a></li>
                        @if(isset($page_data) && $page_data->terms_status == 'Show')
                            <li><a href="{{ route('terms') }}" class="text-gray-400 hover:text-white transition">{{ $page_data->terms_title }}</a></li>
                        @else
                            <li><a href="{{ route('terms') }}" class="text-gray-400 hover:text-white transition">Terms & Conditions</a></li>
                        @endif
                        @if(isset($page_data) && $page_data->privacy_status == 'Show')
                            <li><a href="{{ route('privacy') }}" class="text-gray-400 hover:text-white transition">{{ $page_data->privacy_title }}</a></li>
                        @else
                            <li><a href="{{ route('privacy') }}" class="text-gray-400 hover:text-white transition">Privacy Policy</a></li>
                        @endif
                        @if(isset($page_data) && $page_data->disclaimer_status == 'Show')
                            <li><a href="{{ route('disclaimer') }}" class="text-gray-400 hover:text-white transition">{{ $page_data->disclaimer_title }}</a></li>
                        @else
                            <li><a href="{{ route('disclaimer') }}" class="text-gray-400 hover:text-white transition">Disclaimer</a></li>
                        @endif
                        <li><a href="{{ route('forex.calculator') }}" class="text-gray-400 hover:text-white transition">Forex Calculator</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h3 class="text-base sm:text-lg font-semibold mb-3 sm:mb-4">Get in Touch</h3>
                    <ul class="space-y-2 sm:space-y-3 text-xs sm:text-sm">
                        <li class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-2 text-gray-400"></i>
                            <span class="text-gray-400">Al Nahda 2 Dubai</span>
                        </li>
                        <li class="flex items-center">
                            <i class="far fa-envelope mr-2 text-gray-400"></i>
                            <a href="mailto:info@brokerscourt.com" class="text-gray-400 hover:text-white transition">info@brokerscourt.com</a>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone-alt mr-2 text-gray-400"></i>
                            <a href="tel:" class="text-gray-400 hover:text-white transition">+44 7577 309951</a>
                        </li>
                        <li class="flex items-center">
                            <i class="far fa-clock mr-2 text-gray-400"></i>
                            <span class="text-gray-400">Mon-Fri: 9 AM - 5 PM EST</span>
                        </li>
                    </ul>
                    <!-- Social Links -->
                    <div class="mt-3 sm:mt-4 flex space-x-3 sm:space-x-4">
                        @foreach($global_social_item_data as $item)
                            <a href="{{ $item->url }}" target="_blank" class="text-gray-400 hover:text-white transition" aria-label="{{ $item->name }}">
                                <i class="{{ $item->icon }} text-base sm:text-lg"></i>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Explore Our Services -->
                <div>
                    <h3 class="text-base sm:text-lg font-semibold mb-3 sm:mb-4">Explore Our Services</h3>
                    <ul class="space-y-3 text-gray-400 text-xs sm:text-sm">
                        <li class="flex items-center space-x-2">
                            <i class="fas fa-star mr-2"></i>
                            <span>In-depth Broker Reviews</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <i class="fas fa-lightbulb mr-2"></i>
                            <span>Expert Trading Tips</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <i class="fas fa-chart-line mr-2"></i>
                            <span>Market Analysis & Insights</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <i class="fas fa-gift mr-2"></i>
                            <span>Exclusive Forex Promotions</span>
                        </li>
                    </ul>
                </div>

            </div>


            <!-- Risk Disclosures -->
            <div class="bg-gray-800 rounded-lg p-4 sm:p-6 my-8 sm:my-12">
                <h3 class="text-lg sm:text-xl font-bold mb-4 sm:mb-6 text-center flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle w-5 h-5 sm:w-6 sm:h-6 mr-2 text-yellow-400"></i>
                    Important Risk Disclosures
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                    <div class="bg-gray-700 p-3 sm:p-4 rounded-lg">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-exclamation-triangle w-4 h-4 sm:w-5 sm:h-5 mr-2 text-red-400"></i>
                            <h4 class="font-semibold text-sm sm:text-base">High Risk Investment</h4>
                        </div>
                        <p class="text-gray-300 text-xs sm:text-sm">Trading Forex, CFDs, and other leveraged products carries a high level of risk and may not be suitable for all investors.</p>
                    </div>
                    <div class="bg-gray-700 p-3 sm:p-4 rounded-lg">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-shield-alt w-4 h-4 sm:w-5 sm:h-5 mr-2 text-red-400"></i>
                            <h4 class="font-semibold text-sm sm:text-base">Capital Risk</h4>
                        </div>
                        <p class="text-gray-300 text-xs sm:text-sm">You may lose more than your initial investment. Only trade with money you can afford to lose.</p>
                    </div>
                    <div class="bg-gray-700 p-3 sm:p-4 rounded-lg">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-chart-bar w-4 h-4 sm:w-5 sm:h-5 mr-2 text-red-400"></i>
                            <h4 class="font-semibold text-sm sm:text-base">Past Performance</h4>
                        </div>
                        <p class="text-gray-300 text-xs sm:text-sm">Past performance is not indicative of future results. Historical data should not be used as the sole basis for trading decisions.</p>
                    </div>
                    <div class="bg-gray-700 p-3 sm:p-4 rounded-lg">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-book-open w-4 h-4 sm:w-5 sm:h-5 mr-2 text-red-400"></i>
                            <h4 class="font-semibold text-sm sm:text-base">Educational Purpose</h4>
                        </div>
                        <p class="text-gray-300 text-xs sm:text-sm">The content on this site is for educational purposes only and should not be considered financial advice.</p>
                    </div>
                    <div class="bg-gray-700 p-3 sm:p-4 rounded-lg">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-clipboard w-4 h-4 sm:w-5 sm:h-5 mr-2 text-red-400"></i>
                            <h4 class="font-semibold text-sm sm:text-base">Regulatory Status</h4>
                        </div>
                        <p class="text-gray-300 text-xs sm:text-sm">BrokersCourt does not provide trading services. We are an informational website only.</p>
                    </div>
                    <div class="bg-gray-700 p-3 sm:p-4 rounded-lg">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-clock w-4 h-4 sm:w-5 sm:h-5 mr-2 text-red-400"></i>
                            <h4 class="font-semibold text-sm sm:text-base">Time Sensitive</h4>
                        </div>
                        <p class="text-gray-300 text-xs sm:text-sm">Market conditions change rapidly. Information may become outdated quickly. Verify all data before trading.</p>
                    </div>
                </div>
            </div>

            <!-- Regulatory Compliance -->
            <div class="bg-gray-800 rounded-lg p-4 sm:p-6 mb-6 sm:mb-8">
                <h3 class="text-lg sm:text-xl font-bold mb-3 sm:mb-4">Regulatory Compliance</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <div>
                        <h4 class="font-semibold mb-2 text-sm sm:text-base">
                            <i class="fas fa-building w-4 h-4 sm:w-5 sm:h-5 mr-2 text-red-400"></i>
                            Broker Regulation
                        </h4>
                        <p class="text-gray-300 text-xs sm:text-sm">The brokers listed on our site are regulated by one or more of the following authorities: FCA (UK), ASIC (Australia), CySEC (Cyprus), FSCA (South Africa), and other reputable regulators.</p>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-2 text-sm sm:text-base">
                            <i class="fas fa-exclamation-circle w-4 h-4 sm:w-5 sm:h-5 mr-2 text-red-400"></i>
                            Site Disclaimer
                        </h4>
                        <p class="text-gray-300 text-xs sm:text-sm">BrokersCourt is an independent comparison site and information service. We may receive compensation from brokers we feature. All broker reviews are based on our team's independent research and analysis.</p>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-800 my-6 sm:my-8"></div>

            <!-- Copyright and Risk Warning -->
            <div class="flex flex-col sm:flex-row justify-between items-center text-gray-400 text-xs sm:text-sm">
                <div class="mb-4 sm:mb-0 text-center sm:text-left">
                    <p>© 2025 BrokersCourt. All rights reserved.</p>
                    <p class="mt-1 text-xs">BrokersCourt is not a broker or financial advisor. All trading involves risk.</p>
                </div>
                <div class="text-center sm:text-right">
                    <p class="text-xs max-w-full sm:max-w-md">
                        <strong>General Risk Warning:</strong> The financial products offered by the companies listed on this website carry a high level of risk and can result in the loss of all your funds. You should never trade with money that you cannot afford to lose. Before trading, please ensure you understand the risks involved and consider your level of experience. Seek independent advice if necessary.
                    </p>
                </div>
            </div>
        </div>
    </footer>


    <!-- SweetAlert for success and error -->
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#d33',
                confirmButtonText: 'OK'
            });
        </script>
    @endif
    <!-- iziToast for errors -->
    @if($errors->any())
        @foreach($errors->all() as $error)
            <script>
                iziToast.error({
                    title: '',
                    position: 'topRight',
                    message: '{{ $error }}',
                });
            </script>
        @endforeach
    @endif

</body>
</html>
