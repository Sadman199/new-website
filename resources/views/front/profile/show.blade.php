@extends('front.layout.app')

@section('title', $user->name . ' | My Profile | BrokersCourt')
@section('meta_description', 'Manage your BrokersCourt profile, reviews and account activity.')

@section('main_content')
<section class="bg-gray-50 min-h-[80vh] pt-24 pb-16 px-4" x-data="{ tab: 'overview' }">
    <div class="max-w-5xl mx-auto">

        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg px-4 py-3">
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3">
                <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
            </div>
        @endif

        <!-- Header card -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-6">
            <div class="h-24 bg-gradient-to-r from-yellow-400 to-amber-500"></div>
            <div class="px-6 pb-6">
                <div class="flex flex-col sm:flex-row sm:items-end gap-4 -mt-10">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                        class="w-24 h-24 rounded-2xl border-4 border-white shadow object-cover bg-white">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                            @if($user->is_verified)
                                <span class="inline-flex items-center gap-1 bg-blue-100 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                    <i class="fas fa-check-circle"></i> Verified
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 bg-amber-100 text-amber-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                    <i class="fas fa-clock"></i> Pending verification
                                </span>
                            @endif
                        </div>
                        <p class="text-gray-500 text-sm mt-1">
                            <i class="fas fa-envelope mr-1"></i> {{ $user->email }}
                            @if($user->country)<span class="mx-2 text-gray-300">|</span><i class="fas fa-map-marker-alt mr-1"></i> {{ $user->country }}@endif
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('user.profile.edit') }}" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold px-4 py-2 rounded-lg transition">
                            <i class="fas fa-pen"></i> Edit profile
                        </a>
                        <form action="{{ route('user.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 bg-red-50 hover:bg-red-100 text-red-600 text-sm font-semibold px-4 py-2 rounded-lg transition">
                                <i class="fas fa-sign-out-alt"></i> Log out
                            </button>
                        </form>
                    </div>
                </div>

                @if($user->bio)
                    <p class="text-gray-600 text-sm mt-4 max-w-2xl">{{ $user->bio }}</p>
                @endif
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl border border-gray-200 p-4 text-center">
                <p class="text-2xl font-bold text-gray-900">{{ $stats['reviews_total'] }}</p>
                <p class="text-xs text-gray-500 mt-1">Total reviews</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4 text-center">
                <p class="text-2xl font-bold text-green-600">{{ $stats['reviews_approved'] }}</p>
                <p class="text-xs text-gray-500 mt-1">Approved</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4 text-center">
                <p class="text-2xl font-bold text-amber-500">{{ $stats['reviews_pending'] }}</p>
                <p class="text-xs text-gray-500 mt-1">Pending</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4 text-center">
                <p class="text-sm font-bold text-gray-900 mt-1">{{ $user->created_at->format('M Y') }}</p>
                <p class="text-xs text-gray-500 mt-1">Member since</p>
            </div>
        </div>

        <!-- Tabs -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
            <div class="flex border-b border-gray-200 overflow-x-auto">
                <button @click="tab = 'overview'" :class="tab === 'overview' ? 'text-yellow-600 border-yellow-500' : 'text-gray-500 border-transparent'" class="px-5 py-3 text-sm font-semibold border-b-2 transition whitespace-nowrap">
                    <i class="fas fa-star mr-1"></i> My Reviews
                </button>
                <button @click="tab = 'activity'" :class="tab === 'activity' ? 'text-yellow-600 border-yellow-500' : 'text-gray-500 border-transparent'" class="px-5 py-3 text-sm font-semibold border-b-2 transition whitespace-nowrap">
                    <i class="fas fa-history mr-1"></i> Activity
                </button>
                <button @click="tab = 'security'" :class="tab === 'security' ? 'text-yellow-600 border-yellow-500' : 'text-gray-500 border-transparent'" class="px-5 py-3 text-sm font-semibold border-b-2 transition whitespace-nowrap">
                    <i class="fas fa-lock mr-1"></i> Security
                </button>
            </div>

            <!-- My Reviews -->
            <div x-show="tab === 'overview'" class="p-6">
                @forelse($reviews as $review)
                    <div class="flex items-start gap-4 py-4 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                        <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-building text-gray-400"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2">
                                <h3 class="font-semibold text-gray-800 truncate">{{ $review->broker->name ?? 'Broker' }}</h3>
                                @if($review->status == 1)
                                    <span class="bg-green-100 text-green-700 text-xs font-semibold px-2 py-0.5 rounded-full">Approved</span>
                                @elseif($review->status == 0)
                                    <span class="bg-amber-100 text-amber-700 text-xs font-semibold px-2 py-0.5 rounded-full">Pending</span>
                                @else
                                    <span class="bg-red-100 text-red-700 text-xs font-semibold px-2 py-0.5 rounded-full">Declined</span>
                                @endif
                            </div>
                            <div class="text-xs text-yellow-500 my-1">
                                @for($i = 1; $i <= 5; $i++)<i class="fas fa-star {{ $i <= $review->rating ? '' : 'text-gray-200' }}"></i>@endfor
                                <span class="text-gray-400 ml-1">{{ $review->created_at->format('M d, Y') }}</span>
                            </div>
                            <p class="text-sm text-gray-600">{{ $review->description }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10">
                        <i class="fas fa-comment-slash text-3xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500 text-sm">You haven't written any reviews yet.</p>
                        <a href="{{ route('all_brokers') }}" class="inline-block mt-3 text-yellow-600 font-semibold text-sm">Browse brokers to review →</a>
                    </div>
                @endforelse
            </div>

            <!-- Activity -->
            <div x-show="tab === 'activity'" class="p-6" style="display:none;">
                @forelse($activities as $activity)
                    <div class="flex items-start gap-3 py-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                        <div class="w-8 h-8 rounded-full bg-yellow-50 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-circle text-[6px] text-yellow-500"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-700 font-medium">{{ $activity->label }}</p>
                            @if($activity->description)<p class="text-xs text-gray-500">{{ $activity->description }}</p>@endif
                            <p class="text-xs text-gray-400 mt-0.5">
                                {{ $activity->created_at->diffForHumans() }}
                                @if($activity->ip_address)<span class="mx-1">·</span>{{ $activity->ip_address }}@endif
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 text-sm py-10">No activity recorded yet.</p>
                @endforelse
            </div>

            <!-- Security -->
            <div x-show="tab === 'security'" class="p-6 max-w-md" style="display:none;">
                <h3 class="font-semibold text-gray-800 mb-4">Change password</h3>
                <form action="{{ route('user.profile.password') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Current password</label>
                        <input type="password" name="current_password" required class="w-full bg-gray-50 border border-gray-300 rounded-lg py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                        @error('current_password')<div class="text-sm text-red-500 mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">New password</label>
                        <input type="password" name="password" required class="w-full bg-gray-50 border border-gray-300 rounded-lg py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                        @error('password')<div class="text-sm text-red-500 mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm new password</label>
                        <input type="password" name="password_confirmation" required class="w-full bg-gray-50 border border-gray-300 rounded-lg py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                    </div>
                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-5 py-2.5 rounded-lg transition">
                        <i class="fas fa-key mr-1"></i> Update password
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
