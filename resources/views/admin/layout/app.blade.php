<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <link rel="icon" type="image/png" href="{{ \App\Support\SiteTheme::faviconUrl() }}">
    <title>Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            prefix: 'tw-',
            corePlugins: { preflight: false },
            theme: {
                extend: {
                    colors: {
                        brand: {
                            DEFAULT: '{{ \App\Support\SiteTheme::primary() }}',
                            dark: '{{ \App\Support\SiteTheme::dark() }}',
                            light: '{{ \App\Support\SiteTheme::light() }}'
                        }
                    }
                }
            }
        }
    </script>
    @include('admin.layout.styles')
    @include('admin.layout.scripts')
    @stack('styles')
</head>

<body>
  
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" async></script>

    
<div id="app">
    <div class="main-wrapper">
        @include('admin.layout.nav')
        @include('admin.layout.sidebar')        
        <div class="main-content @yield('main_content_class')">
            @hasSection('dashboard_page')
                @yield('main_content')
            @else
            <section class="section">
                <div class="section-header">
                    <h1>@yield('heading')</h1>
                    <div class="ml-auto">
                        @yield('button')
                    </div>
                </div>
                @yield('main_content')
            </section>
            @endif
        </div>
    </div>
</div>
@include('admin.layout.scripts_footer')
<script src="{{ asset('js/admin-topbar.js') }}?v=2"></script>
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
    @stack('scripts')
</body>
</html>