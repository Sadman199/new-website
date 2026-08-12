<!-- Core front styles -->

<link rel="stylesheet" href="{{ asset('dist-front/css/spacing.css') }}" data-bc-global>

<link rel="stylesheet" href="{{ asset('dist-front/css/font_awesome_5_free.min.css') }}" data-bc-global>

<link rel="stylesheet" href="{{ asset('dist-front/css/style.css') }}" data-bc-global>

<link rel="stylesheet" href="{{ asset('dist-front/css/responsive.css') }}" data-bc-global>

<link rel="stylesheet" href="{{ asset('css/mega-footer.css') }}?v=8" data-bc-global>

@if(\App\Support\SiteTheme::showQuickAccessDrawer())

<link rel="stylesheet" href="{{ asset('css/quick-access-drawers.css') }}?v=5" data-bc-global>

@endif

@if(\App\Support\SiteTheme::showBrokerSpotlight())

<link rel="stylesheet" href="{{ asset('css/broker-spotlight-dock.css') }}?v=3" data-bc-global>

@endif

@auth('web')
<link rel="stylesheet" href="{{ asset('css/nav-notifications.css') }}?v=1" data-bc-global>
@endauth
