@extends('front.layout.app')

@section('title', 'Reset Password | BrokersCourt')
@section('meta_description', 'Choose a new password for your BrokersCourt account.')
@section('robots', 'noindex, nofollow')
@section('canonical', route('user.password.reset', ['token' => $token]))

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/user-account.css') }}?v=4">
@endpush

@section('main_content')
<div class="ua-root">
    <div class="ua-wrap ua-wrap--narrow">
        <div class="ua-auth-card">
            <div class="ua-auth-head">
                <div class="ua-auth-icon"><i class="fas fa-lock" aria-hidden="true"></i></div>
                <h1 class="ua-auth-title">Set a new password</h1>
                <p class="ua-auth-sub">Choose a strong password for your BrokersCourt account.</p>
            </div>

            @include('front.account._alerts')

            <form action="{{ route('user.password.update') }}" method="POST" class="ua-form" novalidate>
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="ua-field">
                    <label for="email">Email address</label>
                    <input type="email" name="email" id="email" value="{{ $email }}" required autocomplete="email"
                        class="ua-input @error('email') is-error @enderror" placeholder="you@example.com">
                    @error('email')<p class="ua-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>

                <div class="ua-field">
                    <label for="password">New password</label>
                    <input type="password" name="password" id="password" required autocomplete="new-password"
                        class="ua-input @error('password') is-error @enderror" placeholder="At least 8 characters">
                    @error('password')<p class="ua-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                    <p class="ua-hint">Use at least 8 characters with a mix of letters and numbers.</p>
                </div>

                <div class="ua-field">
                    <label for="password_confirmation">Confirm new password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password"
                        class="ua-input" placeholder="Repeat your password">
                </div>

                <button type="submit" class="ua-btn ua-btn--primary ua-btn--block">
                    <i class="fas fa-check" aria-hidden="true"></i> Update password
                </button>
            </form>

            <p class="ua-footnote">
                <a href="{{ route('user.login') }}" class="ua-link">Back to log in</a>
            </p>
        </div>
    </div>
</div>
@endsection
