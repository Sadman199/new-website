<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <link rel="icon" type="image/png" href="{{ \App\Support\SiteTheme::faviconUrl() }}">
    <title>Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ mix('css/admin-tw.css') }}">
    @include('admin.layout.styles')
    @stack('styles')
</head>

<body>
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

{{-- Core + plugins at end of body (was blocking in <head>) --}}
@include('admin.layout.scripts')
@include('admin.layout.scripts_footer')
<script src="{{ asset('js/admin-topbar.js') }}?v=3" defer></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
<script>
    window.bcAdminSwal = function (options) {
        function fire() {
            if (window.Swal && typeof window.Swal.fire === 'function') {
                window.Swal.fire(options);
                return;
            }
            window.setTimeout(fire, 40);
        }
        fire();
    };
</script>

@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            window.bcAdminSwal({
                icon: 'success',
                title: 'Success!',
                text: @json(session('success')),
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        });
    </script>
@endif

@if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            window.bcAdminSwal({
                icon: 'error',
                title: 'Error!',
                text: @json(session('error')),
                confirmButtonColor: '#d33',
                confirmButtonText: 'OK'
            });
        });
    </script>
@endif

@if($errors->any())
    @foreach($errors->all() as $error)
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (window.iziToast) {
                    iziToast.error({
                        title: '',
                        position: 'topRight',
                        message: @json($error),
                    });
                }
            });
        </script>
    @endforeach
@endif
@stack('scripts')
</body>
</html>
