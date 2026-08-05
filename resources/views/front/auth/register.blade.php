@extends('front.layout.app')

@section('title', 'Create Account | BrokersCourt')
@section('meta_description', 'Create a free BrokersCourt account to write verified broker reviews, track your activity and build your trader profile.')

@section('main_content')
<section class="min-h-[80vh] flex items-center justify-center bg-gradient-to-br from-gray-50 via-white to-gray-50 px-4 py-16 mt-16">
    <div class="w-full max-w-md">
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-8">
            <div class="text-center mb-8">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-yellow-100 flex items-center justify-center mb-4">
                    <i class="fas fa-user-plus text-2xl text-yellow-500"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">Create your account</h1>
                <p class="text-gray-500 text-sm mt-1">Join BrokersCourt to review brokers and track your activity.</p>
            </div>

            @if(session('error'))
                <div class="mb-5 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3">
                    <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('user.register.submit') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                        class="w-full bg-gray-50 border border-gray-300 rounded-lg py-2.5 px-3 text-gray-800 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent">
                    @error('name')<div class="text-sm text-red-500 mt-1">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                        class="w-full bg-gray-50 border border-gray-300 rounded-lg py-2.5 px-3 text-gray-800 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent">
                    @error('email')<div class="text-sm text-red-500 mt-1">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country <span class="text-gray-400 font-normal">(optional)</span></label>
                    <input type="text" name="country" id="country" value="{{ old('country') }}"
                        class="w-full bg-gray-50 border border-gray-300 rounded-lg py-2.5 px-3 text-gray-800 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent">
                    @error('country')<div class="text-sm text-red-500 mt-1">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" id="password" required
                        class="w-full bg-gray-50 border border-gray-300 rounded-lg py-2.5 px-3 text-gray-800 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent">
                    @error('password')<div class="text-sm text-red-500 mt-1">{{ $message }}</div>@enderror
                    <p class="text-xs text-gray-400 mt-1">At least 8 characters.</p>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full bg-gray-50 border border-gray-300 rounded-lg py-2.5 px-3 text-gray-800 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent">
                </div>

                <button type="submit"
                    class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2.5 rounded-lg transition flex items-center justify-center">
                    <i class="fas fa-user-plus mr-2"></i> Create account
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-6">
                Already have an account?
                <a href="{{ route('user.login') }}" class="text-yellow-600 font-semibold hover:text-yellow-700">Log in</a>
            </p>
        </div>
    </div>
</section>
@endsection
