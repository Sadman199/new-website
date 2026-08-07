<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <link rel="icon" type="image/png" href="{{ asset('uploads/favicon.png') }}">
    <title>Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600;700&display=swap" rel="stylesheet">
    @include('admin.layout.styles')
    @include('admin.layout.scripts')
    
</head>

<body>
  
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" async></script>

    
<div id="app">
    <div class="main-wrapper">
        @include('admin.layout.nav')
        @include('admin.layout.sidebar')        
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>@yield('heading')</h1>
                    <div class="ml-auto">
                        @yield('button')
                    </div>
                </div>
                @yield('main_content')
            </section>
        </div>
    </div>
</div>
@include('admin.layout.scripts_footer')
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