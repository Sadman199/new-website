@php
    $isEdit = $author->exists;
@endphp

<section class="ab-section">
    <div class="ab-section__head">
        <h2>Profile</h2>
        <p>Name, login email, photo, and a short bio shown on author pages.</p>
    </div>
    <div class="ab-section__body">
        <div class="row">
            <div class="col-md-6 form-group">
                @include('admin.partials._image_upload_preview', [
                    'inputId' => 'photo',
                    'previewId' => 'author_photo_preview',
                    'label' => 'Photo',
                    'required' => false,
                    'currentUrl' => $isEdit ? $author->photoUrl() : null,
                    'hint' => 'JPG, PNG, WEBP, GIF. Leave empty on edit to keep the current photo.',
                ])
            </div>
            <div class="col-md-6 form-group">
                <label for="name">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" required
                       value="{{ old('name', $author->name) }}" placeholder="Full name">
                @error('name')<small class="ab-error">{{ $message }}</small>@enderror
            </div>
            <div class="col-md-6 form-group">
                <label for="email">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" required
                       value="{{ old('email', $author->email) }}" placeholder="name@example.com">
                @error('email')<small class="ab-error">{{ $message }}</small>@enderror
            </div>
            <div class="col-md-6 form-group">
                <label for="password">Password @unless($isEdit)<span class="text-danger">*</span>@endunless</label>
                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror"
                       @unless($isEdit) required @endunless
                       placeholder="{{ $isEdit ? 'Leave blank to keep current' : 'At least 6 characters' }}">
                @error('password')<small class="ab-error">{{ $message }}</small>@enderror
            </div>
            <div class="col-md-6 form-group">
                <label for="retype_password">Retype password @unless($isEdit)<span class="text-danger">*</span>@endunless</label>
                <input type="password" name="retype_password" id="retype_password" class="form-control @error('retype_password') is-invalid @enderror"
                       @unless($isEdit) required @endunless>
                @error('retype_password')<small class="ab-error">{{ $message }}</small>@enderror
            </div>
            <div class="col-md-12 form-group mb-0">
                <label for="bio">Bio</label>
                <textarea name="bio" id="bio" class="form-control snote" data-admin-editor="compact" rows="4" placeholder="Short author bio">{{ old('bio', $author->bio) }}</textarea>
            </div>
        </div>
    </div>
</section>

<section class="ab-section">
    <div class="ab-section__head">
        <h2>Social links</h2>
        <p>Optional profiles shown on the public author page.</p>
    </div>
    <div class="ab-section__body">
        <div class="row">
            <div class="col-md-4 form-group mb-md-0">
                <label for="twitter_url">Twitter URL</label>
                <input type="url" name="twitter_url" id="twitter_url" class="form-control @error('twitter_url') is-invalid @enderror"
                       value="{{ old('twitter_url', $author->twitter_url) }}" placeholder="https://">
                @error('twitter_url')<small class="ab-error">{{ $message }}</small>@enderror
            </div>
            <div class="col-md-4 form-group mb-md-0">
                <label for="linkedin_url">LinkedIn URL</label>
                <input type="url" name="linkedin_url" id="linkedin_url" class="form-control @error('linkedin_url') is-invalid @enderror"
                       value="{{ old('linkedin_url', $author->linkedin_url) }}" placeholder="https://">
                @error('linkedin_url')<small class="ab-error">{{ $message }}</small>@enderror
            </div>
            <div class="col-md-4 form-group mb-0">
                <label for="facebook_url">Facebook URL</label>
                <input type="url" name="facebook_url" id="facebook_url" class="form-control @error('facebook_url') is-invalid @enderror"
                       value="{{ old('facebook_url', $author->facebook_url) }}" placeholder="https://">
                @error('facebook_url')<small class="ab-error">{{ $message }}</small>@enderror
            </div>
        </div>
    </div>
</section>

<section class="ab-section">
    <div class="ab-section__head">
        <h2>Editorial roles</h2>
        <p>Select which credits this author can receive on published content.</p>
    </div>
    <div class="ab-section__body">
        @php
            $roleFields = [
                'can_write' => ['label' => 'Written', 'help' => 'Can be credited as the writer on posts.'],
                'can_edit' => ['label' => 'Edited', 'help' => 'Can be credited as the editor on posts.'],
                'can_fact_check' => ['label' => 'Fact-Checked', 'help' => 'Can be credited as the fact-checker on posts.'],
            ];
        @endphp
        <div class="row">
            @foreach($roleFields as $field => $meta)
                <div class="col-md-4">
                    <label class="ab-check">
                        <input type="hidden" name="{{ $field }}" value="0">
                        <input type="checkbox" name="{{ $field }}" id="{{ $field }}" value="1"
                               @checked(old($field, $author->{$field} ?? ($field === 'can_write')))>
                        <span>
                            {{ $meta['label'] }}
                            <small>{{ $meta['help'] }}</small>
                        </span>
                    </label>
                </div>
            @endforeach
        </div>
    </div>
</section>
