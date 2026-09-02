@php
    $topic = $topic ?? new \App\Models\BrokerGuideTopic(['is_active' => true]);
    $isEdit = $topic->exists;
    $contextProfiles = $contextProfiles ?? \App\Models\BrokerGuideTopic::contextProfileOptions();
@endphp

<div class="ab-form">
    <section class="ab-section" id="content">
        <div class="ab-section__head">
            <h2>1. Content</h2>
            <p>Title, URL slug, and the default summary copied onto new broker guides.</p>
        </div>
        <div class="ab-section__body">
            <div class="form-group">
                <label for="title">Title <span class="text-danger">*</span></label>
                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $topic->title) }}" required>
                @error('title')<small class="ab-error">{{ $message }}</small>@enderror
            </div>

            <div class="form-group">
                <label for="slug">URL slug</label>
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text">/guides/</span></div>
                    <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $topic->slug) }}" placeholder="auto from title" pattern="[a-z0-9]+(?:-[a-z0-9]+)*" @if($isEdit) data-autogen="off" @endif>
                </div>
                <small class="text-muted">Used in broker guide URLs. Lowercase letters, numbers, and hyphens only.</small>
                @error('slug')<small class="ab-error">{{ $message }}</small>@enderror
            </div>

            <div class="form-group mb-0">
                <label for="default_summary">Default summary</label>
                <textarea name="default_summary" id="default_summary" class="form-control" rows="3">{{ old('default_summary', $topic->default_summary) }}</textarea>
                <small class="text-muted">Pre-filled on new broker guides and shown on review page cards.</small>
            </div>
        </div>
    </section>

    <section class="ab-section" id="display">
        <div class="ab-section__head">
            <h2>2. Display &amp; sync</h2>
            <p>Icon, order, context block, and whether this topic is pushed to every broker.</p>
        </div>
        <div class="ab-section__body">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="icon">Icon (Font Awesome)</label>
                    <input type="text" name="icon" id="icon" class="form-control" value="{{ old('icon', $topic->icon) }}" placeholder="fas fa-wallet">
                </div>
                <div class="col-md-6 form-group">
                    <label for="sort_order">Sort order</label>
                    <input type="number" name="sort_order" id="sort_order" class="form-control" min="0" max="999" value="{{ old('sort_order', $topic->sort_order ?? 0) }}">
                </div>
                <div class="col-12 form-group">
                    <label for="context_profile">Auto context block</label>
                    <select name="context_profile" id="context_profile" class="form-control">
                        @foreach($contextProfiles as $value => $label)
                            <option value="{{ $value }}" @selected(old('context_profile', $topic->context_profile ?? '') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="hidden" name="requires_swap_free" value="0">
                        <input type="checkbox" name="requires_swap_free" value="1" class="custom-control-input" id="requires_swap_free" @checked(old('requires_swap_free', $topic->requires_swap_free))>
                        <label class="custom-control-label" for="requires_swap_free">Only show when broker has swap-free accounts</label>
                    </div>
                </div>
                <div class="col-md-6 form-group mb-0">
                    <div class="custom-control custom-checkbox">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" class="custom-control-input" id="is_active" @checked(old('is_active', $topic->is_active ?? true))>
                        <label class="custom-control-label" for="is_active">Active — sync to all brokers</label>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="ab-save">
    <div class="ab-header__actions">
        <button type="submit" class="ab-btn ab-btn--primary">
            <i class="fas fa-save" aria-hidden="true"></i>
            {{ $isEdit ? 'Save topic' : 'Create topic' }}
        </button>
    </div>
    <a href="{{ route('admin_broker_guide_topics_index') }}" class="ab-btn ab-btn--ghost">Cancel</a>
</div>
