@extends('front.layout.app')

@section('title', 'Create Account | BrokersCourt')
@section('meta_description', 'Create a free BrokersCourt account to write verified broker reviews, track your activity and build your trader profile.')
@section('robots', 'noindex, nofollow')
@section('canonical', route('user.register'))

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/user-account.css') }}?v=11">
@endpush

@section('main_content')
<div class="ua-root">
    <div class="container">
    <div class="ua-auth-shell">
        <aside class="ua-auth-aside">
            <p class="ua-auth-aside__eyebrow">
                <i class="fas fa-user-plus" aria-hidden="true"></i>
                Free account
            </p>
            <h2 class="ua-auth-aside__title">Join traders who <span>review brokers</span></h2>
            <p class="ua-auth-aside__lead">
                Create a free account to rate the brokers you trade with, build your trader profile, and help others avoid bad ones.
            </p>
            <ul class="ua-auth-points">
                <li>
                    <i class="fas fa-edit" aria-hidden="true"></i>
                    <span><strong>Write broker reviews</strong>Rate execution, fees, and withdrawals from experience.</span>
                </li>
                <li>
                    <i class="fas fa-chart-line" aria-hidden="true"></i>
                    <span><strong>Track your activity</strong>Every review and comparison stays on your profile.</span>
                </li>
                <li>
                    <i class="fas fa-lock" aria-hidden="true"></i>
                    <span><strong>Free and private</strong>No trading account needed, no data sold to brokers.</span>
                </li>
            </ul>
        </aside>

        <div class="ua-auth-card">
            <div class="ua-auth-head">
                <div class="ua-auth-icon"><i class="fas fa-user-plus" aria-hidden="true"></i></div>
                <h1 class="ua-auth-title">Create your account</h1>
                <p class="ua-auth-sub">Join BrokersCourt to review brokers and track your activity.</p>
            </div>

            @include('front.account._alerts')

            @include('front.auth.partials.google_button', ['label' => 'Sign up with Google'])

            @include('front.auth.partials.auth_divider')

            <form action="{{ route('user.register.submit') }}" method="POST" class="ua-form" novalidate>
                @csrf

                <div class="ua-field">
                    <label for="name">Full name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                        class="ua-input @error('name') is-error @enderror" placeholder="Your name">
                    @error('name')<p class="ua-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>

                <div class="ua-field">
                    <label for="email">Email address</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autocomplete="email"
                        class="ua-input @error('email') is-error @enderror" placeholder="you@example.com">
                    @error('email')<p class="ua-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>

                <div class="ua-field">
                    <label for="country">Country <span class="ua-optional">(optional)</span></label>
                    <input type="text" name="country" id="country" value="{{ old('country') }}" autocomplete="country-name"
                        class="ua-input @error('country') is-error @enderror" placeholder="e.g. United States">
                    @error('country')<p class="ua-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>

                <div class="ua-field">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required autocomplete="new-password"
                        class="ua-input @error('password') is-error @enderror" placeholder="At least 8 characters">
                    @error('password')<p class="ua-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                    <p class="ua-hint">Use at least 8 characters with a mix of letters and numbers.</p>
                </div>

                <div class="ua-field">
                    <label for="password_confirmation">Confirm password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password"
                        class="ua-input" placeholder="Repeat your password">
                </div>

                <button type="submit" class="ua-btn ua-btn--primary ua-btn--block">
                    <i class="fas fa-user-plus" aria-hidden="true"></i> Create account
                </button>
            </form>

            <p class="ua-footnote">
                Already have an account?
                <a href="{{ route('user.login') }}" class="ua-link">Log in</a>
            </p>
        </div>
    </div>
    </div>
</div>
@include('front.auth.partials.google_scripts')
@endsection
