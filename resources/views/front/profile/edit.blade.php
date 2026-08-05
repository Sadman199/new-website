@extends('front.layout.app')

@section('title', 'Edit Profile | BrokersCourt')

@section('main_content')
<section class="bg-gray-50 min-h-[80vh] pt-24 pb-16 px-4">
    <div class="max-w-2xl mx-auto">
        <div class="flex items-center gap-2 mb-6">
            <a href="{{ route('user.profile') }}" class="text-gray-500 hover:text-gray-700"><i class="fas fa-arrow-left"></i></a>
            <h1 class="text-2xl font-bold text-gray-900">Edit profile</h1>
        </div>

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3">
                <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="flex items-center gap-4">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-xl object-cover border border-gray-200 bg-white">
                    <div>
                        <label for="avatar" class="block text-sm font-medium text-gray-700 mb-1">Profile photo</label>
                        <input type="file" name="avatar" id="avatar" accept="image/*" class="text-sm text-gray-600">
                        @error('avatar')<div class="text-sm text-red-500 mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                        class="w-full bg-gray-50 border border-gray-300 rounded-lg py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                    @error('name')<div class="text-sm text-red-500 mt-1">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" value="{{ $user->email }}" disabled
                        class="w-full bg-gray-100 border border-gray-200 rounded-lg py-2.5 px-3 text-gray-500 cursor-not-allowed">
                    <p class="text-xs text-gray-400 mt-1">Email cannot be changed here. Contact support to update it.</p>
                </div>

                <div>
                    <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                    <input type="text" name="country" id="country" value="{{ old('country', $user->country) }}"
                        class="w-full bg-gray-50 border border-gray-300 rounded-lg py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                    @error('country')<div class="text-sm text-red-500 mt-1">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">Bio</label>
                    <textarea name="bio" id="bio" rows="4" maxlength="1000"
                        class="w-full bg-gray-50 border border-gray-300 rounded-lg py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-yellow-400"
                        placeholder="Tell other traders a little about yourself...">{{ old('bio', $user->bio) }}</textarea>
                    @error('bio')<div class="text-sm text-red-500 mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-6 py-2.5 rounded-lg transition">
                        <i class="fas fa-save mr-1"></i> Save changes
                    </button>
                    <a href="{{ route('user.profile') }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
