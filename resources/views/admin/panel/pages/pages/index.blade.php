@php
    $activePageTab = $activePageTab ?? 'about';
@endphp

<x-admin.page-header
    title="Static Pages"
    description='Table: <code>pages</code> — one row per language_id with all page content'
>
    <x-slot:actions>
        <select class="form-control-bc" style="max-width:180px" name="language_id">
            @foreach($languages ?? [] as $language)
                <option value="{{ $language->id }}" @selected(($page->language_id ?? 1) == $language->id)>
                    language_id: {{ $language->id }} — {{ $language->name }}
                </option>
            @endforeach
            @if(empty($languages))
                <option>language_id: {{ $page->language_id ?? 1 }} — English</option>
            @endif
        </select>
    </x-slot:actions>
</x-admin.page-header>

<div class="bc-card">
    <form action="{{ route('admin_page_about_update') }}" method="POST">
        @csrf

        <div class="bc-tabs" data-tab-group="pages">
            <button type="button" class="bc-tab {{ $activePageTab === 'about' ? 'active' : '' }}" data-tab="about">About</button>
            <button type="button" class="bc-tab {{ $activePageTab === 'faq' ? 'active' : '' }}" data-tab="faq">FAQ Page</button>
            <button type="button" class="bc-tab {{ $activePageTab === 'contact' ? 'active' : '' }}" data-tab="contact">Contact</button>
            <button type="button" class="bc-tab {{ $activePageTab === 'terms' ? 'active' : '' }}" data-tab="terms">Terms</button>
            <button type="button" class="bc-tab {{ $activePageTab === 'privacy' ? 'active' : '' }}" data-tab="privacy">Privacy</button>
            <button type="button" class="bc-tab {{ $activePageTab === 'disclaimer' ? 'active' : '' }}" data-tab="disclaimer">Disclaimer</button>
            <button type="button" class="bc-tab {{ $activePageTab === 'login' ? 'active' : '' }}" data-tab="login">Login</button>
        </div>

        <div class="tab-panel {{ $activePageTab === 'about' ? 'active' : '' }}" data-tab-panel="pages" data-tab-id="about">
            <div class="form-group">
                <label for="about_title">about_title</label>
                <input class="form-control-bc" type="text" name="about_title" id="about_title" value="{{ old('about_title', $page->about_title ?? '') }}">
            </div>
            <div class="form-group">
                <label for="about_detail">about_detail</label>
                <textarea class="form-control-bc" name="about_detail" id="about_detail" rows="5">{{ old('about_detail', $page->about_detail ?? '') }}</textarea>
            </div>
            <div class="form-group">
                <label for="about_status">about_status</label>
                <select class="form-control-bc" name="about_status" id="about_status">
                    <option value="Show" @selected(old('about_status', $page->about_status ?? '') === 'Show')>Show</option>
                    <option value="Hide" @selected(old('about_status', $page->about_status ?? '') === 'Hide')>Hide</option>
                </select>
            </div>
        </div>

        <div class="tab-panel {{ $activePageTab === 'faq' ? 'active' : '' }}" data-tab-panel="pages" data-tab-id="faq">
            <div class="form-group">
                <label for="faq_title">faq_title</label>
                <input class="form-control-bc" type="text" name="faq_title" id="faq_title" value="{{ old('faq_title', $page->faq_title ?? '') }}">
            </div>
            <div class="form-group">
                <label for="faq_detail">faq_detail</label>
                <textarea class="form-control-bc" name="faq_detail" id="faq_detail" rows="4">{{ old('faq_detail', $page->faq_detail ?? '') }}</textarea>
            </div>
            <div class="form-group">
                <label for="faq_status">faq_status</label>
                <select class="form-control-bc" name="faq_status" id="faq_status">
                    <option value="Show" @selected(old('faq_status', $page->faq_status ?? '') === 'Show')>Show</option>
                    <option value="Hide" @selected(old('faq_status', $page->faq_status ?? '') === 'Hide')>Hide</option>
                </select>
            </div>
        </div>

        <div class="tab-panel {{ $activePageTab === 'contact' ? 'active' : '' }}" data-tab-panel="pages" data-tab-id="contact">
            <div class="form-group">
                <label for="contact_title">contact_title</label>
                <input class="form-control-bc" type="text" name="contact_title" id="contact_title" value="{{ old('contact_title', $page->contact_title ?? '') }}">
            </div>
            <div class="form-group">
                <label for="contact_detail">contact_detail</label>
                <textarea class="form-control-bc" name="contact_detail" id="contact_detail" rows="3">{{ old('contact_detail', $page->contact_detail ?? '') }}</textarea>
            </div>
            <div class="form-group">
                <label for="contact_map">contact_map</label>
                <textarea class="form-control-bc" name="contact_map" id="contact_map" rows="3">{{ old('contact_map', $page->contact_map ?? '') }}</textarea>
            </div>
            <div class="form-group">
                <label for="contact_status">contact_status</label>
                <select class="form-control-bc" name="contact_status" id="contact_status">
                    <option value="Show" @selected(old('contact_status', $page->contact_status ?? '') === 'Show')>Show</option>
                    <option value="Hide" @selected(old('contact_status', $page->contact_status ?? '') === 'Hide')>Hide</option>
                </select>
            </div>
        </div>

        <div class="tab-panel {{ $activePageTab === 'terms' ? 'active' : '' }}" data-tab-panel="pages" data-tab-id="terms">
            <div class="form-group">
                <label for="terms_title">terms_title</label>
                <input class="form-control-bc" type="text" name="terms_title" id="terms_title" value="{{ old('terms_title', $page->terms_title ?? '') }}">
            </div>
            <div class="form-group">
                <label for="terms_detail">terms_detail</label>
                <textarea class="form-control-bc" name="terms_detail" id="terms_detail" rows="5">{{ old('terms_detail', $page->terms_detail ?? '') }}</textarea>
            </div>
            <div class="form-group">
                <label for="terms_status">terms_status</label>
                <select class="form-control-bc" name="terms_status" id="terms_status">
                    <option value="Show" @selected(old('terms_status', $page->terms_status ?? '') === 'Show')>Show</option>
                    <option value="Hide" @selected(old('terms_status', $page->terms_status ?? '') === 'Hide')>Hide</option>
                </select>
            </div>
        </div>

        <div class="tab-panel {{ $activePageTab === 'privacy' ? 'active' : '' }}" data-tab-panel="pages" data-tab-id="privacy">
            <div class="form-group">
                <label for="privacy_title">privacy_title</label>
                <input class="form-control-bc" type="text" name="privacy_title" id="privacy_title" value="{{ old('privacy_title', $page->privacy_title ?? '') }}">
            </div>
            <div class="form-group">
                <label for="privacy_detail">privacy_detail</label>
                <textarea class="form-control-bc" name="privacy_detail" id="privacy_detail" rows="5">{{ old('privacy_detail', $page->privacy_detail ?? '') }}</textarea>
            </div>
            <div class="form-group">
                <label for="privacy_status">privacy_status</label>
                <select class="form-control-bc" name="privacy_status" id="privacy_status">
                    <option value="Show" @selected(old('privacy_status', $page->privacy_status ?? '') === 'Show')>Show</option>
                    <option value="Hide" @selected(old('privacy_status', $page->privacy_status ?? '') === 'Hide')>Hide</option>
                </select>
            </div>
        </div>

        <div class="tab-panel {{ $activePageTab === 'disclaimer' ? 'active' : '' }}" data-tab-panel="pages" data-tab-id="disclaimer">
            <div class="form-group">
                <label for="disclaimer_title">disclaimer_title</label>
                <input class="form-control-bc" type="text" name="disclaimer_title" id="disclaimer_title" value="{{ old('disclaimer_title', $page->disclaimer_title ?? '') }}">
            </div>
            <div class="form-group">
                <label for="disclaimer_detail">disclaimer_detail</label>
                <textarea class="form-control-bc" name="disclaimer_detail" id="disclaimer_detail" rows="5">{{ old('disclaimer_detail', $page->disclaimer_detail ?? '') }}</textarea>
            </div>
            <div class="form-group">
                <label for="disclaimer_status">disclaimer_status</label>
                <select class="form-control-bc" name="disclaimer_status" id="disclaimer_status">
                    <option value="Show" @selected(old('disclaimer_status', $page->disclaimer_status ?? '') === 'Show')>Show</option>
                    <option value="Hide" @selected(old('disclaimer_status', $page->disclaimer_status ?? '') === 'Hide')>Hide</option>
                </select>
            </div>
        </div>

        <div class="tab-panel {{ $activePageTab === 'login' ? 'active' : '' }}" data-tab-panel="pages" data-tab-id="login">
            <div class="form-group">
                <label for="login_title">login_title</label>
                <input class="form-control-bc" type="text" name="login_title" id="login_title" value="{{ old('login_title', $page->login_title ?? '') }}">
            </div>
            <div class="form-group">
                <label for="login_status">login_status</label>
                <select class="form-control-bc" name="login_status" id="login_status">
                    <option value="Show" @selected(old('login_status', $page->login_status ?? '') === 'Show')>Show</option>
                    <option value="Hide" @selected(old('login_status', $page->login_status ?? '') === 'Hide')>Hide</option>
                </select>
            </div>
        </div>

        <div class="bc-card-body">
            <button class="btn-bc btn-bc-primary" type="submit">
                Save Pages (language_id: {{ $page->language_id ?? 1 }})
            </button>
        </div>
    </form>
</div>
