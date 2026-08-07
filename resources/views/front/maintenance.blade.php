@extends('front.layout.app')

@section('title', 'Maintenance | ' . ($siteName ?? 'BrokersCourt'))

@section('main_content')
<div class="ua-root">
    <div class="ua-wrap ua-wrap--narrow">
        <div class="ua-auth-card" style="text-align:center;">
            <div class="ua-auth-icon"><i class="fas fa-tools" aria-hidden="true"></i></div>
            <h1 class="ua-auth-title">We&rsquo;ll be back soon</h1>
            <p class="ua-auth-sub">{{ $message }}</p>
        </div>
    </div>
</div>
@endsection

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/user-account.css') }}?v=3">
@endpush
