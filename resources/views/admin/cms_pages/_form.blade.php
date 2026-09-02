@php
    $isEdit = isset($page) && $page->exists;
    $previewUrl = $isEdit && $page->isPublished() ? $page->publicUrl() : null;
    $sectionCount = count($sections ?? []);
@endphp

<div class="cms-admin" id="cms-admin">
    <div class="cms-admin__toolbar">
        <div class="cms-admin__toolbar-meta">
            @if($isEdit)
                <span @class(['cms-admin__status', 'cms-admin__status--live' => $page->isPublished()])>
                    {{ $page->statusLabel() }}
                </span>
                <span class="cms-admin__url">/{{ $page->slug }}</span>
            @else
                <span class="cms-admin__status">New page</span>
            @endif
        </div>
        <div class="cms-admin__toolbar-actions">
            @if($previewUrl)
                <a href="{{ $previewUrl }}" class="ab-btn ab-btn--ghost ab-btn--sm" target="_blank" rel="noopener">
                    <i class="fas fa-external-link-alt" aria-hidden="true"></i>
                    Open live
                </a>
            @endif
            <a href="{{ route('admin_cms_pages_index') }}" class="ab-btn ab-btn--ghost ab-btn--sm">Cancel</a>
            <button type="submit" class="ab-btn ab-btn--primary ab-btn--sm">
                <i class="fas fa-save" aria-hidden="true"></i>
                {{ $isEdit ? 'Save changes' : 'Create Page' }}
            </button>
        </div>
    </div>

    <div class="cms-admin__layout">
        <aside class="cms-admin__sidebar">
            <section class="ab-section">
                <div class="ab-section__head">
                    <h2>Page settings</h2>
                    <p>The title, web address, and whether visitors can see this page.</p>
                </div>
                <div class="ab-section__body">
                    <div class="form-group">
                        <label for="title">Page title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title', $page->title) }}" required placeholder="e.g. Careers">
                        @error('title')<small class="ab-error">{{ $message }}</small>@enderror
                    </div>

                    <div class="form-group">
                        <label for="slug">Page URL <span class="text-danger">*</span></label>
                        <div class="cms-slug">
                            <span class="cms-slug__prefix">/</span>
                            <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror"
                                   value="{{ old('slug', $page->slug) }}" required pattern="[a-z0-9]+(?:-[a-z0-9]+)*"
                                   placeholder="careers" autocomplete="off">
                        </div>
                        @error('slug')<small class="ab-error">{{ $message }}</small>@enderror
                        <small class="text-muted">Filled in automatically from the title. Use lowercase letters, numbers, and hyphens.</small>
                    </div>

                    <div class="form-group">
                        <label for="template">Page layout</label>
                        <select name="template" id="template" class="form-control">
                            @foreach($templates as $key => $label)
                                <option value="{{ $key }}" @selected(old('template', $page->template) === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Default is best for most pages. Landing uses a full-width header. Legal is narrower for policies.</small>
                    </div>

                    <div class="form-group mb-0">
                        <label for="status">Visibility</label>
                        <select name="status" id="status" class="form-control">
                            <option value="draft" @selected(old('status', $page->status) === 'draft')>Draft — only admins can see it</option>
                            <option value="published" @selected(old('status', $page->status) === 'published')>Published — live on the website</option>
                        </select>
                    </div>
                </div>
            </section>

            @if($isEdit)
                <section class="ab-section">
                    <div class="ab-section__body">
                        <dl class="cms-admin__stats">
                            <div><dt>Content blocks</dt><dd id="cms-section-count">{{ $sectionCount }}</dd></div>
                            <div><dt>Last saved</dt><dd>{{ $page->updated_at?->format('M j, Y g:i A') ?? '—' }}</dd></div>
                        </dl>
                    </div>
                </section>
            @endif
        </aside>

        <div class="cms-admin__main">
            <ul class="nav nav-tabs cms-admin__tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#cms-tab-sections" role="tab">
                        Page content
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#cms-tab-seo" role="tab">
                        Search listing
                    </a>
                </li>
            </ul>

            <div class="tab-content cms-admin__tab-panels">
                <div class="tab-pane fade show active" id="cms-tab-sections" role="tabpanel">
                    <div class="cms-admin__palette">
                        <p class="cms-admin__palette-intro">Click a block to add it to the page. Drag the grip icon to change the order. The first block is usually a Hero header.</p>
                        @foreach($sectionCatalog as $groupName => $items)
                            <div class="cms-admin__palette-group">
                                <h4 class="cms-admin__palette-group-title">{{ $groupName }}</h4>
                                <div class="cms-admin__palette-grid">
                                    @foreach($items as $type => $meta)
                                        <button type="button" class="cms-admin__palette-btn" data-add-section="{{ $type }}"
                                                title="{{ $meta['desc'] ?? '' }}">
                                            <i class="fas {{ $meta['icon'] ?? 'fa-cube' }}" aria-hidden="true"></i>
                                            <span>{{ \App\Support\CmsSectionRegistry::label($type) }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="cms-admin__sections-head">
                        <h3>Content on this page <span class="ab-pill" id="cms-section-count-badge">{{ $sectionCount }}</span></h3>
                        <p>Shown from top to bottom, the same way visitors will read it.</p>
                    </div>

                    <div id="cms-sections-list" class="cms-sections-list"></div>
                    <div id="cms-sections-empty" class="cms-sections-empty">
                        <i class="fas fa-layer-group" aria-hidden="true"></i>
                        <p>No content blocks yet</p>
                        <small>Add a <strong>Hero</strong> block above, then stack more blocks underneath.</small>
                    </div>
                </div>

                <div class="tab-pane fade" id="cms-tab-seo" role="tabpanel">
                    <div class="cms-admin__seo">
                        <h3>How this page appears in Google</h3>
                        <p>Leave these blank to use the page title. A clear title and short description help people find the page.</p>

                        <div class="form-group">
                            <label for="meta_title">Search title</label>
                            <input type="text" name="meta_title" id="meta_title" class="form-control @error('meta_title') is-invalid @enderror"
                                   value="{{ old('meta_title', $page->meta_title) }}" maxlength="255"
                                   placeholder="{{ old('title', $page->title) ?: 'Uses the page title if empty' }}">
                            <small class="text-muted"><span id="meta-title-count">0</span> / 255 characters</small>
                            @error('meta_title')<small class="ab-error">{{ $message }}</small>@enderror
                        </div>

                        <div class="form-group mb-0">
                            <label for="meta_description">Search description</label>
                            <textarea name="meta_description" id="meta_description" class="form-control @error('meta_description') is-invalid @enderror" rows="4"
                                      maxlength="500" placeholder="One or two sentences about this page">{{ old('meta_description', $page->meta_description) }}</textarea>
                            <small class="text-muted"><span id="meta-desc-count">0</span> / 500 characters</small>
                            @error('meta_description')<small class="ab-error">{{ $message }}</small>@enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" name="sections_payload" id="sections_payload" value="{{ old('sections_payload') }}">

<script type="application/json" id="cms-section-types-data">@json($builderConfig)</script>

@include('admin.cms_pages._section_templates')
