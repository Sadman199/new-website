@extends('front.layout.app')

@section('title', 'Forgot Password | BrokersCourt')
@section('meta_description', 'Reset your BrokersCourt account password.')
@section('robots', 'noindex, nofollow')
@section('canonical', route('user.password.request'))

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/user-account.css') }}?v=11">
@endpush

@section('main_content')
<div class="ua-root">
    <div class="container">
    <div class="ua-wrap ua-wrap--narrow">
        <div class="ua-auth-card">
            <div class="ua-auth-head">
                <div class="ua-auth-icon"><i class="fas fa-key" aria-hidden="true"></i></div>
                <h1 class="ua-auth-title">Forgot your password?</h1>
                <p class="ua-auth-sub">Enter the email on your account and we will send a reset link.</p>
            </div>

            @include('front.account._alerts')

            <form action="{{ route('user.password.email') }}" method="POST" class="ua-form" novalidate>
                @csrf

                <div class="ua-field">
                    <label for="email">Email address</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                        class="ua-input @error('email') is-error @enderror" placeholder="you@example.com">
                    @error('email')<p class="ua-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>

                <button type="submit" class="ua-btn ua-btn--primary ua-btn--block">
                    <i class="fas fa-paper-plane" aria-hidden="true"></i> Send reset link
                </button>
            </form>

            <p class="ua-footnote">
                Remembered it?
                <a href="{{ route('user.login') }}" class="ua-link">Back to log in</a>
            </p>
        </div>
    </div>
    </div>
</div>
@endsection
