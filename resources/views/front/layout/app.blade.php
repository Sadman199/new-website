
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $seoTitle = trim($__env->yieldContent('title')) ?: 'BrokersCourt - Compare and Find Top Forex Brokers, Reviews, and Deals';
        $seoDescription = trim($__env->yieldContent('meta_description')) ?: \App\Support\SiteTheme::defaultMetaDescription();
        $seoCanonical = trim($__env->yieldContent('canonical')) ?: url()->current();
        $seoOgImage = \App\Support\SiteTheme::ogImageUrl(trim($__env->yieldContent('og_image')) ?: null);
        $seoOgImageWidth = trim($__env->yieldContent('og_image_width')) ?: null;
        $seoOgImageHeight = trim($__env->yieldContent('og_image_height')) ?: null;
        $seoRobots = trim($__env->yieldContent('robots')) ?: 'index, follow';
        $seoSiteName = \App\Support\SiteTheme::siteName();
    @endphp

    <!-- SEO Meta Tags -->
    <meta name="description" content="{{ $seoDescription }}">
    <meta name="author" content="{{ $seoSiteName }}">
    <meta name="robots" content="{{ $seoRobots }}">
    <link rel="canonical" href="{{ $seoCanonical }}">

    <title>{{ $seoTitle }}</title>

    @stack('head')

    <!-- Google Tag Manager -->
    <script>window.dataLayer = window.dataLayer || [];</script>
    <script async src="https://www.googletagmanager.com/gtm.js?id=GTM-W3MTNWPW"></script>
    <!-- End Google Tag Manager -->

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $seoSiteName }}">
    <meta property="og:url" content="{{ $seoCanonical }}">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:image" content="{{ $seoOgImage }}">
    <meta property="og:image:alt" content="{{ $seoTitle }}">
    @if($seoOgImageWidth)
        <meta property="og:image:width" content="{{ $seoOgImageWidth }}">
    @endif
    @if($seoOgImageHeight)
        <meta property="og:image:height" content="{{ $seoOgImageHeight }}">
    @endif

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:creator" content="@BrokersCourt">
    <meta name="twitter:title" content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDescription }}">
    <meta name="twitter:image" content="{{ $seoOgImage }}">
    @stack('json_ld')
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ \App\Support\SiteTheme::faviconUrl() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Optimized CSS -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet" data-bc-global>
    <link rel="dns-prefetch" href="https://www.googletagmanager.com">
    <link rel="preconnect" href="https://www.googletagmanager.com" crossorigin>

    <!-- Additional Includes -->
    @include('front.layout.partials.site-theme')
    @include('front.layout.styles')
    @include('front.layout.scripts')
    @if($showCountryBrokersStrip ?? false)
        <link rel="stylesheet" href="{{ asset('css/country-brokers.css') }}?v=3">
    @endif
    @stack('page-styles')
    <script src="{{ asset('js/bc-nav-optimizer.js') }}?v=3" defer></script>
</head>


<body data-bc-nav="prefetch" @auth('web') data-user-auth="1" data-saved-sync-url="{{ route('user.saved_brokers.sync') }}" data-saved-index-url="{{ route('user.saved_brokers.index') }}" @endauth>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-W3MTNWPW" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <div id="bc-nav-progress" aria-hidden="true"><div id="bc-nav-progress__bar"></div></div>
    <div id="bc-nav-veil" aria-hidden="true"></div>


    @include('front.layout.nav')

    @if(\App\Support\SiteTheme::showQuickAccessDrawer())
        <div data-bc-persist="quick-access">
            @include('front.layout.partial.quick-access-drawers')
        </div>
    @endif
    @if(\App\Support\SiteTheme::showBrokerSpotlight())
        <div data-bc-persist="broker-spotlight">
            @include('front.layout.partial.broker-spotlight-dock')
        </div>
    @endif

    <main id="bc-page-root" tabindex="-1">
        @yield('main_content')
        @if($showCountryBrokersStrip ?? false)
            @include('front.layout.partial.country-brokers-strip')
        @endif
    </main>

    @stack('scripts')
    @if($showCountryBrokersStrip ?? false)
        <script src="{{ asset('js/country-brokers.js') }}?v=1" defer></script>
    @endif
    <script src="{{ asset('js/country-drawer.js') }}?v=4" defer></script>
    
    
    

   <!-- Scroll to Top -->
    <button type="button" id="scrollToTopBtn" data-bc-persist="scroll-top" class="bc-scroll-top" aria-label="Scroll to top">
        <i class="fas fa-chevron-up" aria-hidden="true"></i>
    </button>

    <div data-bc-persist="site-footer">
        @include('front.layout.partial.mega-footer')
    </div>

    @if(\App\Support\SiteTheme::showQuickAccessDrawer())
        <script src="{{ asset('js/quick-access-drawers.js') }}?v=5" defer></script>
    @endif
    @if(\App\Support\SiteTheme::showBrokerSpotlight())
        <script src="{{ asset('js/broker-spotlight-dock.js') }}?v=1" defer></script>
    @endif

    @auth('web')
        <script src="{{ asset('js/nav-notifications.js') }}?v=1" defer></script>
        <script src="{{ asset('js/user-saved-brokers.js') }}?v=1" defer></script>
    @endauth


    @if(session('success') || session('error') || $errors->any())
        @include('front.layout.partials.sweetalert')
    @endif

    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: @json(session('success')),
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
                text: @json(session('error')),
                confirmButtonColor: '#d33',
                confirmButtonText: 'OK'
            });
        </script>
    @endif

    @if($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Please fix the errors',
                text: @json($errors->first()),
                confirmButtonColor: '#d33',
                confirmButtonText: 'OK'
            });
        </script>
    @endif

    @include('front.partials.popup-ads')

</body>
</html>
