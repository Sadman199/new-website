@extends('front.layout.app')

@section('title', 'Edit Profile | BrokersCourt')
@section('meta_description', 'Update your BrokersCourt profile information, photo, and bio.')
@section('robots', 'noindex, nofollow')
@section('canonical', route('user.profile.edit'))

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/user-account.css') }}?v=11">
@endpush

@section('main_content')
<div class="ua-root">
    <div class="container">
    <div class="ua-wrap ua-wrap--profile">
        <a href="{{ route('user.profile') }}" class="ua-back"><i class="fas fa-arrow-left"></i> Back to profile</a>

        <div class="ua-panel">
            <div class="ua-panel__head">
                <h1 class="ua-panel__title">Edit profile</h1>
                <p class="ua-panel__sub">Update your public profile and account details.</p>
            </div>
            <div class="ua-panel__body">
                @include('front.account._alerts')

                <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data" class="ua-form">
                    @csrf
                    @method('PUT')

                    <div class="ua-field">
                        <label>Profile photo</label>
                        <div class="d-flex flex-wrap align-items-center gap-3">
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="ua-profile-avatar" style="margin:0;width:4rem;height:4rem;">
                            <input type="file" name="avatar" id="avatar" accept="image/jpeg,image/png,image/webp,image/gif" class="ua-file">
                        </div>
                        @error('avatar')<p class="ua-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                        <p class="ua-hint">JPG, PNG, WebP or GIF. Max 2 MB.</p>
                    </div>

                    <div class="ua-field">
                        <label for="name">Full name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required autocomplete="name"
                            class="ua-input @error('name') is-error @enderror">
                        @error('name')<p class="ua-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                    </div>

                    <div class="ua-field">
                        <label for="email">Email</label>
                        <input type="email" id="email" value="{{ $user->email }}" disabled class="ua-input">
                        <p class="ua-hint">Email cannot be changed here. Contact support to update it.</p>
                    </div>

                    <div class="ua-field">
                        <label for="country">Country</label>
                        <input type="text" name="country" id="country" value="{{ old('country', $user->country) }}" autocomplete="country-name"
                            class="ua-input @error('country') is-error @enderror" placeholder="e.g. United Kingdom">
                        @error('country')<p class="ua-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                    </div>

                    <div class="ua-field">
                        <label for="bio">Bio</label>
                        <textarea name="bio" id="bio" rows="4" maxlength="1000" class="ua-textarea @error('bio') is-error @enderror"
                            placeholder="Tell other traders a little about yourself…">{{ old('bio', $user->bio) }}</textarea>
                        @error('bio')<p class="ua-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                    </div>

                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <button type="submit" class="ua-btn ua-btn--primary">
                            <i class="fas fa-save"></i> Save changes
                        </button>
                        <a href="{{ route('user.profile') }}" class="ua-link">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection
