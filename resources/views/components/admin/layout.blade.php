<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('uploads/favicon.png') }}">
    <title>{{ $title ?? 'Admin' }} — {{ config('admin-panel.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/admin.css') }}">
    @stack('styles')
</head>
<body>

<div class="admin-wrapper">
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    @include('admin.panel.partials.sidebar')

    <div class="admin-main">
        @include('admin.panel.partials.header')

        <main class="admin-content">
            @if(session('success'))
                <div class="alert alert-success bc-alert mb-3">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger bc-alert mb-3">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger bc-alert mb-3">
                    <ul class="mb-0 pl-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('assets/admin/js/admin.js') }}"></script>
@stack('scripts')
</body>
</html>
