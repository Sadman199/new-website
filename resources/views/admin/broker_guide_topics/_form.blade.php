@php
    $topic = $topic ?? new \App\Models\BrokerGuideTopic(['is_active' => true]);
    $contextProfiles = $contextProfiles ?? \App\Models\BrokerGuideTopic::contextProfileOptions();
@endphp

<div class="row">
    <div class="col-lg-8">
        <div class="form-group">
            <label class="font-weight-bold">Title <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $topic->title) }}" required>
        </div>

        <div class="form-group">
            <label class="font-weight-bold">URL slug <span class="text-danger">*</span></label>
            <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text">/guides/</span></div>
                <input type="text" name="slug" class="form-control" value="{{ old('slug', $topic->slug) }}" required pattern="[a-z0-9]+(?:-[a-z0-9]+)*">
            </div>
            <small class="form-text text-muted">Used in broker guide URLs. Lowercase letters, numbers, and hyphens only.</small>
        </div>

        <div class="form-group">
            <label class="font-weight-bold">Default summary</label>
            <textarea name="default_summary" class="form-control" rows="2">{{ old('default_summary', $topic->default_summary) }}</textarea>
            <small class="form-text text-muted">Pre-filled on new broker guides and shown on review page cards.</small>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border">
            <div class="card-body">
                <div class="form-group">
                    <label class="font-weight-bold">Icon (Font Awesome)</label>
                    <input type="text" name="icon" class="form-control" value="{{ old('icon', $topic->icon) }}" placeholder="fas fa-wallet">
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">Sort order</label>
                    <input type="number" name="sort_order" class="form-control" min="0" max="999" value="{{ old('sort_order', $topic->sort_order ?? 0) }}">
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">Auto context block</label>
                    <select name="context_profile" class="form-control">
                        @foreach($contextProfiles as $value => $label)
                            <option value="{{ $value }}" @selected(old('context_profile', $topic->context_profile ?? '') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group form-check">
                    <input type="hidden" name="requires_swap_free" value="0">
                    <input type="checkbox" name="requires_swap_free" value="1" class="form-check-input" id="requires_swap_free" @checked(old('requires_swap_free', $topic->requires_swap_free))>
                    <label class="form-check-label" for="requires_swap_free">Only show when broker has swap-free accounts</label>
                </div>

                <div class="form-group form-check mb-0">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" @checked(old('is_active', $topic->is_active ?? true))>
                    <label class="form-check-label" for="is_active">Active — sync to all brokers</label>
                </div>
            </div>
        </div>
    </div>
</div>
