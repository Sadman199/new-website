@php
$brokers = \App\Models\Broker::all(); // Fetch brokers dynamically
$global_categories = \App\Models\Category::with('rSubCategory')->where('show_on_menu',
'Show')->orderBy('category_order', 'asc')->get();
@endphp
<nav class="navbar custom-nav navbar-expand-lg fixed-top" id="mainNavbar">
    <div class="container position-relative">
      <!-- Logo -->
      <div class="logo me-3">
            <a href="{{ route('home') }}">
            <img src="{{ asset('uploads/'.$global_setting_data->logo) }}" alt="Logo" id="navbarLogo">
            </a>
        </div>
        <!-- Toggle Button -->
        <button class="navbar-toggler custom-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="toggler-icon"></span>
                <span class="toggler-icon"></span>
                <span class="toggler-icon"></span>
        </button>
        <!-- Navbar Content -->
        <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
            <ul class="navbar-nav mb-2 mb-lg-0 site_ul">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="{{ route('home') }}">HOME</a>
                </li>
                <!-- Forex Bonus Mega Dropdown -->
                <li class="nav-item dropdown position-static">
                    <a class="nav-link dropdown-toggle c_nav" href="#" id="forexBonusDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Bonus
                    </a>
                    <div class="dropdown-menu mega-menu" aria-labelledby="forexBonusDropdown">
                        <div class="row">
                            
                            <div class="col-lg-6"> <!-- First column -->
                               <div class="m_content_border">
                                        @php
                                        $bonusTypes = [
                                            'deposit-bonuses' => ['name' => 'Forex Deposit Bonus'],
                                            'no-deposit-bonuses' => ['name' => 'Forex No Deposit Bonus'],
                                            'live-contests' => ['name' => 'Forex Live Contest'],
                                        ];
                                    @endphp
                                    @foreach ($bonusTypes as $type => $bonus)
                                        <a class="dropdown-item" href="{{ route('bonuses.type', $type) }}">
                                            {{ $bonus['name'] }}
                                        </a>
                                    @endforeach
                               </div>
                            </div>
                            <div class="col-lg-6">
                                @php
                                    $bonusTypes2 = [
                                        'demo-contests' => ['name' => 'Forex Demo Contest'],
                                        'cashback-rebates' => ['name' => 'Forex Cashback Rebate'],
                                        'crypto-bonuses' => ['name' => 'Crypto Bonus Promotion'],
                                    ];
                                @endphp
                                @foreach ($bonusTypes2 as $type => $bonus)
                                    <a class="dropdown-item" href="{{ route('bonuses.type', $type) }}">
                                        {{ $bonus['name'] }}
                                    </a>
                                @endforeach
                                
                            </div>
                        </div>
                    </div>
                </li>
                <li class="nav-item dropdown position-static">
                    <a class="nav-link dropdown-toggle c_nav" href="#" id="brokersDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Broker Types
                    </a>
                    <div class="dropdown-menu custom-dropdown-menu mega-menu" aria-labelledby="brokersDropdown">
                        <div class="row">
                            @php
                                // Hardcode the accountTypes array with all available types
                                $accountTypes = [
                                    'Standard Accounts',
                                    'Islamic Account',
                                    'ECN Accounts',
                                    'Classic Account',
                                    'Copy Trading Accounts',
                                    'VIP Accounts',
                                    'Raw Account',
                                    'Micro Accounts'
                                ];

                                // Split accountTypes into two chunks for better layout
                                $accountTypeChunks = array_chunk($accountTypes, ceil(count($accountTypes) / 2));
                            @endphp

                            @foreach ($accountTypeChunks as $chunk)
                                <div class="col-lg-6">
                                    <div class="account-types">
                                        <ul class="list-disc pl-5">
                                            @foreach ($chunk as $type)
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('brokers.byAccountType', ['type' => strtolower(str_replace(' ', '-', $type))]) }}" class="text-lg text-blue-600 hover:text-blue-800">
                                                        {{ $type }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </li>
                <li class="nav-item dropdown position-static">
                    <a class="nav-link dropdown-toggle c_nav" href="#" id="countriesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Regional Brokers
                    </a>
                    <div class="dropdown-menu custom-dropdown-menu mega-menu" aria-labelledby="countriesDropdown">
                        <div class="row">
                            <!-- Brokers by Country -->
                            <div class="col-lg-12">
                                <div class="row">
                                    @php
                                        // Define a static list of all countries
                                        $allCountries = collect([
                                            'Asia', 'USA', 'Canada', 'UK', 'Australia', 'South Africa', 
                                            'Germany', 'France', 'India', 'China', 'Japan', 'Brazil'
                                        ]);

                                        // Split into 3 columns
                                        $columns = $allCountries->chunk(ceil($allCountries->count() / 3));
                                    @endphp

                                    @foreach ($columns as $column)
                                        <div class="col-md-4">
                                            <ul class="list-unstyled">
                                                @foreach ($column as $country)
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('broker_by_country', ['country' => urlencode($country)]) }}">
                                                            {{ $country }} Brokers
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <!-- Categories Mega Dropdown -->
                @foreach($global_categories->where('language_id', $current_language_id) as $item)
                    <li class="nav-item dropdown position-static">
                        <a class="nav-link dropdown-toggle c_nav" href="#" id="categoryDropdown{{ $item->id }}" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ $item->category_name }}
                        </a>
                        <div class="dropdown-menu mega-menu" aria-labelledby="categoryDropdown{{ $item->id }}">
                            <div class="row">
                                @php
                                    $chunks = $item->rSubCategory->chunk(ceil($item->rSubCategory->count() / 3));
                                @endphp
                                @foreach($chunks as $chunk)
                                    <div class="col-lg-4">
                                        @foreach($chunk as $item2)
                                            <a class="dropdown-item" href="{{ route('category', $item2->slug) }}">
                                                {{ $item2->sub_category_name }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</nav>
<script>
$(document).ready(function () {
    $('.nav-item.dropdown').on('click', function (e) {
        var $this = $(this);
        var $menu = $this.find('.dropdown-menu');
        if ($this.hasClass('show')) {
            $menu.stop(true, true).fadeOut(100, 'easeInOutQuad');  // Fade out with easing
            $this.removeClass('show');
        } else {
            $('.nav-item.dropdown').not($this).find('.dropdown-menu').stop(true, true).fadeOut(100, 'easeInOutQuad');
            $('.nav-item.dropdown').removeClass('show');
            $menu.stop(true, true).fadeIn(100, 'easeInOutQuad');  // Fade in with easing
            $this.addClass('show');
        }
    });
});
document.addEventListener("DOMContentLoaded", function () {
    const navbar = document.getElementById("mainNavbar");
    if (window.scrollY > 50) {
        navbar.classList.add("scrolled");
    }
    window.addEventListener("scroll", function () {
        if (window.scrollY > 50) {
            navbar.classList.add("scrolled");
        } else {
            navbar.classList.remove("scrolled");
        }
    });
});
</script>