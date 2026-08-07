@extends('front.layout.app')

@section('title', 'Log In | BrokersCourt')
@section('meta_description', 'Log in to your BrokersCourt account to write broker reviews, track your activity and manage your profile.')

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/user-account.css') }}?v=3">
@endpush

@section('main_content')
<div class="ua-root">
    <div class="ua-wrap ua-wrap--narrow">
        <div class="ua-auth-card">
            <div class="ua-auth-head">
                <div class="ua-auth-icon"><i class="fas fa-sign-in-alt" aria-hidden="true"></i></div>
                <h1 class="ua-auth-title">Welcome back</h1>
                <p class="ua-auth-sub">Log in to manage your reviews and profile.</p>
            </div>

            @include('front.account._alerts')

            @include('front.auth.partials.google_button', ['label' => 'Login with Google'])

            @include('front.auth.partials.auth_divider')

            <form action="{{ route('user.login.submit') }}" method="POST" class="ua-form" novalidate>
                @csrf

                <div class="ua-field">
                    <label for="email">Email address</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                        class="ua-input @error('email') is-error @enderror" placeholder="you@example.com">
                    @error('email')<p class="ua-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>

                <div class="ua-field">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required autocomplete="current-password"
                        class="ua-input @error('password') is-error @enderror" placeholder="Enter your password">
                    @error('password')<p class="ua-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>

                <label class="ua-check">
                    <input type="checkbox" name="remember" @checked(old('remember'))>
                    Remember me on this device
                </label>

                <button type="submit" class="ua-btn ua-btn--primary ua-btn--block">
                    <i class="fas fa-sign-in-alt" aria-hidden="true"></i> Log in
                </button>
            </form>

            <p class="ua-footnote">
                Don't have an account?
                <a href="{{ route('user.register') }}" class="ua-link">Create one free</a>
            </p>
        </div>
    </div>
</div>
@include('front.auth.partials.google_scripts')
@endsection
