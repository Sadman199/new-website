<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <link rel="icon" type="image/png" href="{{ asset('uploads/favicon.png') }}">
    <title>Admin Login — BrokersCourt</title>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600;700&display=swap" rel="stylesheet">
    @include('admin.layout.styles')
    <style>
        body { background: #f4f6fb; }
        .admin-login-wrap { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem 1rem; }
        .admin-login-card { width: 100%; max-width: 420px; border: 1px solid #e4e6fc; box-shadow: 0 10px 40px rgba(52, 57, 94, 0.08); }
        .admin-login-card .card-header { background: #fff; border-bottom: 1px solid #eef0f8; padding: 1.75rem 1.5rem 1rem; }
        .admin-login-card .card-body { padding: 1.5rem; }
        .admin-login-card h4 { font-weight: 700; color: #34395e; margin: 0; }
        .admin-login-card .subtitle { color: #6c757d; font-size: 0.875rem; margin-top: 0.35rem; }
        .admin-login-alert { border-radius: 0.5rem; padding: 0.75rem 1rem; font-size: 0.875rem; margin-bottom: 1rem; }
        .admin-login-alert--success { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
        .admin-login-alert--error { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .admin-login-card label { font-weight: 600; font-size: 0.8125rem; color: #34395e; margin-bottom: 0.35rem; }
        .admin-login-card .form-control { min-height: 46px; border-radius: 0.5rem; }
        .admin-login-card .btn-login { min-height: 46px; font-weight: 700; border-radius: 0.5rem; }
        .admin-login-meta { display: flex; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: 1rem; }
    </style>
</head>
<body>
<div class="admin-login-wrap">
    <div class="card card-primary admin-login-card">
        <div class="card-header">
            <h4>Admin Login</h4>
            <p class="subtitle">Sign in to manage BrokersCourt</p>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="admin-login-alert admin-login-alert--success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="admin-login-alert admin-login-alert--error">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('admin_login_submit') }}" novalidate>
                @csrf

                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email"
                           id="email"
                           name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}"
                           placeholder="admin@example.com"
                           autocomplete="username"
                           autofocus
                           required>
                    @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password"
                           id="password"
                           name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Enter your password"
                           autocomplete="current-password"
                           required>
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="admin-login-meta">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="remember" id="remember" value="1" @checked(old('remember'))>
                        <label class="custom-control-label" for="remember">Remember me</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-login">
                    Sign in
                </button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
