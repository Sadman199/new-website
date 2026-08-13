<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <link rel="icon" type="image/png" href="{{ asset('uploads/favicon.png') }}">
    <title>Author Login — BrokersCourt</title>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600;700&display=swap" rel="stylesheet">
    @include('author.layout.styles')
    <style>
        body { background: #f4f6fb; }
        .author-login-wrap { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem 1rem; }
        .author-login-card { width: 100%; max-width: 420px; border: 1px solid #e4e6fc; }
        .author-login-card .card-header { background: #fff; border-bottom: 1px solid #eef0f8; padding: 1.75rem 1.5rem 1rem; }
        .author-login-card .card-body { padding: 1.5rem; }
        .author-login-card h4 { font-weight: 700; color: #34395e; margin: 0; }
        .author-login-card .subtitle { color: #6c757d; font-size: 0.875rem; margin-top: 0.35rem; }
        .author-login-alert { border-radius: 0.5rem; padding: 0.75rem 1rem; font-size: 0.875rem; margin-bottom: 1rem; }
        .author-login-alert--success { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
        .author-login-alert--error { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .author-login-card label { font-weight: 600; font-size: 0.8125rem; color: #34395e; margin-bottom: 0.35rem; }
        .author-login-card .form-control { min-height: 46px; border-radius: 0.5rem; }
        .author-login-card .btn-login { min-height: 46px; font-weight: 700; border-radius: 0.5rem; }
        .author-login-meta { display: flex; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: 1rem; }
    </style>
</head>
<body>
<div class="author-login-wrap">
    <div class="card card-primary author-login-card">
        <div class="card-header">
            <h4>Author Login</h4>
            <p class="subtitle">Sign in to manage your BrokersCourt posts</p>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="author-login-alert author-login-alert--success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="author-login-alert author-login-alert--error">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('author_login_submit') }}" novalidate>
                @csrf

                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email"
                           id="email"
                           name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}"
                           placeholder="author@example.com"
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

                <div class="author-login-meta">
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
