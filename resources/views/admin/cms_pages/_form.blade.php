@php
    $sectionsJson = old('sections_json', json_encode($sections ?? []));
    $sectionCatalog = $sectionCatalog ?? \App\Support\CmsSectionRegistry::adminCatalog();
    $isEdit = isset($page) && $page->exists;
    $previewUrl = $isEdit && $page->status === 'published' ? url('/' . $page->slug) : null;
@endphp

<div class="cms-admin" id="cms-admin">
    {{-- Sticky action bar --}}
    <div class="cms-admin__toolbar">
        <div class="cms-admin__toolbar-meta">
            @if($isEdit)
                <span @class(['cms-admin__status', 'cms-admin__status--live' => $page->status === 'published'])>
                    {{ $page->status === 'published' ? 'Published' : 'Draft' }}
                </span>
                <span class="cms-admin__url"><code>/{{ $page->slug }}</code></span>
            @else
                <span class="cms-admin__status">New page</span>
            @endif
        </div>
        <div class="cms-admin__toolbar-actions">
            @if($previewUrl)
                <a href="{{ $previewUrl }}" class="btn btn-light btn-sm" target="_blank" rel="noopener">
                    <i class="fas fa-external-link-alt"></i> Preview
                </a>
            @endif
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="fas fa-save"></i> Save page
            </button>
        </div>
    </div>

    <div class="cms-admin__layout">
        {{-- Left sidebar: page settings --}}
        <aside class="cms-admin__sidebar">
            <div class="cms-admin__panel">
                <h3 class="cms-admin__panel-title"><i class="fas fa-file-alt"></i> Page settings</h3>

                <div class="form-group">
                    <label for="title">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror"
                           value="{{ old('title', $page->title) }}" required placeholder="e.g. For Businesses">
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="slug">URL slug <span class="text-danger">*</span></label>
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend"><span class="input-group-text">/</span></div>
                        <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror"
                               value="{{ old('slug', $page->slug) }}" required pattern="[a-z0-9]+(?:-[a-z0-9]+)*"
                               placeholder="for-businesses">
                    </div>
                    @error('slug')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    <small class="form-text text-muted">Lowercase letters, numbers, and hyphens only.</small>
                </div>

                <div class="form-group">
                    <label for="template">Layout template</label>
                    <select name="template" id="template" class="form-control form-control-sm">
                        @foreach($templates as $key => $label)
                            <option value="{{ $key }}" @selected(old('template', $page->template) === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted">Controls page width and hero treatment on the frontend.</small>
                </div>

                <div class="form-group mb-0">
                    <label for="status">Visibility</label>
                    <select name="status" id="status" class="form-control form-control-sm">
                        <option value="draft" @selected(old('status', $page->status) === 'draft')>Draft — hidden from visitors</option>
                        <option value="published" @selected(old('status', $page->status) === 'published')>Published — live on site</option>
                    </select>
                </div>
            </div>

            @if($isEdit)
                <div class="cms-admin__panel cms-admin__panel--muted">
                    <dl class="cms-admin__stats">
                        <div><dt>Sections</dt><dd id="cms-section-count">{{ count($sections ?? []) }}</dd></div>
                        <div><dt>Last updated</dt><dd>{{ $page->updated_at?->format('M j, Y g:i A') ?? '—' }}</dd></div>
                    </dl>
                </div>
            @endif
        </aside>

        {{-- Main editor --}}
        <div class="cms-admin__main">
            <ul class="nav nav-tabs cms-admin__tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#cms-tab-sections" role="tab">
                        <i class="fas fa-th-list"></i> Sections
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#cms-tab-seo" role="tab">
                        <i class="fas fa-search"></i> SEO
                    </a>
                </li>
            </ul>

            <div class="tab-content cms-admin__tab-panels">
                {{-- Sections tab --}}
                <div class="tab-pane fade show active" id="cms-tab-sections" role="tabpanel">
                    <div class="cms-admin__palette">
                        <p class="cms-admin__palette-intro">Click a block to add it. Drag sections to reorder. Each block maps to a frontend component styled like the rest of BrokersCourt.</p>
                        @foreach($sectionCatalog as $groupName => $items)
                            <div class="cms-admin__palette-group">
                                <h4 class="cms-admin__palette-group-title">{{ $groupName }}</h4>
                                <div class="cms-admin__palette-grid">
                                    @foreach($items as $type => $meta)
                                        <button type="button" class="cms-admin__palette-btn" data-add-section="{{ $type }}"
                                                title="{{ $meta['desc'] ?? '' }}">
                                            <i class="fas {{ $meta['icon'] ?? 'fa-cube' }}"></i>
                                            <span>{{ \App\Support\CmsSectionRegistry::label($type) }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="cms-admin__sections-head">
                        <h3>Page content <span class="badge badge-light" id="cms-section-count-badge">{{ count($sections ?? []) }}</span></h3>
                        <p class="text-muted mb-0">Top to bottom — the first section is usually the Hero header.</p>
                    </div>

                    <div id="cms-sections-list" class="cms-sections-list"></div>
                    <div id="cms-sections-empty" class="cms-sections-empty">
                        <i class="fas fa-layer-group"></i>
                        <p>No sections yet</p>
                        <small>Add a <strong>Hero</strong> block above to start, then stack content sections below it.</small>
                    </div>
                </div>

                {{-- SEO tab --}}
                <div class="tab-pane fade" id="cms-tab-seo" role="tabpanel">
                    <div class="cms-admin__panel cms-admin__panel--flat">
                        <h3 class="cms-admin__panel-title">Search engine settings</h3>
                        <p class="text-muted">Leave meta title empty to use the page title. These values appear in Google results and social shares.</p>

                        <div class="form-group">
                            <label for="meta_title">Meta title</label>
                            <input type="text" name="meta_title" id="meta_title" class="form-control"
                                   value="{{ old('meta_title', $page->meta_title) }}" maxlength="255"
                                   placeholder="{{ old('title', $page->title) ?: 'Page title used if empty' }}">
                            <small class="form-text text-muted"><span id="meta-title-count">0</span>/255 characters</small>
                        </div>

                        <div class="form-group mb-0">
                            <label for="meta_description">Meta description</label>
                            <textarea name="meta_description" id="meta_description" class="form-control" rows="4"
                                      maxlength="500" placeholder="Brief summary for search results…">{{ old('meta_description', $page->meta_description) }}</textarea>
                            <small class="form-text text-muted"><span id="meta-desc-count">0</span>/500 characters</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" name="sections_payload" id="sections_payload" value="">

<script type="application/json" id="cms-section-types-data">
{!! json_encode([
    'types' => $sectionTypes,
    'catalog' => \App\Support\CmsSectionRegistry::adminCatalogFlat(),
    'defaults' => collect(array_keys($sectionTypes))->mapWithKeys(fn ($t) => [$t => \App\Support\CmsSectionRegistry::defaults($t)])->all(),
    'initial' => json_decode($sectionsJson, true) ?: [],
]) !!}
</script>

@include('admin.cms_pages._section_templates')
