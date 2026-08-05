@extends('front.layout.app')

@section('title', 'Log In | BrokersCourt')
@section('meta_description', 'Log in to your BrokersCourt account to write broker reviews, track your activity and manage your profile.')

@section('main_content')
<section class="min-h-[80vh] flex items-center justify-center bg-gradient-to-br from-gray-50 via-white to-gray-50 px-4 py-16 mt-16">
    <div class="w-full max-w-md">
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-8">
            <div class="text-center mb-8">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-yellow-100 flex items-center justify-center mb-4">
                    <i class="fas fa-user-circle text-2xl text-yellow-500"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">Welcome back</h1>
                <p class="text-gray-500 text-sm mt-1">Log in to manage your reviews and profile.</p>
            </div>

            @if(session('error'))
                <div class="mb-5 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3">
                    <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
                </div>
            @endif
            @if(session('success'))
                <div class="mb-5 bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg px-4 py-3">
                    <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('user.login.submit') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                        class="w-full bg-gray-50 border border-gray-300 rounded-lg py-2.5 px-3 text-gray-800 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent">
                    @error('email')<div class="text-sm text-red-500 mt-1">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" id="password" required
                        class="w-full bg-gray-50 border border-gray-300 rounded-lg py-2.5 px-3 text-gray-800 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent">
                    @error('password')<div class="text-sm text-red-500 mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center text-sm text-gray-600">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-yellow-500 focus:ring-yellow-400 mr-2">
                        Remember me
                    </label>
                </div>

                <button type="submit"
                    class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2.5 rounded-lg transition flex items-center justify-center">
                    <i class="fas fa-sign-in-alt mr-2"></i> Log in
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-6">
                Don't have an account?
                <a href="{{ route('user.register') }}" class="text-yellow-600 font-semibold hover:text-yellow-700">Sign up</a>
            </p>
        </div>
    </div>
</section>
@endsection
