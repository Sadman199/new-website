
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('meta_description', 'BrokersCourt helps you compare and find top forex brokers, read expert reviews, and grab exclusive deals on trading accounts.')">
    <meta name="keywords" content="@yield('meta_keywords', 'Forex Brokers, Forex Broker Comparison, Broker Reviews, Forex Deals, Top Forex Brokers')">
    <meta name="author" content="BrokersCourt">
    <meta name="robots" content="index, follow">

    <title>@yield('title', 'BrokersCourt - Compare and Find Top Forex Brokers, Reviews, and Deals')</title>

    @stack('head')

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
    @stack('page-styles')
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
    @stack('scripts')
    
    
    

   <!-- Scroll to Top Button -->
    <button id="scrollToTopBtn" class="fixed bottom-6 right-6 bg-yellow-500 text-white rounded-full shadow-lg hover:bg-yellow-600 focus:outline-none transition-all duration-300 w-16 h-16 flex items-center justify-center" style="opacity: 0; pointer-events: none;">
        <span id="scrollPercentage" class="absolute text-sm font-medium">0%</span>
        <svg class="absolute w-full h-full top-0 left-0 -rotate-90" viewBox="0 0 100 100">
            <circle cx="50" cy="50" r="45" stroke="" stroke-width="8" fill="none" />
            <circle id="scrollProgress" cx="50" cy="50" r="45" stroke="currentColor" stroke-width="8" fill="none" stroke-dasharray="283" stroke-dashoffset="283" />
        </svg>
    </button>

    @include('front.layout.partial.mega-footer')


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

    @include('front.partials.popup-ads')

</body>
</html>
