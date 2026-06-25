@php
$brokers = \App\Models\Broker::all();
$global_categories = \App\Models\Category::with('rSubCategory')->where('show_on_menu', 'Show')->orderBy('category_order', 'asc')->get();
@endphp

<nav class="fixed top-0 w-full z-50 bg-gray-950/80 backdrop-blur-xl border-b border-gray-800" id="navbar">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Left Section: Logo -->
            <div class="flex items-center gap-8">
                <a href="{{ route('home') }}" class="flex-shrink-0">
                    <img src="{{ asset('uploads/'.$global_setting_data->logo) }}" alt="Logo" class="h-8 w-auto hover:opacity-80 transition-opacity">
                </a>
                
                <!-- Main Navigation Links -->
                <div class="hidden lg:flex lg:items-center lg:gap-1">
                    <a href="{{ route('home') }}" class="px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg hover:bg-white/5 transition-all duration-200">
                        Home
                    </a>
                    
                    <!-- Brokers Mega Menu Trigger -->
                    <div class="relative" id="brokersDropdown">
                        <button class="brokers-trigger px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg hover:bg-white/5 transition-all duration-200 inline-flex items-center gap-1.5" id="brokersButton">
                            Brokers
                            <svg class="w-3.5 h-3.5 transition-transform duration-200 chevron-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        
                        <!-- Mega Menu Panel -->
                        <div class="brokers-mega-menu absolute top-full left-0 mt-2 w-[720px] hidden" id="brokersMegaMenu">
                            <div class="bg-gray-900 rounded-2xl border border-gray-800 shadow-2xl shadow-black/50 p-6">
                                <div class="grid grid-cols-3 gap-8">
                                    <!-- Column 1: Categories -->
                                    <div>
                                        <div class="flex items-center gap-2 mb-3">
                                            <div class="w-6 h-6 rounded-lg bg-yellow-500/10 flex items-center justify-center">
                                                <svg class="w-3.5 h-3.5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                </svg>
                                            </div>
                                            <span class="text-xs font-semibold text-gray-300 uppercase tracking-wider">Categories</span>
                                        </div>
                                        <div class="space-y-0.5">
                                            @php
                                            $brokerCategories = [
                                                'low-spread-brokers' => 'Low Spread',
                                                'free-withdrawal-brokers' => 'Free Withdrawal',
                                                'mt4-brokers' => 'MT4 Platform',
                                                'mt5-brokers' => 'MT5 Platform',
                                                'micro-account-brokers' => 'Micro Account',
                                                'copy-trading-brokers' => 'Copy Trading',
                                                'social-trading-brokers' => 'Social Trading',
                                                'scalping-brokers' => 'Scalping',
                                                'trading-apps-brokers' => 'Trading Apps',
                                                'beginner-friendly-brokers' => 'Beginner Friendly',
                                            ];
                                            @endphp
                                            
                                            @foreach ($brokerCategories as $slug => $name)
                                                <a href="{{ route('brokers.best',['slug'=>$slug]) }}" class="block px-3 py-1.5 text-sm text-gray-400 hover:text-yellow-500 hover:bg-yellow-500/5 rounded-lg transition-all duration-150">
                                                    {{ $name }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    <!-- Column 2: Countries -->
                                    <div>
                                        <div class="flex items-center gap-2 mb-3">
                                            <div class="w-6 h-6 rounded-lg bg-yellow-500/10 flex items-center justify-center">
                                                <svg class="w-3.5 h-3.5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </div>
                                            <span class="text-xs font-semibold text-gray-300 uppercase tracking-wider">Countries</span>
                                        </div>
                                        <div class="space-y-0.5">
                                            @php
                                            $countries = [
                                                'united-kingdom' => 'United Kingdom',
                                                'india' => 'India',
                                                'bangladesh' => 'Bangladesh',
                                                'singapore' => 'Singapore',
                                                'malaysia' => 'Malaysia',
                                                'south-africa' => 'South Africa',
                                                'nigeria' => 'Nigeria',
                                                'australia' => 'Australia',
                                                'canada' => 'Canada',
                                            ];
                                            @endphp
                                            
                                            @foreach($countries as $slug => $name)
                                                <a href="{{ url('best-brokers/'.$slug) }}" class="flex items-center justify-between px-3 py-1.5 text-sm text-gray-400 hover:text-yellow-500 hover:bg-yellow-500/5 rounded-lg transition-all duration-150 group/item">
                                                    <span>{{ $name }}</span>
                                                    <svg class="w-3 h-3 opacity-0 group-hover/item:opacity-100 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                    </svg>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    <!-- Column 3: Top Brokers -->
                                    <div>
                                        <div class="flex items-center gap-2 mb-3">
                                            <div class="w-6 h-6 rounded-lg bg-yellow-500/10 flex items-center justify-center">
                                                <svg class="w-3.5 h-3.5 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                                </svg>
                                            </div>
                                            <span class="text-xs font-semibold text-gray-300 uppercase tracking-wider">Top Rated</span>
                                        </div>
                                        <div class="space-y-1">
                                            @foreach($topRatedBrokers->take(4) as $broker)
                                                <a href="{{ route('broker_detail',['slug'=>$broker->slug]) }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-800 transition-all duration-150 group/item">
                                                    <div class="w-8 h-8 rounded-lg bg-gray-800 flex items-center justify-center overflow-hidden border border-gray-700">
                                                        <img src="{{ asset($broker->logo) }}" alt="{{ $broker->name }}" class="w-5 h-5 object-contain">
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-sm text-gray-300 group-hover/item:text-white truncate">{{ $broker->name }}</p>
                                                        <div class="flex items-center gap-1 mt-0.5">
                                                            <span class="text-yellow-500 text-xs">★</span>
                                                            <span class="text-xs text-gray-500">{{ number_format($broker->rating,1) }}</span>
                                                        </div>
                                                    </div>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <a href="{{ route('awards.index') }}" onclick="window.open(this.href, '_blank', 'noopener'); return false;" class="px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg hover:bg-white/5 transition-all duration-200">
                        Awards
                    </a>
                    
                    <!-- Company Dropdown -->
                    <div class="relative" id="companyDropdown">
                        <button class="company-trigger px-3 py-2 text-sm text-gray-400 hover:text-white rounded-lg hover:bg-white/5 transition-all duration-200 inline-flex items-center gap-1.5" id="companyButton">
                            Company
                            <svg class="w-3.5 h-3.5 transition-transform duration-200 company-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        
                        <div class="company-menu absolute top-full left-0 mt-2 w-56 hidden" id="companyMenu">
                            <div class="bg-gray-900 rounded-xl border border-gray-800 shadow-2xl shadow-black/50 p-2">
                                <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-400 hover:text-white rounded-lg hover:bg-white/5 transition-all duration-150">
                                    <div class="w-8 h-8 rounded-lg bg-yellow-500/10 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium">Tools</p>
                                        <p class="text-xs text-gray-500">Trading resources</p>
                                    </div>
                                </a>
                                <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-400 hover:text-white rounded-lg hover:bg-white/5 transition-all duration-150">
                                    <div class="w-8 h-8 rounded-lg bg-yellow-500/10 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium">About Us</p>
                                        <p class="text-xs text-gray-500">Our team & story</p>
                                    </div>
                                </a>
                                <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-400 hover:text-white rounded-lg hover:bg-white/5 transition-all duration-150">
                                    <div class="w-8 h-8 rounded-lg bg-yellow-500/10 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium">Contact</p>
                                        <p class="text-xs text-gray-500">Get in touch</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Section: Actions -->
            <div class="hidden lg:flex lg:items-center lg:gap-3">
                <a href="{{ route('all_brokers') }}" class="px-4 py-2 text-sm text-gray-400 hover:text-white rounded-lg hover:bg-white/5 transition-all duration-200">
                    All Brokers
                </a>
                <a href="#" class="px-5 py-2 text-sm font-medium text-black bg-yellow-500 hover:bg-yellow-400 rounded-lg transition-all duration-200 shadow-lg shadow-yellow-500/20 hover:shadow-yellow-500/40">
                    Start Trading
                </a>
            </div>
            
            <!-- Mobile Menu Button -->
            <div class="lg:hidden">
                <button id="mobileMenuButton" class="p-2 text-gray-400 hover:text-white rounded-lg hover:bg-white/5 transition-all duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu (Hidden by default) -->
        <div class="lg:hidden hidden" id="mobileMenu">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ route('home') }}" class="block px-3 py-2 text-base font-medium text-gray-400 hover:text-white hover:bg-white/5 rounded-lg">Home</a>
                <a href="#" class="block px-3 py-2 text-base font-medium text-gray-400 hover:text-white hover:bg-white/5 rounded-lg">Brokers</a>
                <a href="{{ route('awards.index') }}" class="block px-3 py-2 text-base font-medium text-gray-400 hover:text-white hover:bg-white/5 rounded-lg">Awards</a>
                <a href="#" class="block px-3 py-2 text-base font-medium text-gray-400 hover:text-white hover:bg-white/5 rounded-lg">Company</a>
                <a href="{{ route('all_brokers') }}" class="block px-3 py-2 text-base font-medium text-gray-400 hover:text-white hover:bg-white/5 rounded-lg">All Brokers</a>
                <a href="#" class="block px-3 py-2 text-base font-medium text-black bg-yellow-500 hover:bg-yellow-400 rounded-lg text-center">Start Trading</a>
            </div>
        </div>
    </div>
</nav>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Brokers Mega Menu - Click to Toggle
    $('#brokersButton').click(function(e) {
        e.stopPropagation();
        e.preventDefault();
        
        var $megaMenu = $('#brokersMegaMenu');
        var $chevron = $(this).find('.chevron-icon');
        var $companyMenu = $('#companyMenu');
        var $companyChevron = $('.company-chevron');
        
        // Close company menu if open
        if ($companyMenu.is(':visible')) {
            $companyMenu.slideUp(200);
            $companyChevron.css('transform', 'rotate(0deg)');
            $('.company-trigger').removeClass('text-white bg-white/5');
        }
        
        // Toggle brokers mega menu
        $megaMenu.slideToggle(200);
        $chevron.css('transform', $megaMenu.is(':visible') ? 'rotate(180deg)' : 'rotate(0deg)');
        
        // Toggle active state
        $(this).toggleClass('text-white bg-white/5');
    });
    
    // Company Menu - Click to Toggle
    $('#companyButton').click(function(e) {
        e.stopPropagation();
        e.preventDefault();
        
        var $companyMenu = $('#companyMenu');
        var $companyChevron = $(this).find('.company-chevron');
        var $brokersMenu = $('#brokersMegaMenu');
        var $brokersChevron = $('.chevron-icon');
        
        // Close brokers menu if open
        if ($brokersMenu.is(':visible')) {
            $brokersMenu.slideUp(200);
            $brokersChevron.css('transform', 'rotate(0deg)');
            $('.brokers-trigger').removeClass('text-white bg-white/5');
        }
        
        // Toggle company menu
        $companyMenu.slideToggle(200);
        $companyChevron.css('transform', $companyMenu.is(':visible') ? 'rotate(180deg)' : 'rotate(0deg)');
        
        // Toggle active state
        $(this).toggleClass('text-white bg-white/5');
    });
    
    // Close menus when clicking outside
    $(document).click(function(e) {
        if (!$(e.target).closest('#brokersDropdown').length) {
            $('#brokersMegaMenu').slideUp(200);
            $('.chevron-icon').css('transform', 'rotate(0deg)');
            $('.brokers-trigger').removeClass('text-white bg-white/5');
        }
        
        if (!$(e.target).closest('#companyDropdown').length) {
            $('#companyMenu').slideUp(200);
            $('.company-chevron').css('transform', 'rotate(0deg)');
            $('.company-trigger').removeClass('text-white bg-white/5');
        }
    });
    
    // Close menus when pressing ESC key
    $(document).keydown(function(e) {
        if (e.key === "Escape") {
            $('#brokersMegaMenu').slideUp(200);
            $('.chevron-icon').css('transform', 'rotate(0deg)');
            $('.brokers-trigger').removeClass('text-white bg-white/5');
            
            $('#companyMenu').slideUp(200);
            $('.company-chevron').css('transform', 'rotate(0deg)');
            $('.company-trigger').removeClass('text-white bg-white/5');
        }
    });
    
    // Mobile Menu Toggle
    $('#mobileMenuButton').click(function() {
        $('#mobileMenu').slideToggle(200);
    });
    
    // Keep mega menu open when hovering after click
    var brokersMenuOpen = false;
    var companyMenuOpen = false;
    
    $('#brokersMegaMenu, #brokersButton').hover(
        function() {
            if ($('#brokersMegaMenu').is(':visible')) {
                brokersMenuOpen = true;
            }
        },
        function() {
            brokersMenuOpen = false;
        }
    );
    
    $('#companyMenu, #companyButton').hover(
        function() {
            if ($('#companyMenu').is(':visible')) {
                companyMenuOpen = true;
            }
        },
        function() {
            companyMenuOpen = false;
        }
    );
});
</script>